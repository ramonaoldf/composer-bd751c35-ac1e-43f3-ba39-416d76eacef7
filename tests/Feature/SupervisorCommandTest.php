<?php

namespace Laravel\Horizon\Tests\Feature;

use Laravel\Horizon\SupervisorFactory;
use Laravel\Horizon\Tests\IntegrationTest;

class SupervisorCommandTest extends IntegrationTest
{
    public function test_supervisor_command_can_start_supervisor_monitoring()
    {
        $this->app->instance(SupervisorFactory::class, $factory = new FakeSupervisorFactory);
        $this->artisan('horizon:supervisor', ['name' => 'foo', 'connection' => 'redis']);

        $this->assertTrue($factory->supervisor->monitoring);
        $this->assertTrue($factory->supervisor->working);
    }


    public function test_supervisor_command_can_start_paused_supervisors()
    {
        $this->app->instance(SupervisorFactory::class, $factory = new FakeSupervisorFactory);
        $this->artisan('horizon:supervisor', ['name' => 'foo', 'connection' => 'redis', '--paused' => true]);

        $this->assertFalse($factory->supervisor->working);
    }
}



class FakeSupervisorFactory
{
    public $supervisor;

    public function make($options)
    {
        return $this->supervisor = new Fakes\SupervisorWithFakeMonitor($options);
    }
}
