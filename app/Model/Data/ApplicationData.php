<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Model\Data;

use Sportisimo\Core\Nette\Model\Data\LinkData;

/**
 * Class ApplicationData
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Data
 */
class ApplicationData
{
  /**
   * Rodicovska kategorie procesu
   * @var int|null
   */
  public $name;

  /**
   * Odkaz do detailu aplikace pro vypsani vsech procesu vybrane aplikace
   * @var LinkData|null
   */
  public $subProcessTreeLinkData;

} // ApplicationData