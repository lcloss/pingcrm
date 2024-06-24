<?php

use App\Models\Account;
use App\Models\User;

it('can log in a user', function () {
    $credentials = [
        'email' => 'ted.farok@villain.com',
        'password' => 'password',
        'account_id' => Account::create(['name' => fake()->company])->id,
    ];

    $user = User::factory()->create($credentials);

    $this->post(route('login'), $credentials);

    $another_user = User::factory()->create(['account_id' => Account::create(['name' => fake()->company])->id]);

    // expect(Auth::user()->is($user))->toBeTrue();  // Rewriting in Pest to avoid original error
    expect(Auth::user())->toBe($user);  // Original: Failed asserting that two variables reference the same object.
    // expect(Auth::user())->toBe($another_user);  // To test message on intercept
});
