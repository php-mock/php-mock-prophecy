<?php

namespace phpmock\prophecy;

use phpmock\MockBuilder;
use phpmock\integration\MockDelegateFunctionBuilder;
use Prophecy\Prophecy\ProphecyInterface;

/**
 * The single function revelation.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @internal
 */
class Revelation implements ProphecyInterface
{

    /**
     * @var string The function namespace.
     */
    private $namespace;

    /**
     * @var string The function name.
     */
    private $functionName;

    /**
     * @var ProphecyInterface The prophecy
     */
    private $prophecy;
    
    /**
     * Setup the revelation.
     * 
     * @param String $namespace           The namespace.
     * @param String $functionName        The function name.
     * @param ProphecyInterface $prophecy The prophecy.
     */
    public function __construct($namespace, $functionName, ProphecyInterface $prophecy)
    {
        $this->namespace    = $namespace;
        $this->functionName = $functionName;
        $this->prophecy     = $prophecy;
    }
    
    /**
     * Reveals the function prophecy.
     * 
     * I.e. the prophesized function will become effective.
     * 
     * @return Mock The enabled function mock.
     */
    public function reveal()
    {
        $delegate = $this->prophecy->reveal();
        $builder  = new MockBuilder();
        $builder->setNamespace($this->namespace)
                ->setName($this->functionName)
                ->setFunction([$delegate, MockDelegateFunctionBuilder::METHOD]);
        $mock = $builder->build();
        $mock->enable();
        return $mock;
    }
}
