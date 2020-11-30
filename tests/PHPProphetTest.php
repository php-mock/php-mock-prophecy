<?php

namespace phpmock\prophecy;

use phpmock\AbstractMockTest;
use Prophecy\Argument;

/**
 * A tests for PHPProphet.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see PHPProphet
 */
final class PHPProphetTest extends AbstractMockTest
{
    
    /**
     * @var PHPProphet The SUT.
     */
    private $prophet;

    protected function setUp(): void
    {
        parent::setUp();

        $this->prophet = new PHPProphet();
    }

    protected function disableMocks()
    {
        $this->prophet->checkPredictions();
    }

    protected function mockFunction($namespace, $functionName, callable $function)
    {
        $prophecy = $this->prophet->prophesize($namespace);
        $prophecy->$functionName(Argument::cetera())->will(function (array $parameters) use ($function) {
            return call_user_func_array($function, $parameters);
        });
        $prophecy->reveal();
    }

    protected function defineFunction($namespace, $functionName)
    {
        PHPProphet::define($namespace, $functionName);
    }

    /**
     * This test is skipped until PHPUnit#2016 is resolved.
     *
     * @see https://github.com/sebastianbergmann/phpunit/issues/2016
     */
    public function testBackupStaticAttributes()
    {
        $this->markTestSkipped("Skip until PHPUnit#2016 is resolved");
    }

    /**
     * Pass-By-Reference is not supported in Prophecy.
     *
     * @see https://github.com/phpspec/prophecy/issues/225
     */
    public function testPassingByReference()
    {
        $this->markTestSkipped("Pass-By-Reference is not supported in Prophecy");
    }
}
