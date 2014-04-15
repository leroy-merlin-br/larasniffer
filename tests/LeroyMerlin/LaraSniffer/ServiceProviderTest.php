<?php namespace LeroyMerlin\LaraSniffer;

use Mockery as m;
use PHPUnit_Framework_TestCase;

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Calls Mockery::close
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    public function testShouldBoot()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $sp = m::mock('LeroyMerlin\LaraSniffer\ServiceProvider[package]', ['something']);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $sp->shouldReceive('package')
            ->with('leroy-merlin-br/larasniffer')
            ->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $sp->boot();
    }

    public function testShouldRegister()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $test = $this;
        $app = m::mock('LaravelApp');
        $sp = m::mock('LeroyMerlin\LaraSniffer\ServiceProvider[register,commands]', [$app]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $sp->shouldReceive('register')
            ->passthru();

        $app->shouldReceive('bind')
            ->once()->andReturnUsing(
                // Make sure that the commands are being registered
                // with a closure that returns the correct
                // object.
                function ($name, $closure) use ($test, $app) {

                    $shouldBe = ['command.larasniffer' => 'LeroyMerlin\LaraSniffer\SniffCommand'];

                    $test->assertInstanceOf(
                        $shouldBe[$name],
                        $closure($app)
                    );
                }
            );

        $sp->shouldReceive('commands')
            ->with('command.larasniffer');

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $sp->register();
    }
}
