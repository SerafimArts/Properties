<?php
/**
 * This file is part of Properties package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Properties\Commands;

use Railt\Compiler\Compiler;
use Railt\Io\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CompileGrammarCommand
 */
class CompileGrammarCommand extends Command
{
    /**
     * @var string
     */
    private const PATH_TO_GRAMMAR = __DIR__ . '/../../resources/grammar.pp2';

    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('compile');
        $this->setDescription('Compile grammar from sources');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \Throwable
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $compiler = Compiler::load(File::fromPathname(self::PATH_TO_GRAMMAR));

        $compiler->setNamespace('Serafim\\Properties\\Parser')
            ->setClassName('BaseParser')
            ->saveTo(__DIR__ . '/../Parser');
    }
}
