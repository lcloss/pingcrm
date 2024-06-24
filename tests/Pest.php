<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\ExpectationFailedException;

uses(
    Tests\TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBePhoneNumber', function () {
    expect($this->value)->toBestring()->toStartWith('+');

    if (strlen($this->value) < 6) {
        throw new ExpectationFailedException('Phone numbers must be at least 6 characters long.');
    }

    if (! is_numeric(Str::of($this->value)->after('+')->remove([' ', '-'])->__toString())) {
        throw new ExpectationFailedException('Phone numbers must contain only numeric characters.');
    }
});

expect()->intercept('toBe', Model::class, function ($model) {
    expect($this->value->is($model))->toBeTrue(message: 'Failed asserting that two models reference the same object.');
});

expect()->intercept('toContain', TestResponse::class, function (...$value) {
    $this->value->assertInertia(fn (AssertableInertia $inertia) => $inertia->has(...$value));
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createUser()
{
    $user = User::factory()->create([
        'account_id' => Account::create(['name' => 'Acme Corporation'])->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'johndoe@example.com',
        'owner' => true,
    ]);

    $organization = $user->account->organizations()->create(['name' => 'Example Organization Inc.']);

    $user->account->contacts()->createMany([
        [
            'organization_id' => $organization->id,
            'first_name' => 'Martin',
            'last_name' => 'Abbott',
            'email' => 'martin.abbott@example.com',
            'phone' => '555-111-2222',
            'address' => '330 Glenda Shore',
            'city' => 'Murphyland',
            'region' => 'Tennessee',
            'country' => 'US',
            'postal_code' => '57851',
        ], [
            'organization_id' => $organization->id,
            'first_name' => 'Lynn',
            'last_name' => 'Kub',
            'email' => 'lynn.kub@example.com',
            'phone' => '555-333-4444',
            'address' => '199 Connelly Turnpike',
            'city' => 'Woodstock',
            'region' => 'Colorado',
            'country' => 'US',
            'postal_code' => '11623',
        ],
    ]);

    return $user;
}

function createUserWithManyOrganizations()
{
    $user = User::factory()->create([
        'account_id' => Account::create(['name' => 'Acme Corporation'])->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'johndoe@example.com',
        'owner' => true,
    ]);

    $user->account->organizations()->createMany([
        [
            'name' => 'Apple',
            'email' => 'info@apple.com',
            'phone' => '647-943-4400',
            'address' => '1600-120 Bremner Blvd',
            'city' => 'Toronto',
            'region' => 'ON',
            'country' => 'CA',
            'postal_code' => 'M5J 0A8',
        ], [
            'name' => 'Microsoft',
            'email' => 'info@microsoft.com',
            'phone' => '877-568-2495',
            'address' => 'One Microsoft Way',
            'city' => 'Redmond',
            'region' => 'WA',
            'country' => 'US',
            'postal_code' => '98052',
        ],
    ]);

    return $user;
}

function login(User $user = null)
{
    return test()->actingAs($user ?? createUser());
}
