<?php

namespace phpmock\prophecy;

use phpmock\AbstractMockTest;
use Prophecy\Argument;

/**
 * Tests PHPProphet.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see PHPProphet
 */
class PHPProphetTest extends AbstractMockTest
{
    
    /**
     * @var PHPProphet The SUT. 
     */
    private $prophet;

    protected function setup()
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
        $prophecy->$functionName(Argument::cetera())->will(function(array $parameters) use ($function) {
            return call_user_func_array($function, $parameters);
        });
        $prophecy->reveal();
    }
}
