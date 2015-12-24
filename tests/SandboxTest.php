<?php

namespace test;

use phpmock\prophecy\PHPProphet;

class Foo
{
    
    public function noArgument()
    {
        throw new \RuntimeException("not mocked");
    }
    
    public function argument($arg)
    {
        throw new \RuntimeException("not mocked");
    }
    
    public function variadics1($arg1, $arg2 = 'default')
    {
        throw new \RuntimeException("not mocked");
    }
    
    // not supported https://github.com/phpspec/prophecy/issues/91
    /*public function variadics2(...$args)
    {
        throw new \RuntimeException("not mocked");
    }
     */
    
    public function reference(&$arg)
    {
        throw new \RuntimeException("not mocked");
    }
}

class SandboxTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Prophecy\Prophet()
     */
    private $prophet;
    
    /**
     * @var type
     */
    private $prophecy;
    
    protected function setUp()
    {
        parent::setUp();
        
        $revealer = new \phpmock\prophecy\ReferencePreservingRevealer(new \Prophecy\Prophecy\Revealer());
        
        $this->prophet  = new \Prophecy\Prophet(null, $revealer);
        $this->prophecy = $this->prophet->prophesize(Foo::class);
    }
    
    public function testPHPProphet()
    {
        $prophet = new PHPProphet();
        
        $prophecy = $prophet->prophesize(__NAMESPACE__);
        $prophecy->time()->willReturn(123);
        $prophecy->reveal();
        
        assert(123 == time());
        $prophet->checkPredictions();
    }
    
    public function testNoArgument()
    {
        $this->prophecy->noArgument()->willReturn("noarument");
        
        $foo = $this->prophecy->reveal();
        $this->assertEquals("noarument", $foo->noArgument());
    }

    public function testOneArgument()
    {
        $this->prophecy->argument("foo")->willReturn("bar");
        
        $foo = $this->prophecy->reveal();
        $this->assertEquals("bar", $foo->argument("foo"));
    }

    public function testVariadics1Argument()
    {
        $this->prophecy->variadics1("foo1")->willReturn("bar1");
        $this->prophecy->variadics1("foo1", "bar1")->willReturn("bar2");
        
        $this->prophecy->variadics1("foo2")->willReturnArgument();
        $this->prophecy->variadics1(1, 2)->will(function ($args) {
            return array_sum($args);
        });
        
        $foo = $this->prophecy->reveal();
        $this->assertEquals("bar1", $foo->variadics1("foo1"));
        $this->assertEquals("bar2", $foo->variadics1("foo1", "bar1"));
        $this->assertEquals("foo2", $foo->variadics1("foo2"));
        $this->assertEquals(3, $foo->variadics1(1, 2));
    }

    public function testReference()
    {
        $this->prophecy->reference(\Prophecy\Argument::cetera())->will(function (array $args) {
            call_user_func_array(function (&$arg) {
                $arg = "test4";

            }, $args);
        });
        
        $foo = $this->prophecy->reveal();
        
        $ref="ref";
        $foo->reference($ref);
        $this->assertEquals("test4", $ref);
    }

    public function testReference2()
    {
        $this->prophecy->reference(\Prophecy\Argument::cetera())->will(function (array $args) {
            $args[0] = "test5";
        });
        
        $foo = $this->prophecy->reveal();
        
        $ref="ref";
        $foo->reference($ref);
        $this->assertEquals("test5", $ref);
    }
}
