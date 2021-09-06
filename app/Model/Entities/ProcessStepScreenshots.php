<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities;

use Sportisimo\Core\DataTypes\DateTime;
use Sportisimo\Core\Model\Entities\APrimaryEntity;
use Sportisimo\Core\Utils\TypeUtils;

/**
 * Class ProcessStepScreenshots
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities
 */
class ProcessStepScreenshots extends APrimaryEntity
{
  public const TABLE = 'sm_process_step_screenshots';

  public const PROCESS_STEP_ID = 'process_step_id';
  public const FILE_PATH = 'file_path';
  public const DATE_CREATED = 'date_created';
  public const DATE_LAST_MODIFIED = 'date_last_modified';

  /**
   * Urcuje krok procesu.
   * @var int
   */
  protected $processStepId;

  /**
   * Urcuje krok procesu.
   * @var string
   */
  protected $filePath;

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
    $this->processStepId = (int)$row[self::PROCESS_STEP_ID];
    $this->filePath = (string)$row[self::FILE_PATH];
    $this->dateCreated = TypeUtils::convertToDateTime($row[self::DATE_CREATED]);
    $this->dateLastModified = TypeUtils::convertToDateTime($row[self::DATE_LAST_MODIFIED]);
  } // mapping()

  /**
   * Definuje konkretni krok procesu.
   * @return int
   */
  public function getProcessStepId(): int
  {
    return $this->processStepId;
  } // getProcessStepId()

  /**
   * URL cesta k souboru
   * @return string|null
   */
  public function getFilePath(): string
  {
    return $this->filePath;
  } // getFilePath()

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

} // ProcessStepScreenshots
