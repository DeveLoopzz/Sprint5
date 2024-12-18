<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        // Ejecutar migraciones antes de cada test
        $this->artisan('migrate:fresh');
        $this->artisan('passport:install');
    }
}
