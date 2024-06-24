<?php

use Inertia\Testing\AssertableInertia as Assert;

uses()->group('contacts');

todo('it can create contact');

it('can view contacts', function () {
    login()
        ->get('/contacts')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Contacts/Index')
            ->has('contacts.data', 2)
            ->has('contacts.data.0', fn (Assert $assert) => $assert
                ->has('id')
                ->where('name', 'Martin Abbott')
                ->where('phone', '555-111-2222')
                ->where('city', 'Murphyland')
                ->where('deleted_at', null)
                ->has('organization', fn (Assert $assert) => $assert
                    ->where('name', 'Example Organization Inc.')
                )
            )
            ->has('contacts.data.1', fn (Assert $assert) => $assert
                ->has('id')
                ->where('name', 'Lynn Kub')
                ->where('phone', '555-333-4444')
                ->where('city', 'Woodstock')
                ->where('deleted_at', null)
                ->has('organization', fn (Assert $assert) => $assert
                    ->where('name', 'Example Organization Inc.')
                )
            )
        );
});

it('can view contacts rewritted', function () {
    $response = login()
        ->get('/contacts');

    expect($response)
        ->toContain('contacts.data', 2)

        ->toContain('contacts.data.0', fn (Assert $assert) => $assert
            ->has('id')
            ->where('name', 'Martin Abbott')
            ->where('phone', '555-111-2222')
            ->where('city', 'Murphyland')
            ->where('deleted_at', null)
            ->has('organization', fn (Assert $assert) => $assert
                ->where('name', 'Example Organization Inc.')
            ))

        ->toContain('contacts.data.1', fn (Assert $assert) => $assert
            ->has('id')
            ->where('name', 'Lynn Kub')
            ->where('phone', '555-333-4444')
            ->where('city', 'Woodstock')
            ->where('deleted_at', null)
            ->has('organization', fn (Assert $assert) => $assert
                ->where('name', 'Example Organization Inc.')
            ));
});

it('can search for contacts', function () {
    login()
        ->get('/contacts?search=Martin')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Contacts/Index')
            ->where('filters.search', 'Martin')
            ->has('contacts.data', 1)
            ->has('contacts.data.0', fn (Assert $assert) => $assert
                ->has('id')
                ->where('name', 'Martin Abbott')
                ->where('phone', '555-111-2222')
                ->where('city', 'Murphyland')
                ->where('deleted_at', null)
                ->has('organization', fn (Assert $assert) => $assert
                    ->where('name', 'Example Organization Inc.')
                )
            )
        );
});

todo('it can edit a contact');

it('cannot view deleted contacts', function () {
    $user = createUser();
    $user->account->contacts()->firstWhere('first_name', 'Martin')->delete();

    login($user)
        ->get('/contacts')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Contacts/Index')
            ->has('contacts.data', 1)
            ->where('contacts.data.0.name', 'Lynn Kub')
        );
});

it('can filter to view deleted contacts', function () {
    $user = createUser();
    $user->account->contacts()->firstWhere('first_name', 'Martin')->delete();

    login($user)
        ->get('/contacts?trashed=with')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Contacts/Index')
            ->has('contacts.data', 2)
            ->where('contacts.data.0.name', 'Martin Abbott')
            ->where('contacts.data.1.name', 'Lynn Kub')
        );

});

todo('it can delete contacts');

todo('it cannot delete contacts belong to someone else');
