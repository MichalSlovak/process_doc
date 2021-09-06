<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Config\Providers;

use Sportisimo\Core\Nette\Config\Providers\IAppSettingsProvider;

/**
 * Class AppSettingsProvider
 * @package Sportisimo\Ecommerce\StockAdmin\Config\Providers
 */
final class AppSettingsProvider implements IAppSettingsProvider
{

  /**
   * @var array|mixed[]
   */
  private $settings;

  /**
   * TopComponent constructor.
   * @param array|mixed[] $settings
   */
  public function __construct(array $settings)
  {
    $this->settings = $settings;
  }

  public function getName(): string
  {
    return $this->settings['name'];
  } // getName()

  public function getSessionExpiration(): string
  {
    return $this->settings['sessionExpiration'];
  } // getSessionExpiration()

  /**
   * Ziska nastaveni logovani pristupu do systemovych sekci aplikace.
   * @return bool Vraci true, pokud ma byt logovan pristup do systemovych sekci aplikace, jinak false.
   */
  public function isLogSystemSectionAccess(): bool
  {
    return $this->settings['log']['systemSectionAccess'];
  } // isLogSections()

  /**
   * Ziska nastaveni logovani vykonani operaci.
   * @return bool Vraci true, pokud ma byt logovano vykonani operaci, jinak false.
   */
  public function isLogOperations(): bool
  {
    return $this->settings['log']['operations'];
  } // isLogOperations()

  /**
   * Ziska nastaveni logovani jsonu, ktery se posila na print server.
   * @return bool Vraci true, pokud se ma logovat json, ktery se posila na print server.
   */
  public function isLogJson(): bool
  {
    return $this->settings['log']['json'];
  } // isLogJson()

  /**
   * Ziska nastaveni logovani tisku
   * @return bool
   */
  public function isLogPrint(): bool
  {
    return $this->settings['log']['print'];
  } // isLogPrint()

  /**
   * Verze javascriptu.
   * @return int
   */
  public function getJsVersion(): int
  {
    return $this->settings['jsVersion'];
  } // getJsVersion()

  /**
   * Verze css.
   * @return int
   */
  public function getCssVersion(): int
  {
    return $this->settings['cssVersion'];
  } // getCssVersion()

  /**
   * Vrati iso code2 defaultniho jazyka.
   * @return string
   */
  public function getDefaultLanguageIsoCode2(): string
  {
    return $this->settings['defaultLanguageIsoCode2'];
  } // getDefaultLanguageIsoCode2()

  /**
   * Vrati defaultni dobu expirace pro potvrzeni systemovych operaci schvalovatelem
   * @return string
   */
  public function getSystemOperationApprovementRequestExpiration(): string
  {
    return $this->settings['approvement']['systemOperationApprovementRequestExpiration'];
  } // getSystemOperationApprovementRequestExpiration()

  /**
   * Vrati defaultni dobu expirace pro potvrzeni systemovych operaci schvalovatelem
   * @return string
   */
  public function getSystemOperationApprovementExpiration(): string
  {
    return $this->settings['approvement']['systemOperationApprovementExpiration'];
  } // getSystemOperationApprovementExpiration()

  /**
   * Vrati priznak, zda aplikace ma povoleny tisk (pri testovani ho napr. vypinam)
   * @return bool
   */
  public function isPrintEnabled(): bool
  {
    return $this->settings['printEnabled'];
  } // isPrintEnabled()

} // AppSettingsProvider
