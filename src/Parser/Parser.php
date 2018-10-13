<?php
/**
 * This file is part of Properties package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Properties\Parser;

use Railt\Io\File;
use Railt\Parser\Ast\RuleInterface;

/**
 * Class Parser
 */
class Parser extends BaseParser
{
    /**
     * @param string $class
     * @return RuleInterface|null
     */
    public function parseClassDocComment(string $class): ?RuleInterface
    {
        try {
            $comment = (new \ReflectionClass($class))->getDocComment();

            return $this->parse(File::fromSources($comment, $class));
        } catch (\Throwable $e) {
            return null;
        }
    }
}
