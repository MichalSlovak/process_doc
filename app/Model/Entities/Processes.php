<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities;

use Sportisimo\Core\DataTypes\DateTime;
use Sportisimo\Core\Model\Entities\APrimaryEntity;
use Sportisimo\Core\Utils\TypeUtils;

/**
 * Class Processes
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities
 */
class Processes extends APrimaryEntity
{
  public const TABLE = 'sm_processes';

  public const CURRENT_PROCESS_VERSION_ID = 'current_process_version_id';
  public const APPLICATION_ID = 'application_id';
  public const PROCESS_STATUS_ID = 'process_status_id';
  public const PARENT_PROCESS_ID = 'parent_process_id';
  public const CODE = 'code';
  public const NAME = 'name';
  public const TITLE = 'title';
  public const TARGET = 'target';
  public const URL = 'url';
  public const MODUL = 'modul';
  public const PRESENTER = 'presenter';
  public const ACTION = 'action';
  public const SORT_ORDER = 'sort_order';
  public const C2F = 'c2f';
  public const C2F_PARAM = 'c2f_param';
  public const DATE_CREATED = 'date_created';
  public const DATE_LAST_MODIFIED = 'date_last_modified';

	/**
	 * ID aktualni verze procesu.
	 * @var int
	 */
	protected $currentProcessVersionId;

  /**
   * ID aplikace.
   * @var int
   */
  protected $applicationId;

  /**
   * ID statusu procesu.
   * @var int|null
   */
  protected $processStatusId;

  /**
   * ID nadrazene kategorie
   * @var int|null
   */
  protected $processParentId;

  /**
   * kod procesu / kod vnoreni
   * @var string|null
   */
  protected $code;

  /**
   * nazev procesu
   * @var string|null
   */
  protected $name;

  /**
   * podtitul nazvu procesu - sekundarni nazev
   * @var string|null
   */
  protected $title;

  /**
   * textovy popis cile daneho procesu
   * @var string|null
   */
  protected $target;

  /**
   * URL procesu
   * @var string|null
   */
  protected $url;

  /**
   * Modul ve kterem se proces nachazi.
   * @var string|null
   */
  protected $modul;

  /**
   * presenter procesu
   * @var string|null
   */
  protected $presenter;

  /**
   * akce procesu
   * @var string|null
   */
  protected $action;

  /**
   * poradi procesu mezi potomky spolecneho rodice
   * @var float|null
   */
  protected $nodeOrder;

  /**
   * kod pro model v aplikaci code2flow.com
   * @var string|null
   */
  protected $c2f;

  /**
   * parametr pro model v aplikaci code2flow.com.
   * aplikace otevre read-only model, ktery je mozno naklonovat a nasledne upravovat
   * @var string|null
   */
  protected $c2fParam;

  /**
   * datum vytvoreni zaznamu
   * @var DateTime
   */
  protected $dateCreated;

  /**
   * datum upravy zaznamu
   * @var DateTime
   */
  protected $dateLastModified;

  /**
   * @inheritDoc
   */
  protected function mapping(array $row): void
  {
    $this->id = (int)$row[self::ID];
    $this->currentProcessVersionId = (int)$row[self::CURRENT_PROCESS_VERSION_ID];
    $this->applicationId = TypeUtils::convertToInt($row[self::APPLICATION_ID]);
    $this->processStatusId = TypeUtils::convertToInt($row[self::PROCESS_STATUS_ID]);
    $this->processParentId = TypeUtils::convertToInt($row[self::PARENT_PROCESS_ID]);
    $this->code = (string)$row[self::CODE];
    $this->name = (string)$row[self::NAME];
    $this->title = (string)$row[self::TITLE];
    $this->url = (string)$row[self::URL];
    $this->modul = (string)$row[self::MODUL];
    $this->presenter = (string)$row[self::PRESENTER];
    $this->action = (string)$row[self::ACTION];
    $this->target = (string)$row[self::TARGET];
    $this->nodeOrder = TypeUtils::convertToFloat($row[self::SORT_ORDER]);
    $this->c2f = (string)$row[self::C2F];
    $this->c2fParam = (string)$row[self::C2F_PARAM];
    $this->dateCreated = TypeUtils::convertToDateTime($row[self::DATE_CREATED]);
    $this->dateLastModified = TypeUtils::convertToDateTime($row[self::DATE_LAST_MODIFIED]);
  } // mapping()

