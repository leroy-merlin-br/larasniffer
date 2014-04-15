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
            'formatLine,runSniffer,terminate]'
        );

        $app = m::mock('LaravelApp');
        $command->shouldAllowMockingProtectedMethods();
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
            ->andReturn(true);

        $command->shouldReceive('formatLine')
            ->times(4)
            ->andReturnUsing(function ($input) {
                return $input;
            });

        $this->expectOutputString($sniffResult."\n");

        $command->shouldReceive('terminate')
            ->once();

       /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $command->fire();
        $this->assertEquals($app, $command->app);
        $this->assertEquals($config, $command->config);

    }

    public function testShouldRunSniffer()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $command = m::mock(
            'LeroyMerlin\LaraSniffer\SniffCommand[getPHPSnifferInstance]'
        );
        $command->shouldAllowMockingProtectedMethods();
        $command->app    = m::mock('LaravelApp');
        $command->config = m::mock('Config');
        $phpcs = m::mock("PHP_CodeSniffer_CLI");
        $options = ['standard'=>"PSRX", 'files'=>"path/to/files"];

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $command->shouldReceive('getPHPSnifferInstance')
            ->once()->andReturn($phpcs);

        $phpcs->shouldReceive('checkRequirements')
            ->once();

        $command->config->shouldReceive('get')
            ->with('larasniffer::standard', m::any())
            ->andReturn('PSRX');

        $command->config->shouldReceive('get')
            ->with('larasniffer::files', m::any())
            ->andReturn('path/to/files');

        $phpcs->shouldReceive('process')
            ->with($options)->once()
            ->andReturnUsing(function () {
                echo 'Something';
            });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertEquals('Something', $command->runSniffer());
    }
}
