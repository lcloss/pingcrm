<?php

it('do not user debugger functions')
    ->expect(['dd', 'dump', 'ray', 'die', 'exit', 'var_dump', 'print_r', 'xdebug'])->not
        ->toBeUsed();
    //->toBeUsedInFile(__DIR__ . '/../../app');

it('it uses redirect facade for redirecting')
    ->expect(['back', 'redirect', 'to_route'])
    ->not
    ->toBeUsedIn('App\\Http\\Controllers\\');

// Only for demonstration purposes
//it('cannot uses facades')
//    ->expect('Illuminate\Support\Facades')
//    ->not
//    ->toBeUsed();

