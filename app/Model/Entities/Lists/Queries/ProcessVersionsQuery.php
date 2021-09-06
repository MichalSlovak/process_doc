<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Entities\Lists\Queries;

use Sportisimo\Core\Database\Lists\Filtration\AQueryObject;
use Sportisimo\Core\Database\Lists\Filtration\IntFilterValue;
use Sportisimo\ProcessDoc\Model\Entities\ProcessSteps;
use Sportisimo\ProcessDoc\Model\Entities\ProcessVersions;

/**
 * Class ProcessStepQuery
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities\Lists\Queries
 */
class ProcessVersionsQuery extends AQueryObject
{
	/**
	 * Filtruje procesy dle ID aplikace.
	 * @param IntFilterValue $processIdFilter
	 */
	public function filterByProcessId(IntFilterValue $processIdFilter): void
	{
		$processIdFilter->setSelected();
		$this->queryBuilder->andWhere('e.' . ProcessVersions::PROCESS_ID . ' = ?', $processIdFilter->getValue());
	} // filterByApplicationId()

} // ProcessStepQuery
