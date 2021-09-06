<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Filters;

use Sportisimo\Core\Database\Lists\Filtration\AFiltration;
use Sportisimo\Core\Database\Lists\Filtration\Filter;
use Sportisimo\Core\Database\Lists\Filtration\IntFilterValue;

/**
 * Class ProcessStepFiltration
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Filters
 */
class ProcessStepFiltration extends AFiltration
{
  /**
   * Konstanta s nazvem funkce pro filtraci dle procesu
   * @var string
   */
  private const FILTER_BY_PROCESS_ID = 'filterByProcessId';

  /**
   * Konstanta s id rodicovskeho kroku pro filtraci dle nadrazeneho kroku
   * @var string
   */
  private const FILTER_BY_PARENT_PROCESS_STEP_ID = 'filterByParentProcessStepId';

	/**
	 * Konstanta s nazvem funkce
	 * @var string
	 */
	private const FILTER_BY_PROCESS_VERSION_ID = 'filterByProcessVersionId';

	/**
	 * Filtrovani podle id verze
	 * @param int $processVersionId
	 * @return ProcessStepFiltration
	 */
	public function filterByProcessVersionId(int $processVersionId): self
	{
		$this->filters[] = new Filter(self::FILTER_BY_PROCESS_VERSION_ID, [new IntFilterValue($processVersionId)]);
		return $this;
	} // filterByApplicationId()

  /**
   * Filtrovani podle id procesu
   * @param int $processId
   * @return ProcessStepFiltration
   */
  public function filterByProcessId(int $processId): self
  {
    $this->filters[] = new Filter(self::FILTER_BY_PROCESS_ID, [new IntFilterValue($processId)]);
    return $this;
  } // filterByProcessId()

  /**
   * Filtrovani podle id kroku ktery je rodic vnoreneho kroku, pokud je nejvyssi je null
   * @param int|null $parentProcessStepId
   * @return ProcessStepFiltration
   */
  public function filterByParentProcessStepId(?int $parentProcessStepId): self
  {
    $this->filters[] = new Filter(self::FILTER_BY_PARENT_PROCESS_STEP_ID, [new IntFilterValue($parentProcessStepId)]);
    return $this;
  } // filterByParentProcessStepId()

} // ProcessStepFiltration
