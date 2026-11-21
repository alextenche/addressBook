// Progressive enhancement: every action also works as a plain HTML form without this file.

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const dialog = document.getElementById('contact-dialog');
const form = dialog?.querySelector('[data-contact-form]');
const list = document.getElementById('contact-list');
const filter = document.querySelector('[data-filter]');
const toastEl = document.querySelector('.toast');

/** @type {number|null} id of the contact being edited, or null when adding */
let editingId = null;

async function api(url, { method = 'GET', body } = {}) {
  const response = await fetch(url, {
    method,
    headers: {
      Accept: 'application/json',
      'X-CSRF-Token': csrfToken,
      ...(body ? { 'Content-Type': 'application/json' } : {}),
    },
    body: body ? JSON.stringify(body) : undefined,
  });
  const payload = response.status === 204 ? null : await response.json().catch(() => null);
  return { ok: response.ok, status: response.status, payload };
}

function toast(message, tone = 'info') {
  if (!toastEl) return;
  toastEl.textContent = message;
  toastEl.dataset.tone = tone;
  toastEl.hidden = false;
  clearTimeout(toast.timer);
  toast.timer = setTimeout(() => { toastEl.hidden = true; }, 3500);
}

function failed(status) {
  toast(status === 403 ? 'Your session expired. Reload the page and try again.' : 'Something went wrong. Please try again.', 'error');
}

// ---- List and filtering -------------------------------------------------

function filterParams() {
  const params = new URLSearchParams(filter ? new FormData(filter) : undefined);
  for (const [key, value] of [...params]) if (value === '') params.delete(key);
  return params;
}

async function refreshList() {
  const params = filterParams();
  history.replaceState(null, '', params.size ? `/?${params}` : '/');
  params.set('fragment', 'list');
  const response = await fetch(`/?${params}`, { headers: { Accept: 'text/html' } });
  if (response.ok) list.innerHTML = await response.text();
}

let filterTimer;
filter?.addEventListener('input', () => {
  clearTimeout(filterTimer);
  filterTimer = setTimeout(refreshList, 200);
});
filter?.addEventListener('submit', (event) => {
  event.preventDefault();
  refreshList();
});

// ---- Add / edit dialog --------------------------------------------------

function clearErrors() {
  form.querySelectorAll('.field--invalid').forEach((el) => el.classList.remove('field--invalid'));
  form.querySelectorAll('.field__error').forEach((el) => { el.textContent = ''; });
}

function showErrors(errors) {
  let first = null;
  for (const [name, message] of Object.entries(errors)) {
    const field = form.querySelector(`[data-field="${CSS.escape(name)}"]`);
    if (!field) continue;
    field.classList.add('field--invalid');
    field.querySelector('.field__error').textContent = message;
    first ??= field.querySelector('input, textarea');
  }
  first?.focus();
}

function openDialog(contact = null) {
  editingId = contact?.id ?? null;
  form.reset();
  clearErrors();
  for (const element of form.elements) {
    if (!element.name || element.name.startsWith('_') || element.type === 'radio') continue;
    element.value = contact?.[element.name] ?? '';
  }
  form.elements.contact_group.value = contact?.contact_group ?? 'friends';
  dialog.querySelector('h2').textContent = contact ? `Edit ${contact.first_name} ${contact.last_name}` : 'Add contact';
  dialog.showModal();
  form.elements.first_name.focus();
}

form?.addEventListener('submit', async (event) => {
  event.preventDefault();
  clearErrors();

  const data = Object.fromEntries(new FormData(form));
  delete data._METHOD;
  delete data._csrf;

  const submit = form.querySelector('[type="submit"]');
  submit.disabled = true;
  const { ok, status, payload } = await api(editingId ? `/contacts/${editingId}` : '/contacts', {
    method: editingId ? 'PUT' : 'POST',
    body: data,
  }).finally(() => { submit.disabled = false; });

  if (status === 422) return showErrors(payload?.errors ?? {});
  if (!ok) return failed(status);

  dialog.close();
  toast(`${editingId ? 'Saved' : 'Added'} ${payload.data.first_name} ${payload.data.last_name}.`);
  refreshList();
});

dialog?.addEventListener('click', (event) => {
  if (event.target === dialog) dialog.close(); // click on the backdrop
});

// ---- Delegated clicks (list rows are re-rendered, so bind on document) ----

document.addEventListener('click', async (event) => {
  const trigger = event.target.closest('[data-action]');
  if (!trigger || !dialog) return;

  if (trigger.dataset.action === 'add') {
    event.preventDefault();
    openDialog();
  } else if (trigger.dataset.action === 'cancel' && dialog.contains(trigger)) {
    event.preventDefault();
    dialog.close();
  } else if (trigger.dataset.action === 'edit') {
    event.preventDefault();
    const { ok, status, payload } = await api(`/contacts/${trigger.dataset.id}`);
    if (!ok) return failed(status);
    openDialog(payload.data);
  }
});

document.addEventListener('submit', async (event) => {
  const deleteForm = event.target.closest('form[data-action="delete"]');
  if (!deleteForm) return;
  event.preventDefault();
  if (!confirm(`Delete ${deleteForm.dataset.name}? This cannot be undone.`)) return;

  const { ok, status } = await api(deleteForm.action, { method: 'DELETE' });
  if (!ok) return failed(status);
  toast(`Deleted ${deleteForm.dataset.name}.`);
  refreshList();
});
