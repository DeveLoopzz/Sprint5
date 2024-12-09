<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ArmorsTest extends TestCase
{
    use DatabaseTransactions;
    protected $skill;
    protected $armor;
    public function setup(): void
    {
        parent::setup();
        Artisan::call('migrate');
        Artisan::call('db.seed');
        Artisan::call('passport:client', [
            '--name' => 'ClientTest',
            '--no-interaction' => true,
            '--personal' => true,
        ]);

    }
}
