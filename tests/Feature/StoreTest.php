<?php

use App\Models\Contact;

it('can store a contact', function (array $data) {
    login()
        ->post('/contacts', [...[
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'phone' => fake()->e164PhoneNumber,
            'address' => '1 Test Street',
            'city' => 'Testerfield',
            'region' => 'Derbyshire',
            'country' => fake()->randomElement(['US', 'CA']),
            'postal_code' => fake()->postcode,
        ], ...$data])->assertRedirect('/contacts')
        ->assertSessionHas('success', 'Contact created.')
    ;

    $contact = Contact::orderBy('id', 'desc')->first();

    expect($contact)
        ->first_name->toBeString()->not->toBeEmpty()
        ->last_name->toBeString()->not->toBeEmpty()
        ->email->toBeString()->toContain('@')
        ->phone->toBePhoneNumber()
        ->address->toBeString()->not->toBeEmpty()
        ->city->toBeString()->not->toBeEmpty()
        ->region->toBe('Derbyshire')
        ->country->toBeIn(['US', 'CA']);

})->with([
    'generic' => [[]],
    'email with spaces' => [['email' => '"luke downing"@downing.tech']],
    'email .co.uk and name' => [['email' => 'info@me.co.uk', 'first_name' => 'Sharon']],
    '25 characters for postalcode' => [['postal_code' => str_repeat('A', 25)]],
]);

it('can store a contact with valid emails', function ($email) {
    login()
        ->post('/contacts', [...[
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => $email,
            'phone' => fake()->e164PhoneNumber,
            'address' => '1 Test Street',
            'city' => 'Testerfield',
            'region' => 'Derbyshire',
            'country' => fake()->randomElement(['US', 'CA']),
            'postal_code' => fake()->postcode,
        ]])->assertRedirect('/contacts')
        ->assertSessionHas('success', 'Contact created.')
    ;

    $contact = Contact::orderBy('id', 'desc')->first();

    expect($contact)
        ->first_name->toBeString()->not->toBeEmpty()
        ->last_name->toBeString()->not->toBeEmpty()
        ->email->toBeString()->toContain('@')
        ->phone->toBePhoneNumber()
        ->address->toBeString()->not->toBeEmpty()
        ->city->toBeString()->not->toBeEmpty()
        ->region->toBe('Derbyshire')
        ->country->toBeIn(['US', 'CA']);

})->with('valid emails');
