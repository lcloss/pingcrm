<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    // PHP Unit version
//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        $this->withoutVite();
//    }

    // Pest version
//    use CreatesApplication;   -- Old version?
    use LazilyRefreshDatabase;
}
