<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Queries;

use Sportisimo\Core\Database\Lists\Filtration\AQueryObject;
use Sportisimo\Core\Database\Lists\Filtration\IntFilterValue;
use Sportisimo\Core\Database\Lists\Filtration\StringFilterValue;
use Sportisimo\Core\Database\QueryBuilder;
use Sportisimo\Core\Model\Entities\APrimaryEntity;
use Sportisimo\ProcessDoc\Model\Entities\Processes;

/**
 * Class ProcessQuery
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Queries
 */
class ProcessQuery extends AQueryObject
{
  /**
   * Filtruje procesy dle ID aplikace.
   * @param IntFilterValue $applicatationIdFilter
   */
  public function filterByApplicationId(IntFilterValue $applicatationIdFilter): void
  {
    $applicatationIdFilter->setSelected();
    $this->queryBuilder->andWhere('e.' . Processes::APPLICATION_ID . ' = ?', $applicatationIdFilter->getValue());
  } // filterByApplicationId()

  /**
   * Filtruje procesy dle ID rodicovskeho procesu.
   * @param IntFilterValue $parentProcessIdFilter
   */
  public function filterByParentProcessId(IntFilterValue $parentProcessIdFilter): void
  {
    $parentProcessIdFilter->setSelected();
    if($parentProcessIdFilter->getValue() !== null)
    {
      $this->queryBuilder->andWhere('e.' . Processes::PARENT_PROCESS_ID . ' = ?', $parentProcessIdFilter->getValue());
    }
    else
    {
      $this->queryBuilder->andWhere('e.' . Processes::PARENT_PROCESS_ID . ' IS NULL');
    }
  } // filterByApplicationId()

  /**
   * Filtruje procesy dle ID procesu.
   * @param IntFilterValue $processIdFilter
   */
  public function filterByProcessId(IntFilterValue $processIdFilter): void
  {
    $processIdFilter->setSelected();
    if($processIdFilter->getValue() !== null)
    {
      $this->queryBuilder->andWhere('e.' . Processes::ID . ' = ?', $processIdFilter->getValue());
    }
    else
    {
      $this->queryBuilder->andWhere('e.' . Processes::ID . ' IS NULL');
    }
  } // filterByApplicationId()

  // --------------------------------------- SORT -------------------------------------------------------------

  /**
   * Seradi podle sort order procesu.
   * @param string $sortOrder
   */
  public function sortBySortOrder(string $sortOrder): void
  {
    $this->queryBuilder->addOrderBy('e.'.Processes::SORT_ORDER, $sortOrder);
  } // sortBySortOrder()

  /**
   * Seradi podle parentProcessId.
   * @param string $parentProcessId
   */
  public function sortByParentProcessId(string $parentProcessId): void
  {
    $this->queryBuilder->addOrderBy('e.'.Processes::PARENT_PROCESS_ID, $parentProcessId);
  } // sortBySortOrder()

} // ProcessQuery
