<?php

namespace phpmock\prophecy;

use phpmock\integration\MockDelegateFunctionBuilder;
use Prophecy\Prophet;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Prophecy\MethodProphecy;

/**
 * Function prophecy.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class FunctionProphecy implements ProphecyInterface
{
    
    /**
     * @var Prophet The prophet 
     */
    private $prophet;
    
    /**
     * @var string The namespace.
     */
    private $namespace;
    
    /**
     * @var Revelation[] The delegated prophecies.
     */
    private $revelations = [];
    
    /**
     * Sets the prophet.
     *
     * @param string  $namespace The namespace.
     * @param Prophet $prophet   The prophet.
     * @internal
     */
    public function __construct($namespace, Prophet $prophet)
    {
        $this->prophet   = $prophet;
        $this->namespace = $namespace;
    }
    
    /**
     * Creates a new function prophecy using specified function name and arguments.
     *
     * @param string $functionName The function name.
     * @param array  $arguments    The arguments.
     *
     * @return MethodProphecy The function prophecy.
     */
    public function __call($functionName, array $arguments)
    {
        $delegateBuilder = new MockDelegateFunctionBuilder();
        $delegateBuilder->build($functionName);
        $prophecy = $this->prophet->prophesize($delegateBuilder->getFullyQualifiedClassName());
        $this->revelations[] = new Revelation($this->namespace, $functionName, $prophecy);
        return $prophecy->__call(MockDelegateFunctionBuilder::METHOD, $arguments);
    }
    
    /**
     * Reveals the function prophecies.
     * 
     * I.e. the prophesized functions will become effective.
     */
    public function reveal()
    {
        foreach ($this->revelations as $revelation) {
            $revelation->reveal();
        }
    }
}
