<?php

namespace Doctrine\AnnotationsTests;

use Doctrine\Annotations\Annotation\Target;
use Doctrine\Annotations\Context;
use Doctrine\AnnotationsTests\Fixtures\DummyClass;

class ContextTest extends TestCase
{
    public function testContextAccessors()
    {
        $class        = new \ReflectionClass('Doctrine\AnnotationsTests\Fixtures\Controller');
        $namespaces   = ['Doctrine\AnnotationsTests\Fixtures'];
        $ignoredNames = ['todo' => true];
        $imports      = [
            'template' => 'Doctrine\AnnotationsTests\Fixtures\Annotation\Template',
            'Route'    => 'Doctrine\AnnotationsTests\Fixtures\Annotation\Route'
        ];

        $context = new Context($class, $namespaces, $imports, $ignoredNames);

        $this->assertEquals($ignoredNames, $context->getIgnoredNames());
        $this->assertEquals($namespaces, $context->getNamespaces());
        $this->assertEquals($class, $context->getReflection());
        $this->assertEquals($imports, $context->getImports());

        $this->assertTrue($context->isIgnoredName('todo'));
        $this->assertFalse($context->isIgnoredName('Foo'));
    }

    public function testContextTarget()
    {
        $namespaces = ['Doctrine\AnnotationsTests\Fixtures'];
        $class      = new \ReflectionClass('Doctrine\AnnotationsTests\Fixtures\Controller');
        $property   = new \ReflectionProperty('Doctrine\AnnotationsTests\Fixtures\Controller', 'service');
        $method     = new \ReflectionMethod('Doctrine\AnnotationsTests\Fixtures\Controller', 'indexAction');
        $function   = new \ReflectionFunction('Doctrine\AnnotationsTests\Fixtures\annotation_enum_function');

        $classContext    = new Context($class, $namespaces);
        $methodsContext  = new Context($method, $namespaces);
        $propertyContext = new Context($property, $namespaces);
        $functionContext = new Context($function, $namespaces);

        $this->assertEquals(Target::TARGET_CLASS, $classContext->getTarget());
        $this->assertEquals(Target::TARGET_PROPERTY, $propertyContext->getTarget());
        $this->assertEquals(Target::TARGET_METHOD, $methodsContext->getTarget());
        $this->assertEquals(Target::TARGET_FUNCTION, $functionContext->getTarget());
    }

    public function testContextDescription()
    {
        $namespaces = ['Doctrine\AnnotationsTests\Fixtures'];
        $class      = new \ReflectionClass('Doctrine\AnnotationsTests\Fixtures\Controller');
        $property   = new \ReflectionProperty('Doctrine\AnnotationsTests\Fixtures\Controller', 'service');
        $method     = new \ReflectionMethod('Doctrine\AnnotationsTests\Fixtures\Controller', 'indexAction');
        $function   = new \ReflectionFunction('Doctrine\AnnotationsTests\Fixtures\annotation_enum_function');

        $classContext    = new Context($class, $namespaces);
        $methodsContext  = new Context($method, $namespaces);
        $propertyContext = new Context($property, $namespaces);
        $functionContext = new Context($function, $namespaces);

        $this->assertEquals('class Doctrine\AnnotationsTests\Fixtures\Controller', $classContext->getDescription());
        $this->assertEquals('property Doctrine\AnnotationsTests\Fixtures\Controller::$service', $propertyContext->getDescription());
        $this->assertEquals('method Doctrine\AnnotationsTests\Fixtures\Controller::indexAction()', $methodsContext->getDescription());
        $this->assertEquals('function Doctrine\AnnotationsTests\Fixtures\annotation_enum_function', $functionContext->getDescription());
    }
}
