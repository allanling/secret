<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Database\Seeders\ObjectTableSeeder;

abstract class TestCase extends BaseTestCase
{
    protected $seeder = ObjectTableSeeder::class;
    
}
