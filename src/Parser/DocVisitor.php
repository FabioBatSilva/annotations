<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

use Doctrine\Annotations\Exception\ClassNotFoundException;
use Doctrine\Annotations\Reference;
use Doctrine\Annotations\Resolver;
use Doctrine\Annotations\Builder;
use Doctrine\Annotations\Context;

/**
 * A visitor for docblock annotations.
 *
 * @author Fabio B. Silva <fabio.bat.silva@gmail.com>
 */
class DocVisitor extends BaseVisitor
{
    /**
     * @var \Doctrine\Annotations\Resolver
     */
    protected $resolver;

    /**
     * @var \Doctrine\Annotations\Builder
     */
    private $builder;

    /**
     * @var \Doctrine\Annotations\Context
     */
    private $context;

    /**
     * Whether annotations that have not been imported should be ignored.
     *
     * @var bool
     */
    private $ignoreNotImported;

    /**
     * Constructor
     *
     * @param \Doctrine\Annotations\Context  $context
     * @param \Doctrine\Annotations\Builder  $builder
     * @param \Doctrine\Annotations\Resolver $resolver
     * @param boolean                        $ignoreNotImported
     */
    public function __construct(Context $context, Builder $builder, Resolver $resolver, bool $ignoreNotImported = false)
    {
        $this->builder           = $builder;
        $this->context           = $context;
        $this->resolver          = $resolver;
        $this->ignoreNotImported = $ignoreNotImported;
    }

    /**
     * {@inheritdoc}
     */
    protected function resolveClass(string $class) : string
    {
        return $this->resolver->resolve($this->context, $class);
    }

    /**
     * {@inheritdoc}
     */
    protected function createAnnotation(Reference $reference)
    {
        if ($this->context->isIgnoredName($reference->name)) {
            return null;
        }

        if ( ! $this->ignoreNotImported) {
            return $this->builder->create($this->context, $reference);
        }

        try {
            return $this->builder->create($this->context, $reference);
        } catch (ClassNotFoundException $e) {
            return null;
        }
    }
}
