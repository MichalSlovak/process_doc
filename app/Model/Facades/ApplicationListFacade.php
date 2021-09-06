<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Facades;

use Sportisimo\Core\Database\Exceptions\DatabaseException;
use Sportisimo\Core\Exceptions\InvalidArgumentException;
use Sportisimo\Core\Exceptions\LogicException;
use Sportisimo\Core\Exceptions\NotImplementedException;
use Sportisimo\ProcessDoc\Model\Data\ApplicationListDefaultData;
use Sportisimo\ProcessDoc\Model\Services\ApplicationListService;

/**
 * Class ApplicationListFacade
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Facades
 */
class ApplicationListFacade
{
	/**
   * @var ApplicationListService
   */
	private ApplicationListService $applicationListService;

  /**
   * ProcessTreeFacade constructor.
   * @param ApplicationListService $applicationListService
   */
  public function __construct(
	ApplicationListService $applicationListService
  )
  {
    $this->applicationListService = $applicationListService;
  } // __construct()

	/**
	 * @return ApplicationListDefaultData
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NotImplementedException
	 */
	public function prepareDefaultData(): ApplicationListDefaultData
  {
    return $this->applicationListService->prepareDataforDefaultData();
  } // prepareDefaultData()

} // ApplicationListFacade
