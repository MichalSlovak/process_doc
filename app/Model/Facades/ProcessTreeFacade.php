<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Facades;

use Sportisimo\Core\Database\Exceptions\DatabaseException;
use Sportisimo\Core\Exceptions\InvalidArgumentException;
use Sportisimo\Core\Exceptions\LogicException;
use Sportisimo\Core\Exceptions\NoResultException;
use Sportisimo\Core\Exceptions\NotImplementedException;
use Sportisimo\ProcessDoc\Model\Data\ProcessTreeDefaultData;
use Sportisimo\ProcessDoc\Model\Services\ProcessTreeService;

/**
 * Class ProcessTreeFacade
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Facades
 */
class ProcessTreeFacade
{
	/**
	 * @var ProcessTreeService
	 */
	private ProcessTreeService $processTreeService;

	/**
	 * ProcessTreeFacade constructor.
	 * @param ProcessTreeService $processTreeService
	 */
	public function __construct(
	ProcessTreeService $processTreeService
	)
	{
		$this->processTreeService = $processTreeService;
	} // __construct()

	/**
	 * Funkce pro pripravu dat vychozi akce
	 * @param int|null $applicationId
	 * @param int|null $processParentId
	 * @return ProcessTreeDefaultData
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NoResultException
	 * @throws NotImplementedException
	 */
  public function prepareDefaultData(?int $applicationId, ?int $processParentId): ProcessTreeDefaultData
  {
    return $this->processTreeService->getDefaultData($applicationId, $processParentId);
  } // prepareDefaultData()

} // ProcessTreeFacade
