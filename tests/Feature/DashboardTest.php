<?php

todo('it can view dashboard');

test('it requires authentication', function () {
    $response = $this->get('/');

    $response->assertStatus(302)
        ->assertRedirect('/login');
});
