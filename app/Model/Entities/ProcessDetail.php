<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities;

use Sportisimo\Core\DataTypes\DateTime;
use Sportisimo\Core\Model\Entities\APrimaryEntity;
use Sportisimo\Core\Utils\TypeUtils;

/**
 * Class ProcessDetail
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities
 */
class ProcessDetail extends APrimaryEntity
{
  public const TABLE = 'sm_process_steps';

  public const PROCESS_ID = 'process_id';
  public const TITLE = 'title';
  public const USER_SPECIFICATIONS = 'user_specifications';
  public const TECHNICAL_SPECIFICATIONS = 'technical_specifications';
  public const IDEAS = 'ideas';
  public const SPECIFICATION_PARENT_ID = 'specification_parent_id'; // rodic kroku
  public const CONTINUE_PROCESS_ID = 'continue_process_id'; // proces, kterým se pokračuje
  public const CONTINUE_SPECIFICATIONS_ID = 'continue_specifications_id'; //krok procesu, kterým se pokračuje
  public const PROCESS_STEP_TYPE_ID = 'process_step_type_id'; // typ kroku - ve stromu se kroky seskupují podle jejich typu
  public const PROCESS_STEP_SUBTYPE_ID = 'process_step_subtype_id';// podtyp kroku - určuje ikonu, se kterou je krok zobrazen
  public const SORT_ORDER = 'sort_order'; // určuje pořadí kroku v seskupení kroků stejného typu se společným rodičem
  public const DATE_CREATED = 'date_created';
  public const DATE_LAST_MODIFIED = 'date_last_modified';

  /**
   * ID processu ve kterem se nachazime.
   * @var int
   */
  protected $processId;

  /**
   * Nazev kroku danné specifikace
   * @var string|null
   */
  protected $title;

  /**
   * Popis uzivatelske specifikace v html
   * @var string|null
   */
  protected $userSpecifications;

  /**
   * Technicka specifikace v html
   * @var string|null
   */
  protected $technicalSpecifications;

  /**
   * Namety
   * @var string|null
   */
  protected $ideas;

  /**
   * Namety
   * @var int|null
   */
  protected $specificationParentId;

  /**
   * Nasledujici process
   * @var int|null
   */
  protected $continueProcessId;

  /**
   * Nasledujici process
   * @var int|null
   */
  protected $continueSpecificationsId;

  /**
   * Typ kroku
   * @var int|null
   */
  protected $processStepTypeId;

  /**
   * Typ kroku
   * @var int|null
   */
  protected $processStepSubtypeId;

  /**
   * Urcuje jak se bude vypis sortovat
   * @var int|null
   */
  protected $sortOrder;

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
    $this->processId = (int)$row[self::PROCESS_ID];
    $this->title = (string)$row[self::TITLE];
    $this->userSpecifications = (string)$row[self::USER_SPECIFICATIONS];
    $this->technicalSpecifications = (string)$row[self::TECHNICAL_SPECIFICATIONS];
    $this->ideas = (string)$row[self::IDEAS];
    $this->specificationParentId = (int)$row[self::SPECIFICATION_PARENT_ID];
    $this->continueProcessId = (int)$row[self::CONTINUE_PROCESS_ID];
    $this->continueSpecificationsId = (int)$row[self::CONTINUE_SPECIFICATIONS_ID];
    $this->processStepTypeId = TypeUtils::convertToInt($row[self::PROCESS_STEP_TYPE_ID]);
    $this->processStepSubtypeId = TypeUtils::convertToInt($row[self::PROCESS_STEP_SUBTYPE_ID]);
    $this->sortOrder = TypeUtils::convertToInt($row[self::SORT_ORDER]);
    $this->dateCreated = TypeUtils::convertToDateTime($row[self::DATE_CREATED]);
    $this->dateLastModified = TypeUtils::convertToDateTime($row[self::DATE_LAST_MODIFIED]);
  } // mapping()

  /**
   * @return int
   */
  public function getProcessId(): int
  {
    return $this->processId;
  } // getProcessId()

  /**
   * @return string|null
   */
  public function getTitle(): ?string
  {
    return $this->title;
  } // getTitle()

  /**
   * @return string|null
   */
  public function getUserSpecifications(): ?string
  {
    return $this->userSpecifications;
  } // getUserSpecifications()

  /**
   * @return string|null
   */
  public function getTechnicalSpecifications(): ?string
  {
    return $this->technicalSpecifications;
  } // getTechnicalSpecifications()

  /**
   * @return string|null
   */
  public function getIdeas(): ?string
  {
    return $this->ideas;
  } // getIdeas()

  /**
   * @return int|null
   */
  public function getSpecificationParentId(): ?int
  {
    return $this->specificationParentId;
  } // getSpecificationParentId()

  /**
   * @return int|null
   */
  public function getContinueProcessId(): ?int
  {
    return $this->continueProcessId;
  } // getContinueProcessId()

  /**
   * @return int|null
   */
  public function getContinueSpecificationsId(): ?int
  {
    return $this->continueSpecificationsId;
  } // getContinueSpecificationsId()

  /**
   * @return int|null
   */
  public function getProcessStepTypeId(): ?int
  {
    return $this->processStepTypeId;
  } // getProcessStepTypeId()

  /**
   * @return int|null
   */
  public function getProcessStepSubtypeId(): ?int
  {
    return $this->processStepSubtypeId;
  } // getProcessStepSubtypeId()

  /**
   * @return int|null
   */
  public function getSortOrder(): ?int
  {
    return $this->sortOrder;
  } // getSortOrder()

  /**
   * @return DateTime
   */
  public function getDateCreated(): DateTime
  {
    return $this->dateCreated;
  } // getDateCreated()

  /**
   * @return DateTime
   */
  public function getDateLastModified(): DateTime
  {
    return $this->dateLastModified;
  } // getDateLastModified()

} // Process
