<?php

namespace Tests;

use Database\Seeders\ObjectTableSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $seeder = ObjectTableSeeder::class;
}
