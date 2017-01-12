<?php

namespace Doctrine\AnnotationsTests;

use Doctrine\Annotations\AnnotationReader;

class AnnotationReaderTest extends AbstractReaderTest
{
    /**
     * @return AnnotationReader
     */
    protected function getReader()
    {
        return new AnnotationReader($this->config);
    }

    public function testMethodAnnotationFromTrait()
    {
        $reader = $this->getReader();
        $ref    = new \ReflectionClass('Doctrine\AnnotationsTests\Fixtures\ClassUsesTrait');

        $annotations = $reader->getMethodAnnotations($ref->getMethod('someMethod'));
        $this->assertInstanceOf('Doctrine\AnnotationsTests\Bar\Autoload', $annotations[0]);

        $annotations = $reader->getMethodAnnotations($ref->getMethod('traitMethod'));
        $this->assertInstanceOf('Doctrine\AnnotationsTests\Fixtures\Annotation\Autoload', $annotations[0]);
    }

    public function testMethodAnnotationFromOverwrittenTrait()
    {
        $reader = $this->getReader();
        $ref = new \ReflectionClass('Doctrine\AnnotationsTests\Fixtures\ClassOverwritesTrait');

        $annotations = $reader->getMethodAnnotations($ref->getMethod('traitMethod'));
        $this->assertInstanceOf('Doctrine\AnnotationsTests\Bar2\Autoload', $annotations[0]);
    }

    public function testPropertyAnnotationFromTrait()
    {
        $reader = $this->getReader();
        $ref = new \ReflectionClass('Doctrine\AnnotationsTests\Fixtures\ClassUsesTrait');

        $annotations = $reader->getPropertyAnnotations($ref->getProperty('aProperty'));
        $this->assertInstanceOf('Doctrine\AnnotationsTests\Bar\Autoload', $annotations[0]);

        $annotations = $reader->getPropertyAnnotations($ref->getProperty('traitProperty'));
        $this->assertInstanceOf('Doctrine\AnnotationsTests\Fixtures\Annotation\Autoload', $annotations[0]);
    }

    public function testFunctionAnnotation()
    {
        $reader = $this->getReader();
        $ref = new \ReflectionFunction('Doctrine\AnnotationsTests\Fixtures\annotation_autoload_function');

        $annotations = $reader->getFunctionAnnotations($ref);
        $this->assertInstanceOf('Doctrine\AnnotationsTests\Fixtures\Annotation\Autoload', $annotations[0]);

        $this->assertNull($reader->getFunctionAnnotation($ref, 'foo'));
    }
}