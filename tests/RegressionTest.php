<?php

namespace phpmock\prophecy;

use \Prophecy\Prophet;

/**
 * Regression tests for Prophecy.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class RegressionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Calling no optional parameter
     *
     * @test
     * @see https://github.com/php-mock/php-mock-prophecy/issues/1
     */
    public function expectingWithoutOptionalParameter()
    {
        $prophet = new Prophet();
        $prophecy = $prophet->prophesize(OptionalParameterHolder::class);
        $prophecy->call("arg1")->willReturn("mocked");
        $mock = $prophecy->reveal();
        
        $this->assertEquals("mocked", $mock->call("arg1"));
        $prophet->checkPredictions();
    }
}

// @codingStandardsIgnoreStart
class OptionalParameterHolder
{

    public function call($arg1, $optional = "optional")
    {
        return $arg1 . $optional;
    }
}
// @codingStandardsIgnoreEnd
