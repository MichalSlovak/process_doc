<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists;

use Sportisimo\Core\Database\Lists\AList;
use Sportisimo\Core\Database\Lists\Filtration\AQueryObject;
use Sportisimo\Core\Database\QueryBuilder;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Queries\ProcessVersionsQuery;
use Sportisimo\ProcessDoc\Model\Entities\ProcessVersions;

/**
 * Class ProcessVersionsList
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists
 */
class ProcessVersionsList extends AList
{
  /**
   * @param QueryBuilder $queryBuilder
   * @return ProcessVersionsQuery
   */
  protected function createQueryObject(QueryBuilder $queryBuilder): AQueryObject
  {
    return new ProcessVersionsQuery($queryBuilder);
  } // createQueryObject()

  /**
   * Metoda vrati nazev tabulky.
   * @return string
   */
  protected function getTable(): string
  {
    return ProcessVersions::TABLE;
  } // getTable()

} // ProcessVersionsList
