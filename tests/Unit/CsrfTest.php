<?php

declare(strict_types=1);

use App\Http\Csrf;
use App\Http\Session\ArraySession;

it('issues one stable token per session', function () {
    $csrf = new Csrf(new ArraySession());

    expect($csrf->token())->toHaveLength(64)->toBe($csrf->token());
});

it('validates only the matching token', function () {
    $csrf = new Csrf(new ArraySession());

    expect($csrf->isValid($csrf->token()))->toBeTrue()
        ->and($csrf->isValid('forged'))->toBeFalse()
        ->and($csrf->isValid(''))->toBeFalse()
        ->and($csrf->isValid(null))->toBeFalse()
        ->and((new Csrf(new ArraySession()))->isValid($csrf->token()))->toBeFalse();
});
