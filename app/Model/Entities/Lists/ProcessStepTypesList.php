<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists;

use Sportisimo\Core\Database\Lists\AList;
use Sportisimo\Core\Database\Lists\Filtration\AQueryObject;
use Sportisimo\Core\Database\QueryBuilder;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Queries\ProcessQuery;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Queries\ProcessStepQuery;
use Sportisimo\ProcessDoc\Model\Entities\Processes;
use Sportisimo\ProcessDoc\Model\Entities\ProcessSteps;
use Sportisimo\ProcessDoc\Model\Entities\ProcessStepTypes;

/**
 * Class ProcessStepTypesList
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists
 */
class ProcessStepTypesList extends AList
{
  /**
   * @param QueryBuilder $queryBuilder
   * @return AQueryObject
   */
  protected function createQueryObject(QueryBuilder $queryBuilder): AQueryObject
  {
    return new ProcessStepQuery($queryBuilder);
  } // createQueryObject()

  /**
   * Metoda vrati nazev tabulky.
   * @return string
   */
  protected function getTable(): string
  {
    return ProcessStepTypes::TABLE;
  } // getTable()

} // ProcessStepList
