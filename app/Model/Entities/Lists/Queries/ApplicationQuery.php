<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Queries;

use Sportisimo\Core\Database\Lists\Filtration\AQueryObject;
use Sportisimo\ProcessDoc\Model\Entities\Applications;

/**
 * Class ApplicationQuery
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Queries
 */
class ApplicationQuery extends AQueryObject
{
  // --------------------------------------- SORT -------------------------------------------------------------

  /**
   * Seradi podle nazvu aplikace
   * @param string $sortOrder
   */
  public function sortByName(string $sortOrder): void
  {
    $this->queryBuilder->addOrderBy('e.'.Applications::NAME, $sortOrder);
  } // sortByName()

} // ApplicationQuery
