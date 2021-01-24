<?php

namespace MuhammadInaamMunir\SpeedAdmin\Tests;

use MuhammadInaamMunir\SpeedAdmin\Facades\SpeedAdmin;
use MuhammadInaamMunir\SpeedAdmin\ServiceProvider;
use Orchestra\Testbench\TestCase;

class SpeedAdminTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'speed-admin' => SpeedAdmin::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
