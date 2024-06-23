<?php

use Inertia\Testing\AssertableInertia as Assert;

uses()->group('organizations');

todo('it can create organization');

it('can view organizations', function () {
    $user = createUserWithManyOrganizations();

    login($user)
        ->get('/organizations')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Organizations/Index')
            ->has('organizations.data', 2)
            ->has('organizations.data.0', fn (Assert $assert) => $assert
                ->has('id')
                ->where('name', 'Apple')
                ->where('phone', '647-943-4400')
                ->where('city', 'Toronto')
                ->where('deleted_at', null)
            )
            ->has('organizations.data.1', fn (Assert $assert) => $assert
                ->has('id')
                ->where('name', 'Microsoft')
                ->where('phone', '877-568-2495')
                ->where('city', 'Redmond')
                ->where('deleted_at', null)
            )
        );
});

it('can search for organizations', function () {
    $user = createUserWithManyOrganizations();

    login($user)
        ->get('/organizations?search=Apple')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Organizations/Index')
            ->where('filters.search', 'Apple')
            ->has('organizations.data', 1)
            ->has('organizations.data.0', fn (Assert $assert) => $assert
                ->has('id')
                ->where('name', 'Apple')
                ->where('phone', '647-943-4400')
                ->where('city', 'Toronto')
                ->where('deleted_at', null)
            )
        );
});

todo('it can edit an organization');

it('cannot view deleted organizations', function () {
    $user = createUserWithManyOrganizations();

    $user->account->organizations()->firstWhere('name', 'Microsoft')->delete();

    login($user)
        ->get('/organizations')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Organizations/Index')
            ->has('organizations.data', 1)
            ->where('organizations.data.0.name', 'Apple')
        );
});

it('can filter to view deleted organizations', function () {
    $user = createUserWithManyOrganizations();

    login($user)
        ->get('/organizations?trashed=with')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Organizations/Index')
            ->has('organizations.data', 2)
            ->where('organizations.data.0.name', 'Apple')
            ->where('organizations.data.1.name', 'Microsoft')
        );
});
