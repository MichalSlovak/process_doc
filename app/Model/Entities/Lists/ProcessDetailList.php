<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists;

use Sportisimo\Core\Database\Lists\AList;
use Sportisimo\Core\Database\Lists\Filtration\AQueryObject;
use Sportisimo\Core\Database\QueryBuilder;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Queries\ProcessDetailQuery;
use Sportisimo\ProcessDoc\Model\Entities\ProcessDetail;

/**
 * Class ProcessDetailList
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists
 */
class ProcessDetailList extends AList
{
  /**
   * @param QueryBuilder $queryBuilder
   * @return AQueryObject
   */
  protected function createQueryObject(QueryBuilder $queryBuilder): AQueryObject
  {
    return new ProcessDetailQuery($queryBuilder);
  } // createQueryObject()

  /**
   * Metoda vrati nazev tabulky.
   * @return string
   */
  protected function getTable(): string
  {
    return ProcessDetail::TABLE;
  } // getTable()

} // ProcessList
