<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Data;

use Sportisimo\Core\Nette\Model\Data\LinkData;

/**
 * Class ProcessData
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Data
 */
class ProcessData
{

  /**
   * ID procesu.
   * @var int
   */
  public $id;

  /**
   * TODO popisek
   * @var string|null
   */
  public $code;

  /**
   * TODO popisek
   * @var string|null
   */
  public $name;

	/**
	 * mudul umisteni
	 * @var string|null
	 */
	public $modul;

	/**
	 * mudul umisteni
	 * @var string|null
	 */
	public $presenter;

	/**
	 * mudul umisteni
	 * @var string|null
	 */
	public $url;

	/**
	 * mudul umisteni
	 * @var string|null
	 */
	public $action;

	/**
	 * mudul umisteni
	 * @var string|null
	 */
	public $target;

  /**
   * TODO popisek
   * @var LinkData|null
   */
  public $subProcessTreeLinkData;

  /**
   * TODO popisek
   * @var LinkData|null
   */
  public $processDetailLinkData;

  /**
   * Kod statusu procesu
   * @var string|null
   */
  public $processStatusCode;

} // ProcessData
