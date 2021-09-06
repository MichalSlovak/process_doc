<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Data;

use Sportisimo\Core\DataTypes\DateTime;
use Sportisimo\Core\Nette\Model\Data\LinkData;
use Sportisimo\ProcessDoc\Model\Data\ProcessData;

//TODO: DODELAT POPISKY

/**
 * Class ProcessVersionsData
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Data
 */
class ProcessVersionsData
{

	/**
	 * Rodicovska kategorie procesu
	 * @var int|null
	 */
	public $request;

	/**
	 * Rodicovska kategorie procesu
	 * @var int|null
	 */
	public $author;

	/**
	 * INstance
	 * @var DateTime
	 */
	public $dateCreated;

	/**
	 * Je tato verze defaultni ?
	 * @var boolean
	 */
	public $default;

	/**
	 * Zobrazuje se aktualni verze ?
	 * @var boolean
	 */
	public $displayed;

	/**
	 * Typy kroku
	 * @var LinkData|null
	 */
	public $processStepNextVersionLinkData;

} // ProcessDetailDefaultData
