<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Facades;

use Sportisimo\ProcessDoc\Model\Data\ProcessDetailDefaultData;
use Sportisimo\ProcessDoc\Model\Services\ProcessDetailService;

/**
 * Class ProcessDetailFacade
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Facades
 */
class ProcessDetailFacade
{
	/**
	 * @var ProcessDetailService
	 */
	private ProcessDetailService $processDetailService;

	/**
	 * ProcessDetailFacade constructor.
	 * @param ProcessDetailService $processDetailService
	 */
	public function __construct(
	ProcessDetailService $processDetailService
	)
	{
		$this->processDetailService = $processDetailService;
	} // __construct()

	/**
	 * Funkce pro pripravu dat vychozi akce
	 * @param int|null $processId
	 * @param int|null $processVersionId
	 * @return ProcessDetailDefaultData
	 */
  public function prepareDefaultData(?int $processId, ?int $processVersionId): ProcessDetailDefaultData
  {
		return $this->processDetailService->getDefaultData($processId, $processVersionId);
  } // prepareDefaultData()

} // ProcessDetailFacade
