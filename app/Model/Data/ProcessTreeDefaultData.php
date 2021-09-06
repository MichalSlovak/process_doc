<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Data;

use Sportisimo\Core\Nette\Model\Data\LinkData;

/**
 * Class ProcessTreeDefaultData
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Data
 */
class ProcessTreeDefaultData
{
  /**
   * Rodicovska kategorie procesu
   * @var int|null
   */
	public $applicationData;

  /**
   * INstance
   * @var array|null|ProcessData[]
   */
	public $processesData;

  /**
   * INstance
   * @var array|null|ProcessData[]
   */
  public $breadcrumbProcessesData;

	/**
	 * @var LinkData|null
	 */
	public $backlink;

} // ProcessTreeDefaultData()