	/**
	 * Ziskani id aktualni verze.
	 * @return int
	 */
	public function getCurrentProcessVersionId(): int
	{
		return $this->currentProcessVersionId;
	} // getCurrentProcessVersionId()

  /**
   * Ziskani id aplikace.
   * @return int
   */
  public function getApplicationId(): int
  {
    return $this->applicationId;
  } // getApplicationId()

  /**
   * Ziskani ID statusu procesu.
   * @return int|null Vraci id statusu nebo null.
   */
  public function getProcessStatusId(): ?int
  {
    return $this->processStatusId;
  } // getProcessStatusId()

  /**
   * Ziskani ID nadrazene kategorie.
   * @return int|null Vraci id rodice nebo null.
   */
  public function getProcessParentId(): ?int
  {
    return $this->processParentId;
  } // getProcessParentId()

  /**
   * Ziskani kodu slozeneho ze stromove architektury.
   * @return string|null
   */
  public function getCode(): ?string
  {
    return $this->code;
  } // getCode()

  /**
   * Nazev procesu.
   * @return string|null Vraci nazev procesu nebo null.
   */
  public function getName(): ?string
  {
    return $this->name;
  } // getName()

  /**
   * Podtitul nazvu procesu.
   * @return string|null Vraci sub-nazev procesu nebo null.
   */
  public function getTitle(): ?string
  {
    return $this->title;
  } // getTitle()

  /**
   * Textovy popis cile daneho procesu.
   * @return string|null Vrací popis nebo null.
   */
  public function getTarget(): ?string
  {
    return $this->target;
  } // getTarget()

  /**
   * URL procesu.
   * @return string|null Vraci url nebo null.
   */
  public function getUrl(): ?string
  {
    return $this->url;
  } // getUrl()

  /**
   * Modul ve kterem se proces nachazi.
   * @return string|null
   */
  public function getModul(): ?string
  {
    return $this->modul;
  } // getModul()

  /**
   * Presenter procesu
   * @return string|null Vrací aktuální precenter per proces nebo null
   */
  public function getPresenter(): ?string
  {
    return $this->presenter;
  } // getPresenter()

  /**
   * Akce daneho procesu
   * @return string|null Vraci string nebo null
   */
  public function getAction(): ?string
  {
    return $this->action;
  } // getAction()

  /**
   * Urcuje poradi procesu mezi potomky spolecneho rodice
   * @return float|null Vrací float typu 1.0.2 nebo null
   */
  public function getNodeOrder(): ?float
  {
    return $this->nodeOrder;
  } // getNodeOrder()

  /**
   * kod pro model v aplikaci code2flow.com
   * @return string|null Vraci C2f kod nebo null
   */
  public function getC2f(): ?string
  {
    return $this->c2f;
  } // getC2f()

  /**
   * Parametr pro model v aplikaci code2flow.com.
   * @return string|null Vraci parametr nebo null.
   */
  public function getC2fParam(): ?string
  {
    return $this->c2fParam;
  } // getC2fParam()

  /** Datum vytvoreni zaznamu.
   * @return DateTime Vraci DateTime.
   */
  public function getDateCreated(): DateTime
  {
    return $this->dateCreated;
  } // getDateCreated()

  /** Datum modifikace zaznamu.
   * @return DateTime
   */
  public function getDateLastModified(): DateTime
  {
    return $this->dateLastModified;
  } // getDateLastModified()

} // Processes
