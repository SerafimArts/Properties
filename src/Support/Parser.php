<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 13:26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Support;

/**
 * Class Parser
 * @package Serafim\Properties\Support
 */
class Parser
{
    const ACCESS_READ   = 'read';
    const ACCESS_WRITE  = 'write';
    const ACCESS_BOTH   = '*';

    /**
     * @var array
     */
    protected static $declarations = [
        'property-read'  => self::ACCESS_READ,
        'property-write' => self::ACCESS_WRITE,
        'property'       => self::ACCESS_BOTH,
    ];

    /**
     * @var array|Declaration[]
     */
    private $properties = [];

    /**
     * Parser constructor.
     * @param \ReflectionClass|string|object $class
     */
    public function __construct($class)
    {
        if (is_string($class) || !($class instanceof \ReflectionClass)) {
            $class = new \ReflectionClass($class);
        }


        $declarations = [];
        while ($class) {
            $declarations[] = $this->parse($class->getDocComment());
            $class = $class->getParentClass();
        }

        $this->properties = (array)call_user_func_array('array_merge', $declarations);
    }

    /**
     * @param string $docBlock
     * @return array
     */
    private function parse($docBlock)
    {
        $result = [];

        foreach (static::$declarations as $declaration => $accessing) {
            $matches = $this->getMatches($declaration, $docBlock);

            foreach ($matches as $match) {
                list($declaration, $type, $field) = $match;
                $result[$field] = new Declaration($type, $field, $accessing);
            }
        }

        return $result;
    }

    /**
     * @param string $accessType
     * @param string $docBlock
     * @return array
     */
    private function getMatches($accessType, $docBlock)
    {
        preg_match_all($this->getPattern($accessType), $docBlock, $matches, PREG_SET_ORDER);

        return (array)$matches;
    }

    /**
     * @param string $accessType
     * @return string
     */
    private function getPattern($accessType)
    {
        return sprintf('/@%s\s(?:(.*?)\s*)\$([a-z_]+[0-9a-z_\x7f-\xff]*)/isu', preg_quote($accessType));
    }

    /**
     * @param string $field
     * @return bool
     */
    public function has($field)
    {
        return array_key_exists($field, $this->properties);
    }

    /**
     * @param string $field
     * @return Declaration
     */
    public function get($field)
    {
        return $this->properties[$field];
    }

    /**
     * @param string $field
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isReadable($field)
    {
        if (!$this->has($field)) {
            throw new \InvalidArgumentException(sprintf('Can not find field %s declaration', $field));
        }

        return $this->get($field)->isReadable();
    }

    /**
     * @param string $field
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isWritable($field)
    {
        if (!$this->has($field)) {
            throw new \InvalidArgumentException(sprintf('Can not find field %s declaration', $field));
        }

        return $this->get($field)->isWritable();
    }
}
