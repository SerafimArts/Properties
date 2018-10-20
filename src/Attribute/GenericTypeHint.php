<?php
/**
 * This file is part of Properties package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Properties\Attribute;

/**
 * Class GenericTypeHint
 */
class GenericTypeHint extends TypeHint
{
    /**
     * @var null|Matchable
     */
    protected $key;

    /**
     * @var null|Matchable
     */
    protected $value;

    /**
     * GenericTypeHint constructor.
     * @param string $name
     * @param Matchable|null $key
     * @param Matchable|null $value
     */
    public function __construct(string $name, Matchable $value = null, Matchable $key = null)
    {
        $this->key = $key;
        $this->value = $value;

        parent::__construct($name);
    }

    /**
     * @param iterable|mixed $values
     * @return bool
     */
    public function match($values): bool
    {
        if (! \is_iterable($values)) {
            return false;
        }

        if (! parent::match($values)) {
            return false;
        }

        if ($this->value || $this->key) {
            foreach ($values as $key => $value) {
                if ($this->key && ! $this->key->match($key)) {
                    return false;
                }

                if ($this->value && ! $this->value->match($value)) {
                    return false;
                }
            }
        }

        return true;
    }
}
