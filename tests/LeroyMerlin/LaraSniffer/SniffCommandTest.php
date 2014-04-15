<?php namespace LeroyMerlin\LaraSniffer;

use Mockery as m;
use PHPUnit_Framework_TestCase;

class SniffCommandTest extends PHPUnit_Framework_TestCase
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

    public function testShouldFire()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $command = m::mock(
            'LeroyMerlin\LaraSniffer\SniffCommand['.
            'getLaravel,terminalHasColorSupport,'.
            'formatLine,runSniffer]'
        );

        $app = m::mock('LaravelApp');
        $config = m::mock('Config');

        $sniffResult = "Sniff\nResult\nFoo\nBar";

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $command->shouldReceive('getLaravel')
            ->andReturn($app);

        $app->shouldReceive('make')
            ->with('config')->andReturn($config);

        $command->shouldReceive('runSniffer')
            ->once()->andReturn($sniffResult);

        $command->shouldReceive('terminalHasColorSupport')
            ->times(4)
            ->return(true);

        $command->shouldReceive('formatLine')
            ->returnUsing(function ($input) {
                return $input;
            });

       /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertEquals($app, $command->app);
        $this->assertEquals($config, $command->config);

    }
}
