<?php

use App\Rules\IsValidEmailAddress;

uses()->group('laracasts');

it('can validate email', function () {
    $rule = new IsValidEmailAddress();

    expect($rule->passes('email', 'me@you.com'))->toBeTrue();
})->skip(fn () => config('app.env') === 'production', 'This test is skipped in production.');

it('throws an exception if the value is not a string', function () {
    $rule = new IsValidEmailAddress();

    expect($rule->passes('email', 123))->toBeFalse();
})->throws(Error::class, 'The value must be a string');

it('has better regex support and catch more email addresses');
