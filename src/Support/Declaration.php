<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 13:29
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Support;

/**
 * Class Declaration
 * @package Serafim\Properties\Support
 */
class Declaration
{
    /**
     * @var array|string[]
     */
    private $types = [];

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $access;

    /**
     * Declaration constructor.
     * @param string $types
     * @param string $field
     * @param string $accessType
     */
    public function __construct($types, $field, $accessType = Parser::ACCESS_BOTH)
    {
        $this->types = explode('|', mb_strtolower(trim($types)));
        $this->field = $field;
        $this->access = $accessType;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function typeOf($type)
    {
        if ($this->inTypesArray($type)) return true;

        $typeSynonyms = $this->getVarTypeSynonyms($type);
        foreach ($typeSynonyms as $typeSynonym) {
            if ($this->inTypesArray($typeSynonym)) return true;
        }

        foreach ($this->types as $class) {
            if (is_subclass_of($type, $class)) true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isReadable()
    {
        return $this->access === Parser::ACCESS_READ || $this->access === Parser::ACCESS_BOTH;
    }

    /**
     * @return bool
     */
    public function isWritable()
    {
        return $this->access === Parser::ACCESS_WRITE || $this->access === Parser::ACCESS_BOTH;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->field;
    }

    /**
     * @param $typeName
     * @return array
     */
    private function getVarTypeSynonyms($typeName)
    {
        $typeName = strtolower($typeName);

        $types = [
            'integer' => [
                'int'
            ],
            'boolean' => [
                'bool'
            ],
            'closure' => [
                'callable'
            ],
            'double' => [
                'float'
            ],
            'string' => [],
            'NULL' => [],
            'resource' => [],
        ];

        return isset($types[$typeName]) ? $types[$typeName] : [];
    }

    /**
     * @param $type
     * @return bool
     */
    private function inTypesArray($type)
    {
        return (in_array(mb_strtolower($type), $this->types, true));
    }
}
