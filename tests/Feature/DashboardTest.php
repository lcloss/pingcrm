<?php

todo('it can view dashboard');

test('example', function () {
    $response = $this->get('/dashboard');

    $response->assertStatus(200);
});
