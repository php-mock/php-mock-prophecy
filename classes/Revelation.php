<?php

namespace phpmock\prophecy;

use phpmock\MockBuilder;
use phpmock\integration\MockDelegateFunctionBuilder;
use Prophecy\Prophecy\ProphecyInterface;

/**
 * A function revelation.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @internal
 */
final class Revelation implements ProphecyInterface
{

    /**
     * @internal
     * @var string The function namespace.
     */
    public $namespace;

    /**
     * @internal
     * @var string The function name.
     */
    public $functionName;

    /**
     * @internal
     * @var ProphecyInterface The prophecy.
     */
    public $prophecy;

    /**
     * Builds the revelation.
     *
     * @param String $namespace           function namespace
     * @param String $functionName        function name
     * @param ProphecyInterface $prophecy prophecy
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
     * @return Mock enabled function mock
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
