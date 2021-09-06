<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Filters;

use Sportisimo\Core\Database\Lists\Filtration\AFiltration;
use Sportisimo\Core\Database\Lists\Filtration\Filter;
use Sportisimo\Core\Database\Lists\Filtration\IntFilterValue;

/**
 * Class ProcessStepScreenshotFiltration
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Filters
 */
class ProcessStepScreenshotFiltration extends AFiltration
{
  /**
   * Konstanta urcuje krok procesu, kteremu printscreen odpovida
   * @var string
   */
  private const FILTER_BY_PROCESS_STEP_ID = 'filterByProcessStepId';

  /**
   * Filtrovani podle id kroku procesu
   * @param int $processStepId
   * @return ProcessStepScreenshotFiltration
   */
  public function filterByProcessStepId(int $processStepId): self
  {
    $this->filters[] = new Filter(self::FILTER_BY_PROCESS_STEP_ID, [new IntFilterValue($processStepId)]);
    return $this;
  } // filterByProcessStepId()

} // ProcessStepScreenshotFiltration
