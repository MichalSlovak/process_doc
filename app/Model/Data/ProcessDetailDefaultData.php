<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Data;

use Sportisimo\Core\Nette\Model\Data\LinkData;

/**
 * Class ProcessDetailDefaultData
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Data
 */
class ProcessDetailDefaultData
{
	/**
	 * @var ApplicationData
	 */
	public $applicationData;

	/**
   * INstance
   * @var array|null
   */
  public $processStepsData;

  /**
   * Typy kroku
   * @var array|null
   */
  public $processStepTypesData;

  /**
   * INstance
   * @var array|null|ProcessData[]
   */
  public $breadcrumbProcessData;

	/**
	 * INstance
	 * @var array|ProcessVersionsData[]
	 */
	public $processVersions;

  /**
   * INstance
   * @var string
   */
  public $processDetailData;

	/**
	 * @var LinkData
	 */
	public LinkData $backlink;

} // ProcessDetailDefaultData
