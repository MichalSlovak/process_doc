<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities;

use Sportisimo\Core\DataTypes\DateTime;
use Sportisimo\Core\Model\Entities\APrimaryEntity;
use Sportisimo\Core\Utils\TypeUtils;

/**
 * Class ProcessStatuses
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities
 */
class ProcessStatuses extends APrimaryEntity
{
  public const TABLE = 'sm_process_statuses';

  public const CODE = 'code';
  public const SORT_ORDER = 'sort_order';
  public const DATE_CREATED = 'date_created';
  public const DATE_LAST_MODIFIED = 'date_last_modified';

  /**
   * Kod typu.
   * @var string
   */
  protected $code;

  /**
   * Razeni.
   * @var int|null
   */
  protected $sortOrder;

  /**
   * Datum vytvoreni zaznamu.
   * @var DateTime
   */
  protected $dateCreated;

  /**
   * Datum upravy zaznamu.
   * @var DateTime
   */
  protected $dateLastModified;

  /**
   * @inheritDoc
   */
  protected function mapping(array $row): void
  {
    $this->id = (int)$row[self::ID];
    $this->code = $row[self::CODE];
    $this->sortOrder = (int)$row[self::SORT_ORDER];
    $this->dateCreated = TypeUtils::convertToDateTime($row[self::DATE_CREATED]);
    $this->dateLastModified = TypeUtils::convertToDateTime($row[self::DATE_LAST_MODIFIED]);
  } // mapping()

  /**
   * Nazev (db => status)
   * @return string
   */
  public function getCode(): string
  {
    return $this->code;
  } // getCode()

  /**
   * int urcujici poradi
   * @return int|null
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

} // ProcessStatuses
