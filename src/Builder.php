<?php
/**
 * This file is part of Properties package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Properties;

use Railt\Parser\Ast\RuleInterface;
use Serafim\Properties\Attribute\AttributeInterface;
use Serafim\Properties\Parser\Parser;
use Serafim\Properties\Reducers;
use Serafim\Properties\Reducers\ReducerInterface;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var string[]
     */
    private const DEFAULT_REDUCERS = [
        Reducers\DocBlockReducer::class,
        Reducers\DocTitleReducer::class,
        Reducers\VariableReducer::class,
        Reducers\TypeHintReducer::class,
        Reducers\TypeNameReducer::class,
        Reducers\TypeHint\ScalarHintReducer::class,
        Reducers\TypeHint\ArrayHintReducer::class,
        Reducers\TypeHint\OrHintReducer::class,
        Reducers\TypeHint\AndHintReducer::class,
    ];

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var array|ReducerInterface[]
     */
    private $reducers = [];

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        $this->parser = new Parser();

        $this->bootReducers();
    }

    /**
     * @return void
     */
    private function bootReducers(): void
    {
        foreach (self::DEFAULT_REDUCERS as $reducer) {
            $this->reducers[] = new $reducer;
        }
    }

    /**
     * @param string $class
     * @return iterable|AttributeInterface[]
     * @throws \RuntimeException
     */
    public function buildClass(string $class): iterable
    {
        $ast = $this->parser->parseClassDocComment($class);

        if ($ast instanceof RuleInterface) {
            foreach ($ast->getChildren() as $child) {
                yield $this->build($child);
            }
        }
    }

    /**
     * @param RuleInterface $rule
     * @return mixed
     * @throws \RuntimeException
     */
    private function build(RuleInterface $rule)
    {
        foreach ($this->reducers as $reducer) {
            if ($reducer->match($rule)) {
                $result = $reducer->reduce($rule);

                return $result instanceof \Generator ? $this->coroutine($result) : $result;
            }
        }

        $error = \sprintf('Unrecognized rule %s', $rule->getName());
        throw new \RuntimeException($error);
    }

    /**
     * @param \Generator $coroutine
     * @return mixed
     * @throws \RuntimeException
     */
    private function coroutine(\Generator $coroutine)
    {
        while ($coroutine->valid()) {
            $value = $coroutine->current();

            if ($value instanceof RuleInterface) {
                $value = $this->build($value);
            }

            $coroutine->send($value);
        }

        return $coroutine->getReturn();
    }
}
