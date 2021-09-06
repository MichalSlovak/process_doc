<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Queries;

use Sportisimo\Core\Database\Lists\Filtration\AQueryObject;
use Sportisimo\Core\Database\Lists\Filtration\IntFilterValue;
use Sportisimo\ProcessDoc\Model\Entities\ProcessStepScreenshots;

/**
 * Class ProcessStepScreenshotQuery
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Queries
 */
class ProcessStepScreenshotQuery extends AQueryObject
{
  /**
   * Filtruje procesy dle ID nadrazeneho kroku pokud neni je hlavni a obsahuje null.
   * @param IntFilterValue $processStepIdFilter
   */
  public function filterByProcessStepId(IntFilterValue $processStepIdFilter): void
  {
    $processStepIdFilter->setSelected();
    $this->queryBuilder->andWhere('e.' . ProcessStepScreenshots::PROCESS_STEP_ID . ' = ?', $processStepIdFilter->getValue());
  } // filterByProcessStepId()

} // ProcessStepScreenshotQuery
