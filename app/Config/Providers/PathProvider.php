<?php


namespace Sportisimo\ProcessDoc\Config\Providers;


use Nette\Http\Request;
use Sportisimo\Core\Nette\Config\Providers\IPathProvider;

class PathProvider implements IPathProvider
{
  /**
   * @var array|mixed[]
   */
  private $dirs;

  /**
   * @var array|mixed[]
   */
  private $paths;

  /**
   * @var Request
   */
  private $request;

  /**
   * PathService constructor.
   * @param array|mixed[] $dirs
   * @param array|mixed[] $paths
   * @param Request $request
   */
  public function __construct(array $dirs, array $paths, Request $request)
  {
    $this->dirs = $dirs;
    $this->paths = $paths;
    $this->request = $request;
  }

  /**
   * @return string Vrati souborovou cestu k www slozku
   */
  public function getWwwDir(): string
  {
    return $this->dirs['www'];
  } // getWwwDir()

  /**
   * @return string Vrati souborovou cestu k app slozku
   */
  public function getAppDir(): string
  {
    return $this->dirs['app'];
  } // getAppDir()

  /**
   * Vrati adresar kam aplikace loguje.
   * @return string
   */
  public function getLogDir(): string
  {
    return $this->dirs['log'];
  } // getLogDir()

  /**
   * @return string Vrati webovou cestu k www slozce
   */
  public function getWwwPath(): string
  {
    return $this->request->getUrl()->getBasePath();
  } // getWwwPath()

  public function getImagesPath(): string
  {
    return $this->getWwwPath() . 'assets/images/';
  } // getImagesPath()

  public function getIconsPath(): string
  {
    return $this->getImagesPath() . 'icons/';
  } // getIconsPath()

  /**
   * Ziska webovou cestu media serveru.
   * @return string Vraci webovou cestu media serveru.
   */
  public function getMediaPath(): string
  {
    return $this->paths['media'];
  } // getMediaPath()

  /**
   * Ziska webovou cestu media serveru.
   * @return string Vraci webovou cestu media serveru.
   */
  public function getApiPath(): string
  {
    return $this->paths['api'];
  } // getMediaPath()

  /**
   * Vrati cestu k temp adresari aplikace.
   * @return string
   */
  public function getTempPath(): string
  {
    return $this->dirs['temp'];
  }

  /**
   * Vrati cestu k temp adresari aplikace.
   * @return string
   */
  public function getTempDir(): string
  {
    // TODO: Implement getTempDir() method.
  }

  /**
   * Vrati cestu k public slozce, kam se exportuji soubory.
   * @return string
   * @throws \Sportisimo\Core\Exceptions\NotImplementedException
   */
  public function getPubDir(): string
  {
    throw new NotImplementedException('Pouziva se v consoli');
  }
}