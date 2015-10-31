<?php

namespace phpmock\prophecy;

use phpmock\MockRegistry;
use Prophecy\Prophet;
use Prophecy\Exception\Prediction\AggregateException;

/**
 * PHPProphet creates prophecies for functions. 
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class PHPProphet
{

    /**
     * @var Prophet The prophet.
     */
    private $prophet;
    
    /**
     * Sets an optional prophet.
     * 
     * @param Prophet $prophet The prophet.
     */
    public function __construct(Prophet $prophet = null)
    {
        $this->prophet = is_null($prophet) ? new Prophet() : $prophet;
    }
    
    /**
     * Creates a new function prophecy.
     * 
     * @param string $namespace The function namespace.
     * 
     * @return FunctionProphecy The function prophecy.
     */
    public function prophesize($namespace)
    {
        return new FunctionProphecy($namespace, $this->prophet);
    }
    
    /**
     * Checks all predictions defined by prophecies of this Prophet.
     *
     * It will also disable all previously revealed function prophecies.
     * 
     * @throws AggregateException If any prediction fails
     */
    public function checkPredictions()
    {
        MockRegistry::getInstance()->unregisterAll();
        $this->prophet->checkPredictions();
    }
}
