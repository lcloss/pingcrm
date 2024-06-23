<?php

use Illuminate\Support\Facades\Storage;

it('can show supported image formats and options', function ( $path, $options) {
    Storage::fake()->put($path, file_get_contents(storage_path("fixtures/{$path}")));

    $response = $this->get(route('image', ['path' => $path, 'options' => $options]));

    $response->assertOk();

    expect($response->streamedContent())->not->toBeEmpty()->toBeString();
})->with([
    'example.png',
    'example.jpg',
    'example.webp',
])->with([
    ['w' => 40, 'h' => 40, 'fit' => 'crop'],
    ['w' => 120, 'h' => 120, 'fit' => 'crop'],
    ['w' => 20, 'h' => 20, 'fit' => 'crop'],
]);
