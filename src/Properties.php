<?php
/**
 * This file is part of Properties package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Properties;

use Railt\Io\Exception\ExternalFileException;
use Railt\Io\File;
use Serafim\Properties\Exception\AccessDeniedException;
use Serafim\Properties\Exception\NotReadableException;
use Serafim\Properties\Exception\NotWritableException;

/**
 * Trait Properties
 */
trait Properties
{
    /**
     * @param mixed $name
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __get($name)
    {
        \assert(\is_scalar($name));

        $attribute = Bootstrap::getInstance()->getAttribute(static::class, (string)$name);

        if ($attribute && $attribute->isReadable()) {
            return $attribute->getValueFrom($this);
        }

        $error = \sprintf('Property %s::$%s not readable', __CLASS__, $name);
        throw $this->propertyAccessException(NotReadableException::class, $error);
    }

    /**
     * @param mixed $name
     * @param mixed $value
     * @return mixed
     * @throws AccessDeniedException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __set($name, $value)
    {
        \assert(\is_scalar($name));

        $attribute = Bootstrap::getInstance()->getAttribute(static::class, (string)$name);

        if ($attribute && $attribute->isWritable()) {
            if (! $attribute->match($value)) {
                $error = 'New value of %s::$%s is not compatible with type hint definition';
                $error = \sprintf($error, __CLASS__, $name);
                throw $this->propertyAccessException(NotWritableException::class, $error);
            }

            return $attribute->setValueTo($this, $value);
        }

        $error = \sprintf('Property %s::$%s not writable', __CLASS__, $name);
        throw $this->propertyAccessException(NotWritableException::class, $error);
    }

    /**
     * @param string|ExternalFileException $exception
     * @param string $message
     * @return AccessDeniedException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function propertyAccessException(string $exception, string $message): AccessDeniedException
    {
        [$file, $line] = Bootstrap::getInstance()->getInvocationPosition();

        return (new $exception($message))->throwsIn(File::fromPathname($file), $line, 0);
    }

    /**
     * @param mixed $name
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __isset($name)
    {
        \assert(\is_scalar($name));

        return Bootstrap::getInstance()->hasAttribute(static::class, $name);
    }

    /**
     * @param mixed $name
     * @return bool
     * @throws AccessDeniedException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __unset($name)
    {
        \assert(\is_scalar($name));

        $attribute = Bootstrap::getInstance()->getAttribute(static::class, (string)$name);

        if ($attribute && $attribute->isWritable()) {
            unset($this->$name);

            return true;
        }

        $error = \sprintf('Can not remove not writable property %s::$%s', __CLASS__, $name);
        throw $this->propertyAccessException(NotWritableException::class, $error);
    }
}
