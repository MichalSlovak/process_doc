<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Sorts;

use Sportisimo\Core\Database\Lists\Sortation\ASortation;
use Sportisimo\Core\Database\Lists\Sortation\Sort;

/**
 * Class ProcessSortation
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Sorts
 */
class ProcessSortation extends ASortation
{
  /**
   * Nazev funkce pro razeni podle poradi
   * @var string
   */
  public const SORT_BY_SORT_ORDER = 'sortBySortOrder';

  /**
   * Funkce razeni podle poradi v entite
   * @param string $sortOrder
   * @return ProcessSortation
   */
  public function sortBySortOrder(string $sortOrder = Sort::ASC): self
  {
    $this->sorts[] = new Sort(self::SORT_BY_SORT_ORDER, $sortOrder);
    return $this;
  } // sortBySortOrder()

} // ProcessSortation
