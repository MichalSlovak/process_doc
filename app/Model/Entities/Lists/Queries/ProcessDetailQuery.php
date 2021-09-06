<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Queries;

use Sportisimo\Core\Database\Lists\Filtration\AQueryObject;
use Sportisimo\Core\Database\Lists\Filtration\IntFilterValue;
use Sportisimo\Core\Database\Lists\Filtration\StringFilterValue;
use Sportisimo\Core\Database\QueryBuilder;
use Sportisimo\Core\Model\Entities\APrimaryEntity;
use Sportisimo\ProcessDoc\Model\Entities\Processes;
use Sportisimo\ProcessDoc\Model\Entities\ProcessDetail;

/**
 * Class ProcessDetailQuery
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Queries
 */
class ProcessDetailQuery extends AQueryObject
{
  /**
   * Filtruje dle id procesu.
   * @param IntFilterValue $processIdFilter
   */
  public function filterByProcessId(IntFilterValue $processIdFilter): void
  {
    $processIdFilter->setSelected();
    $this->queryBuilder->andWhere('e.' . ProcessDetail::PROCESS_ID . ' = ?', $processIdFilter->getValue());
  } // filterByApplicationId()


  // --------------------------------------- SORT -------------------------------------------------------------

  /**
   * Seradi podle sort order procesu.
   * @param string $sortOrder
   */
  public function sortBySortOrder(string $sortOrder): void
  {
    $this->queryBuilder->addOrderBy('e.'.ProcessDetail::SORT_ORDER, $sortOrder);
  } // sortBySortOrder()

  /**
   * Seradi podle sort order procesu.
   * @param string $specificationParentId
   */
  public function sortBySpecificationParentId(string $specificationParentId): void
  {
    $this->queryBuilder->addOrderBy('e.'.ProcessDetail::SPECIFICATION_PARENT_ID, $specificationParentId);
  } // sortBySortOrder()

} // ProcessDetailQuery
