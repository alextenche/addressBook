<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Contact;
use App\Domain\ContactGroup;
use App\Http\Session\Session;
use App\Repository\ContactRepository;
use App\Validation\ContactValidator;
use App\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;

/**
 * Every action serves two clients: the browser without JavaScript (HTML pages, forms,
 * redirects) and the JavaScript front end (JSON, sent with "Accept: application/json").
 */
final class ContactController
{
    private const string FLASH = 'flash';

    public function __construct(
        private readonly ContactRepository $contacts,
        private readonly ContactValidator $validator,
        private readonly Twig $view,
        private readonly Session $session,
    ) {}

    public function index(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $query = is_string($params['q'] ?? null) ? $params['q'] : '';
        $group = is_string($params['group'] ?? null) ? ContactGroup::tryFrom($params['group']) : null;
        $contacts = $this->contacts->search($query, $group);

        $data = ['contacts' => $contacts, 'query' => $query, 'group' => $group?->value, 'groups' => ContactGroup::cases()];

        // The front end asks for just the list when filtering or after a change.
        if (($params['fragment'] ?? null) === 'list') {
            return $this->view->render($response, 'contacts/_list.twig', $data);
        }

        return $this->view->render($response, 'contacts/index.twig', $data + [
            'flash' => $this->session->pull(self::FLASH),
            'form' => $this->blankForm(),
        ]);
    }

    public function list(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $query = is_string($params['q'] ?? null) ? $params['q'] : null;
        $group = is_string($params['group'] ?? null) ? ContactGroup::tryFrom($params['group']) : null;

        $data = array_map(static fn(Contact $c): array => $c->toArray(), $this->contacts->search($query, $group));

        return $this->json($response, ['data' => $data]);
    }

    /** @param array{id: string} $args */
    public function show(Request $request, Response $response, array $args): Response
    {
        $contact = $this->findOr404($request, (int) $args['id']);

        if (!$this->wantsJson($request)) {
            return $response->withStatus(302)->withHeader('Location', "/contacts/{$contact->id}/edit");
        }

        return $this->json($response, ['data' => $contact->toArray()]);
    }

    public function new(Request $request, Response $response): Response
    {
        return $this->renderForm($response, null, $this->blankForm());
    }

    public function store(Request $request, Response $response): Response
    {
        try {
            $contact = $this->contacts->create($this->validator->validate($this->body($request)));
        } catch (ValidationException $e) {
            return $this->invalid($request, $response, $e, null);
        }

        if ($this->wantsJson($request)) {
            return $this->json($response->withHeader('Location', "/contacts/{$contact->id}"), ['data' => $contact->toArray()], 201);
        }

        return $this->redirectHome($response, "Added {$contact->fullName()}.");
    }

    /** @param array{id: string} $args */
    public function edit(Request $request, Response $response, array $args): Response
    {
        $contact = $this->findOr404($request, (int) $args['id']);

        return $this->renderForm($response, $contact, $this->formValues($contact->toArray()));
    }

    /** @param array{id: string} $args */
    public function update(Request $request, Response $response, array $args): Response
    {
        $existing = $this->findOr404($request, (int) $args['id']);

        try {
            $contact = $this->contacts->update($existing->id, $this->validator->validate($this->body($request)))
                ?? throw new HttpNotFoundException($request);
        } catch (ValidationException $e) {
            return $this->invalid($request, $response, $e, $existing);
        }

        if ($this->wantsJson($request)) {
            return $this->json($response, ['data' => $contact->toArray()]);
        }

        return $this->redirectHome($response, "Saved {$contact->fullName()}.");
    }

    /** @param array{id: string} $args */
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $contact = $this->findOr404($request, (int) $args['id']);
        $this->contacts->delete($contact->id);

        if ($this->wantsJson($request)) {
            return $response->withStatus(204);
        }

        return $this->redirectHome($response, "Deleted {$contact->fullName()}.");
    }

    private function findOr404(Request $request, int $id): Contact
    {
        return $this->contacts->find($id) ?? throw new HttpNotFoundException($request, 'Contact not found.');
    }

    private function invalid(Request $request, Response $response, ValidationException $e, ?Contact $contact): Response
    {
        if ($this->wantsJson($request)) {
            return $this->json($response, ['errors' => $e->errors], 422);
        }

        return $this->renderForm($response->withStatus(422), $contact, $e->input, $e->errors);
    }

    /**
     * @param array<string, mixed>  $values
     * @param array<string, string> $errors
     */
    private function renderForm(Response $response, ?Contact $contact, array $values, array $errors = []): Response
    {
        return $this->view->render($response, 'contacts/form.twig', [
            'contact' => $contact,
            'form' => $values,
            'errors' => $errors,
            'groups' => ContactGroup::cases(),
        ]);
    }

    /** @return array<string, string> */
    private function blankForm(): array
    {
        return $this->formValues(['contact_group' => ContactGroup::Friends->value]);
    }

    /**
     * @param array<string, mixed> $values
     *
     * @return array<string, string>
     */
    private function formValues(array $values): array
    {
        $fields = ['first_name', 'last_name', 'email', 'phone', 'address_line1', 'address_line2',
            'city', 'region', 'postal_code', 'contact_group', 'notes'];

        $form = [];
        foreach ($fields as $field) {
            $value = $values[$field] ?? '';
            $form[$field] = is_scalar($value) ? (string) $value : '';
        }

        return $form;
    }

    /** @return array<mixed> */
    private function body(Request $request): array
    {
        $body = $request->getParsedBody();

        return is_array($body) ? $body : [];
    }

    private function wantsJson(Request $request): bool
    {
        return str_contains($request->getHeaderLine('Accept'), 'application/json');
    }

    /** @param array<string, mixed> $payload */
    private function json(Response $response, array $payload, int $status = 200): Response
    {
        $response->getBody()->write((string) json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));

        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }

    private function redirectHome(Response $response, string $message): Response
    {
        $this->session->set(self::FLASH, $message);

        // 303 so the browser follows up with a GET, whatever method the form used.
        return $response->withStatus(303)->withHeader('Location', '/');
    }
}
