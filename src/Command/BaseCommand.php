<?php
namespace Pbxg33k\InfoBase\Command;

use Pbxg33k\InfoBase\Service\InfoService;
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
        if (!$this->infoService) {
            $this->initializeService();
        }

        $this
            ->setName(static::COMMAND_PREFIX . ':' . static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'Enables debug mode');

        parent::configure();
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('debug')) {
            Debug::enable();
        }

        parent::initialize($input, $output);
    }

    /**
     * @param       $collection
     * @param       $columns
     * @param Table      $table
     * @return Table
     */
    protected function generateTableForSearchResult($collection, $columns, Table $table)
    {
        $table->setHeaders(array_values($columns));

        foreach ($collection as $service => $serviceResult) {
            $table = $this->generateTableRows($serviceResult, $columns, $table);
        }

        return $table;
    }

    /**
     * @param       $collection
     * @param       $columns
     * @param Table      $table
     * @return Table
     */
    protected function generateTableRows($collection, $columns, Table $table)
    {
        foreach ($collection as $item) {
            $row = [];

            foreach ($columns as $columnKey => $columnValue) {
                $row[] = $item->getPropertyValue($columnKey);
            }

            $table->addRow($row);
        }

        return $table;
    }
}