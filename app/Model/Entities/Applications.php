<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Entities;

use Sportisimo\Core\Model\Entities\APrimaryEntity;

/**
 * Class Applications
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Entities
 */
class Applications extends APrimaryEntity
{
  public const TABLE = 'sm_applications';

  public const NAME = 'name';

  /**
   * Nazev aplikace.
   * @var string
   */
	protected string $name;

  /**
   * @inheritDoc
   */
  protected function mapping(array $row): void
  {
    $this->name = (string)$row[self::NAME];
  } // mapping()

  /**
   * Ziskani nazvu aplikace.
   * @return string|null Vraci nazev aplikace nebo null.
   */
  public function getName(): ?string
  {
    return $this->name;
  } // getName()

} // Applications
