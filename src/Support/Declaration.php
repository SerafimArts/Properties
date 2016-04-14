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
        return in_array(mb_strtolower($type), $this->types, true);
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
}
