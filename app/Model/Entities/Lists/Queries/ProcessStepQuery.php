<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Queries;

use Sportisimo\Core\Database\Lists\Filtration\AQueryObject;
use Sportisimo\Core\Database\Lists\Filtration\IntFilterValue;
use Sportisimo\ProcessDoc\Model\Entities\ProcessSteps;
use Sportisimo\ProcessDoc\Model\Entities\ProcessStepTypes;

/**
 * Class ProcessStepQuery
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Queries
 */
class ProcessStepQuery extends AQueryObject
{

	/**
	 * Filtruje procesy dle ID procesu.
	 * @param IntFilterValue $processVersionId
	 */
	public function filterByProcessVersionId(IntFilterValue $processVersionId): void
	{
		$processVersionId->setSelected();
		$this->queryBuilder->andWhere('e.' . ProcessSteps::PROCESS_VERSION_ID . ' = ?', $processVersionId->getValue());
	} // filterByProcessId()

	/**
   * Filtruje procesy dle ID procesu.
   * @param IntFilterValue $processIdFilter
   */
  public function filterByProcessId(IntFilterValue $processIdFilter): void
  {
    $processIdFilter->setSelected();
    $this->queryBuilder->andWhere('e.' . ProcessSteps::PROCESS_ID . ' = ?', $processIdFilter->getValue());
  } // filterByProcessId()

  /**
   * Filtruje procesy dle id rodicovskeho kroku
   * @param IntFilterValue $parentProcessStepIdFilter
   */
  public function filterByParentProcessStepId(IntFilterValue $parentProcessStepIdFilter): void
  {
    $parentProcessStepIdFilter->setSelected();
    if($parentProcessStepIdFilter->getValue() !== null)
    {
      $this->queryBuilder->andWhere('e.' . ProcessSteps::PARENT_PROCESS_STEP_ID . ' = ?', $parentProcessStepIdFilter->getValue());
    }
    else
    {
      $this->queryBuilder->andWhere('e.' . ProcessSteps::PARENT_PROCESS_STEP_ID . ' IS NULL');
    }
  } // filterByParentProcessStepId()

  // --------------------------------------- SORT -------------------------------------------------------------

  /**
   * Seradi podle sort order procesu.
   * @param string $sortOrder
   */
  public function sortBySortOrder(string $sortOrder): void
  {
    $this->queryBuilder->addOrderBy('e.'.ProcessSteps::SORT_ORDER, $sortOrder);
  } // sortBySortOrder()

  /**
   * Seradi podle relative order procesu.
   * @param string $sortOrder
   */
  public function sortByRelativeOrder(string $sortOrder): void
  {
    $this->queryBuilder->addOrderBy('e.'.ProcessStepTypes::SORT_ORDER, $sortOrder);
  } // sortBySortOrder()

	/**
	 * Seradi podle rodice
	 * @param string $sortOrder
	 */
	public function sortBySpecificationParentId(string $sortOrder): void
	{
		$this->queryBuilder->addOrderBy('e.'.ProcessSteps::PARENT_PROCESS_STEP_ID, $sortOrder);
	} // sortBySortOrder()

} // ProcessStepQuery
