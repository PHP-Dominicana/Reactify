<?php

namespace Tests;

use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;

#[WithMigration]
#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithWorkbench;
}
