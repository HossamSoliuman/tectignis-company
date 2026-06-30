<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The array cache store persists within a process, so rememberForever()
        // from one test would otherwise leak into the next.
        Cache::flush();
    }
}
