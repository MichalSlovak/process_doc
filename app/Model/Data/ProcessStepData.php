<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Data;

use Sportisimo\Core\Nette\Model\Data\LinkData;

/**
 * Class ProcessStepData
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Data
 */
class ProcessStepData
{
  /**
   * Rodicovska kategorie procesu
   * @var int|null
   */
  public $name;

  /**
   * TODO popisek
   * @var LinkData|null
   */
  public $showProcessStepDetailLinkData;

  /**
   * TODO popisek
   * @var int|null
   */
  public $stepType;

  /**
   * TODO popisek
   * @var int|null
   */
  public $stepSubtype;

	/**
	 * TODO popisek
	 * @var int|null
	 */
	public $stepTypeName;

  /**
   * TODO popisek
   * @var int|null
   */
  public $children;

  /**
   * TODO popisek
   * @var array|null
   */
  public $processStepScreenshotData;

	/**
	 * TODO popisek
	 * @var array|null|ProcessStepScreenshotsData
	 */
	public $sortOrder;

	/**
	 * TODO popisek
	 * @var array|null
	 */
	public $processStepTypesData;

	/**
	 * Uzivatelska specifikace
	 * @var string|null
	 */
	public $userDescription;

	/**
	 * Uzivatelska specifikace
	 * @var string|null
	 */
	public $technicalDescription;

	/**
	 * namety
	 * @var string|null
	 */
	public $idea;
	/**
	 * @var null|string
	 */
	public $technicalDescriptionOrigin;

  /**
   * @var LinkData
   */
	public $nextProcessLinkData;

  /**
   * @var int
   */
	public $nextProcessId;

  /**
   * @var int
   */
	public $nextProcessStepId;

  /**
   * @var int
   */
	public $id;

  /**
   * @var string
   */
	public $nextProcessStepName;

  /**
   * @var string
   */
	public $nextProcessName;

} // ProcessStepData
