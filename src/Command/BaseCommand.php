<?php
namespace Pbxg33k\InfoBase\Command;

use Pbxg33k\InfoBase\InfoService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Debug\Debug;

abstract class BaseCommand extends Command
{
    const COMMAND_PREFIX = 'info-base';

    const COMMAND_NAME = 'undefined';

    const COMMAND_DESCRIPTION = 'Description not set';

    /**
     * @var InfoService
     */
    protected $infoService;

    /**
     * Initializes the service
     *
     * @return void
     */
    abstract protected function initializeService();

    protected function configure()
    {
        // @codeCoverageIgnoreStart
        if (!$this->infoService) {
            $this->initializeService();
        }

        $this
            ->setName(static::COMMAND_PREFIX . ':' . static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'Enables debug mode');

        parent::configure();
        // @codeCoverageIgnoreEnd
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        // @codeCoverageIgnoreStart
        if ($input->getOption('debug')) {
            Debug::enable();
        }

        parent::initialize($input, $output);
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param       $collection
     * @param       $columns
     * @param Table      $table
     * @return Table
     */
    protected function generateTableForSearchResult($collection, $columns, Table $table)
    {
        // @codeCoverageIgnoreStart
        $table->setHeaders(array_values($columns));

        foreach ($collection as $service => $serviceResult) {
            $table = $this->generateTableRows($serviceResult, $columns, $table);
        }

        return $table;
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param       $collection
     * @param       $columns
     * @param Table      $table
     * @return Table
     */
    protected function generateTableRows($collection, $columns, Table $table)
    {
        // @codeCoverageIgnoreStart
        foreach ($collection as $item) {
            $row = [];

            foreach ($columns as $columnKey => $columnValue) {
                $row[] = $item->getPropertyValue($columnKey);
            }

            $table->addRow($row);
        }

        return $table;
        // @codeCoverageIgnoreEnd
    }
}