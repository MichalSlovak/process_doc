<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities;

use Sportisimo\Core\DataTypes\DateTime;
use Sportisimo\Core\Model\Entities\APrimaryEntity;
use Sportisimo\Core\Utils\TypeUtils;

/**
 * Class ProcessVersions
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities
 */
class ProcessVersions extends APrimaryEntity
{
  public const TABLE = 'sm_process_versions';

	public const PROCESS_ID = 'process_id';
	public const AUTHOR_ID = 'author_id';
	public const REQUEST = 'request';
	public const DATE_CREATED = 'date_created';
	public const DATE_LAST_MODIFIED = 'date_last_modified';

  /**
   * Id procesu kterou verze definuje
   * @var int
   */
  protected $processId;

  /**
   * Autor verze procesu
   * @var int|null
   */
  protected $authorId;

  /**
   * Datum vytvoreni zaznamu.
   * @var string|null
   */
  protected $request;

	/**
	 * Datum vztvo5en9 procesu zaznamu.
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
		$this->processId = (int)$row[self::PROCESS_ID];
		$this->authorId = (int)$row[self::AUTHOR_ID];
		$this->request = (string)$row[self::REQUEST];
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
	 * @return int
	 */
	public function getAuthorId(): int
	{
		return $this->authorId;
	} // getAuthorId()

	/**
	 * @return string|null
	 */
	public function getRequest(): ?string
	{
		return $this->request;
	} // getRequest()

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

} // ProcessVersions
