<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities;

use Sportisimo\Core\DataTypes\DateTime;
use Sportisimo\Core\Model\Entities\APrimaryEntity;
use Sportisimo\Core\Utils\TypeUtils;

/**
 * Class Authors
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities
 */
class Authors extends APrimaryEntity
{
  public const TABLE = 'sm_authors';

  public const AUTHOR = 'author';
  public const DATE_CREATED = 'date_created';
  public const DATE_LAST_MODIFIED = 'date_last_modified';

	/**
	 * Jmeno autora
	 * @var string
	 */
	protected $author;

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
    $this->author = (string)$row[self::AUTHOR];
    $this->dateCreated = TypeUtils::convertToDateTime($row[self::DATE_CREATED]);
    $this->dateLastModified = TypeUtils::convertToDateTime($row[self::DATE_LAST_MODIFIED]);
  } // mapping()

	/**
	 * Ziskani jmeno autora.
	 * @return string
	 */
	public function getAuthor(): string
	{
		return $this->author;
	} // getAuthor()

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

} // Authors
