<?php

namespace phpmock\prophecy;

use phpmock\MockRegistry;
use phpmock\MockBuilder;
use Prophecy\Prophet;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Exception\Prediction\AggregateException;

/**
 * A Prophet for built-in PHP functions.
 *
 * Example:
 * <code>
 * $prophet = new PHPProphet();
 *
 * $prophecy = $prophet->prophesize(__NAMESPACE__);
 * $prophecy->time()->willReturn(123);
 * $prophecy->reveal();
 *
 * assert(123 == time());
 * $prophet->checkPredictions();
 * <code>
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
final class PHPProphet
{

    /**
     * @var Prophet The prophet.
     */
    private $prophet;
    
    /**
     * Builds the prophet.
     *
     * @param Prophet|null $prophet optional proxied prophet
     */
    public function __construct(Prophet $prophet = null)
    {
        if (is_null($prophet)) {
            $prophet = new Prophet();
        }
        
        $revealer = new ReferencePreservingRevealer(self::getProperty($prophet, "revealer"));
        $util     = self::getProperty($prophet, "util");
        $this->prophet = new Prophet($prophet->getDoubler(), $revealer, $util);
    }
    
    /**
     * Creates a new function prophecy for a given namespace.
     *
     * @param string $namespace function namespace
     *
     * @return ProphecyInterface function prophecy
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
     * @throws AggregateException If any prediction fails.
     */
    public function checkPredictions()
    {
        MockRegistry::getInstance()->unregisterAll();
        $this->prophet->checkPredictions();
    }
    
    /**
     * Defines the function prophecy in the given namespace.
     *
     * In most cases you don't have to call this method. {@link prophesize()}
     * is doing this for you. But if the prophecy is defined after the first
     * call in the tested class, the tested class doesn't resolve to the prophecy.
     * This is documented in Bug #68541. You therefore have to define
     * the namespaced function before the first call.
     *
     * Defining the function has no side effects. If the function was
     * already defined this method does nothing.
     *
     * @param string $namespace function namespace
     * @param string $name      function name
     *
     * @see prophesize()
     * @link https://bugs.php.net/bug.php?id=68541 Bug #68541
     */
    public static function define($namespace, $name)
    {
        $builder = new MockBuilder();
        $builder->setNamespace($namespace)
            ->setName($name)
            ->setFunction(function () {
            })
            ->build()
            ->define();
    }
    
    /**
     * Returns a private property of a prophet.
     *
     * @param Prophet $prophet  prophet
     * @param string  $property property name
     *
     * @return mixed property value of that prophet
     */
    private static function getProperty(Prophet $prophet, $property)
    {
        $reflection = new \ReflectionProperty($prophet, $property);
        $reflection->setAccessible(true);
        return $reflection->getValue($prophet);
    }
}
