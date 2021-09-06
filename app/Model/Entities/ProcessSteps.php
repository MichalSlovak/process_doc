<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities;

use Sportisimo\Core\DataTypes\DateTime;
use Sportisimo\Core\Model\Entities\APrimaryEntity;
use Sportisimo\Core\Utils\TypeUtils;

/**
 * Class ProcessSteps
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities
 */
class ProcessSteps extends APrimaryEntity
{
  public const TABLE = 'sm_process_steps';

  public const PROCESS_ID = 'process_id';
  public const PROCESS_VERSION_ID = 'process_version_id';
  public const PROCESS_STEP_TYPE_ID = 'process_step_type_id';
  public const PROCESS_STEP_SUBTYPE_ID = 'process_step_sub_type_id';
  public const NAME = 'name';
  public const USER_DESCRIPTION = 'user_description';
  public const TECHNICAL_DESCRIPTION = 'technical_description';
  public const IDEAS = 'ideas';
  public const PARENT_PROCESS_STEP_ID = 'parent_process_step_id';
  public const NEXT_PROCESS_ID = 'next_process_id';
  public const NEXT_PROCESS_STEP_ID = 'next_process_step_id';
  public const SORT_ORDER = 'sort_order';
  public const DATE_CREATED = 'date_created';
  public const DATE_LAST_MODIFIED = 'date_last_modified';

  /**
   * ID procesu.
   * @var int
   */
  protected $processId;

  /**
   * ID verze procesu.
   * @var int
   */
  protected $processVersionId;

  /**
   * Typ kroku - ve stromu se kroky seskupují podle jejich typu
   * @var int
   */
  protected $processStepTypeId;

  /**
   * Podtyp kroku - určuje ikonu, se kterou je krok zobrazen
   * @var int|null
   */
  protected $processStepSubtypeId;

  /**
   * Nazev kroku specifikace.
   * @var string
   */
  protected $name;

  /**
   * Uzivatelska specifikace - html kod.
   * @var string|null
   */
  protected $userDescription;

  /**
   * Technicka specifikace - html kod.
   * @var string|null
   */
  protected $technicalDescription;

  /**
   * Pripominky k procesu.
   * @var string|null
   */
  protected $ideas;

  /**
   * Rodic kroku - slouzi pro zobrazeni ve stromove strukture.
   * @var int|null
   */
  protected $parentProcessStepId;

  /**
   * Následující proces v poradi.
   * @var int|null
   */
  protected $nextProcessId;

  /**
   * Následující krok procesu v poradi.
   * @var int|null
   */
  protected $nextProcessStepId;

  /**
   * Typ kroku
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
    $this->processId = TypeUtils::convertToInt($row[self::PROCESS_ID]);
    $this->processVersionId = (int)$row[self::PROCESS_VERSION_ID];
    $this->processStepTypeId = TypeUtils::convertToInt($row[self::PROCESS_STEP_TYPE_ID]);
    $this->processStepSubtypeId = (int)$row[self::PROCESS_STEP_SUBTYPE_ID];
    $this->name = (string)$row[self::NAME];
    $this->userDescription = (string)$row[self::USER_DESCRIPTION];
    $this->technicalDescription = (string)$row[self::TECHNICAL_DESCRIPTION];
    $this->ideas = (string)$row[self::IDEAS];
    $this->parentProcessStepId = (int)$row[self::PARENT_PROCESS_STEP_ID];
    $this->nextProcessId = (int)$row[self::NEXT_PROCESS_ID];
    $this->nextProcessStepId = (int)$row[self::NEXT_PROCESS_STEP_ID];
    $this->sortOrder = (int)$row[self::SORT_ORDER];
    $this->dateCreated = TypeUtils::convertToDateTime($row[self::DATE_CREATED]);
    $this->dateLastModified = TypeUtils::convertToDateTime($row[self::DATE_LAST_MODIFIED]);
  } // mapping()

  /**
   * Ziskani ID procesu dane specifikace
   * @return int
   */
  public function getProcessId(): int
  {
    return $this->processId;
  } // getProcessId()

  /**
   * Ziskani ID procesu dane specifikace
   * @return int
   */
  public function getProcessVersionId(): int
  {
    return $this->processVersionId;
  } // getProcessVersionId()

  /**
   * Získání ID krok procesu dané specifikace
   * @return int
   */
  public function getProcessStepTypeId(): int
  {
    return $this->processStepTypeId;
  } // getProcessStepTypeId()

  /**
   * Podtyp kroku
   * @return int|null Vrací ID podtypu nebo null.
   */
  public function getProcessStepSubtypeId(): ?int
  {
    return $this->processStepSubtypeId;
  } // getProcessStepSubtypeId()

  /**
   * Nazev kroku daneho procesu
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  } // getName()

  /**
   * Uzivatelska specifikace - html kod.
   * @return string|null Vraci html kod nebo null
   */
  public function getUserDescriptionOrigin(): ?string
  {
    return $this->userDescription;
  } // getUserDescriptionOrigin()

  /**
   * Uživatelská specifikace - html kod.
   * @return string|null Vraci html kod nebo null
   */
  public function getUserDescription(): ?string
  {
    $parsedown = new \Parsedown();
    return $parsedown->text($this->getUserDescriptionOrigin());
  } // getUserDescription()

  /**
   * Technicka specifikace - html kod.
   * @return string|null Vraci html kod nebo null
   */
  public function getTechnicalDescription(): ?string
  {
		$parsedown = new \Parsedown();
		$stripTags = strip_tags($this->getTechnicalDescriptionOrigin());
		$stripTags = html_entity_decode($stripTags);
//		$markdown = preg_replace('@[\n\r]+@', "\n", $stripTags);
		return $parsedown->text($stripTags);
  } // getTechnicalDescription()

	public function getTechnicalDescriptionOrigin(): ?string
	{
	  return $this->technicalDescription;
	} // getTechnicalDescriptionOrigin()

  /**
   * Namety - poznamky ke kroku
   * @return string|null Vraci string nebo null
   */
  public function getIdeas(): ?string
  {
    return $this->ideas;
  } // getIdeas()

  /**
   * Rodic kroku
   * @return int|null Vraci id rodice nebo null
   */
  public function getParentProcessStepId(): ?int
  {
    return $this->parentProcessStepId;
  } // getParentProcessStepId()

  /**
   * Nasledujici proces v poradi.
   * @return int|null Vraci
   */
  public function getNextProcessId(): ?int
  {
    return $this->nextProcessId;
  } // getNextProcessId()

  /**
   * Nasledujici krok procesu v poradi.
   * @return int|null Vraci id nasledujiciho kroku nebo null
   */
  public function getNextProcessStepId(): ?int
  {
    return $this->nextProcessStepId;
  } // getNextProcessStepId()

  /**
   * Typ kroku
   * @return int|null Vraci ID kroku nebo null
   */
  public function getSortOrder(): ?int
  {
    return $this->sortOrder;
  } // getSortOrder()

  /**
   * Datum vytvoreni
   * @return DateTime
   */
  public function getDateCreated(): DateTime
  {
    return $this->dateCreated;
  } // getDateCreated()

  /**
   * Datum modifikace
   * @return DateTime
   */
  public function getDateLastModified(): DateTime
  {
    return $this->dateLastModified;
  } // getDateLastModified()

} //ProcessSteps
