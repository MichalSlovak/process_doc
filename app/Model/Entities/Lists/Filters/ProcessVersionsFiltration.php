<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Filters;

use Sportisimo\Core\Database\Lists\Filtration\AFiltration;
use Sportisimo\Core\Database\Lists\Filtration\Filter;
use Sportisimo\Core\Database\Lists\Filtration\IntFilterValue;

/**
 * Class ProcessVersionsFiltration
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Filters
 */
class ProcessVersionsFiltration extends AFiltration
{
  /**
   * Konstanta urcuje krok procesu, kteremu printscreen odpovida
   * @var string
   */
  private const FILTER_BY_PROCESS_ID = 'filterByProcessId';

  /**
   * Filtrovani podle id procesu
   * @param int $processId
   * @return ProcessVersionsFiltration
   */
  public function filterByProcessId(int $processId): self
  {
    $this->filters[] = new Filter(self::FILTER_BY_PROCESS_ID, [new IntFilterValue($processId)]);
    return $this;
  } // filterByProcessStepId()

} // ProcessVersionsFiltration
