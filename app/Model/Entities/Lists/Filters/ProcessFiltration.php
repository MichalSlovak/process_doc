<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Filters;

use Sportisimo\Core\Database\Lists\Filtration\AFiltration;
use Sportisimo\Core\Database\Lists\Filtration\Filter;
use Sportisimo\Core\Database\Lists\Filtration\IntFilterValue;

/**
 * Class ProcessFiltration
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Filters
 */
class ProcessFiltration extends AFiltration
{

  /**
   * Konstanta s nazvem funkce
   * @var string
   */
  private const FILTER_BY_APPLICATION_ID = 'filterByApplicationId';

  /**
   * Konstanta s nazvem funkce pro filtrovani podle id rodice
   * @var string
   */
  private const FILTER_BY_PARENT_PROCESS_ID = 'filterByParentProcessId';

  /**
   * Konstanta s nazvem funkce pro filtrovani podle id zaznamu
   * @var string
   */
  private const FILTER_BY_ID = 'filterByProcessId';

  /**
   * Filtrovani podle id aplikace
   * @param int $applicationId
   * @return ProcessFiltration
   */
  public function filterByApplicationId(int $applicationId): self
  {
    $this->filters[] = new Filter(self::FILTER_BY_APPLICATION_ID, [new IntFilterValue($applicationId)]);
    return $this;
  } // filterByApplicationId()

  /**
   * Filtrovani podle id rodice
   * @param int|null $parentProcessId
   * @return ProcessFiltration
   */
  public function filterByParentProcessId(?int $parentProcessId): self
  {
    $this->filters[] = new Filter(self::FILTER_BY_PARENT_PROCESS_ID, [new IntFilterValue($parentProcessId)]);
    return $this;
  } // filterByParentProcessId

  /**
   * Filtrovani podle id zaznamu
   * @param int|null $processId
   * @return ProcessFiltration
   * TODO: OD chtel abych tento filtr smazal ale pouzivam ho na filtraci dle parentid ktere zde pouzivam jako id zaznamu tak pak dostanu childreny dane kategorie
   */
  public function filterByProcessId(?int $processId): self
  {
    $this->filters[] = new Filter(self::FILTER_BY_ID, [new IntFilterValue($processId)]);
    return $this;
  } // filterByProcessId

} // ProcessFiltration
