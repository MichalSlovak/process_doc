<?php declare(strict_types = 1);

namespace Sportisimo\Ecommerce\StockAdmin\Components\ArticleDetailSrp\Model\Services;

use Kdyby\Translation\ITranslator;
use Nette\InvalidStateException;
use Sportisimo\Api\Printer\PrinterApi;
use Sportisimo\Core\Database\IDatabase;
use Sportisimo\Core\DataTypes\DateTime;
use Sportisimo\Core\DataTypes\Dim;
use Sportisimo\Core\Exceptions\Exception;
use Sportisimo\Core\Exceptions\IConnectionException;
use Sportisimo\Core\Exceptions\IOException;
use Sportisimo\Core\Exceptions\LogicException;
use Sportisimo\Core\Exceptions\NoResultException;
use Sportisimo\Core\Log\ALogger;
use Sportisimo\Core\Log\ILogger;
use Sportisimo\Core\Nette\Config\Providers\IPathProvider;
use Sportisimo\Core\Nette\Model\Data\LinkData;
use Sportisimo\Ecommerce\Model\Core\Entities\Currency;
use Sportisimo\Ecommerce\Model\Core\Entities\Language;
use Sportisimo\Ecommerce\Model\Core\Entities\Printer;
use Sportisimo\Ecommerce\Model\Core\Entities\Services\CurrencyService;
use Sportisimo\Ecommerce\Model\Domains\Entities\Domain;
use Sportisimo\Ecommerce\Model\Domains\Entities\DomainGroup;
use Sportisimo\Ecommerce\Model\Products\Entities\ProductVariant;
use Sportisimo\Ecommerce\Model\Stocks\Entities\Services\StockService;
use Sportisimo\Ecommerce\Model\Stocks\Entities\Stock;
use Sportisimo\Ecommerce\Model\Stores\Entities\Store;
use Sportisimo\Ecommerce\StockAdmin\Model\Context;
use Sportisimo\Ecommerce\StockAdmin\Model\Services\ProductImageService;
use Sportisimo\Erp\Model\Articles\Entities\Article;
use Sportisimo\Erp\Model\Articles\Entities\ArticleAttributeValueDescription;
use Sportisimo\Erp\Model\Articles\Entities\ArticleDescription;
use Sportisimo\Erp\Model\Articles\Entities\ArticleVariant;
use Sportisimo\Erp\Model\Articles\Entities\ArticleVariantCode;
use Sportisimo\Erp\Model\Articles\Entities\Lists\ArticleGroupList;
use Sportisimo\Erp\Model\Articles\Entities\Lists\ArticleList;
use Sportisimo\Erp\Model\Articles\Entities\Lists\Filters\ArticleFiltration;
use Sportisimo\Erp\Model\Categories\Entities\Services\CategoryDescriptionService;
use Sportisimo\Erp\Model\Categories\Entities\Services\CategoryService;
use Sportisimo\Erp\Model\Core\Entities\Services\ExternSystemService;
use Sportisimo\Erp\Model\StoreShelfFilling\Entities\StoreShelfFillingItem;
use Sportisimo\Erp\PrintSets\ArticleVariantLabel\ArticleVariantLabel;
use Sportisimo\Erp\PrintSets\ArticleVariantLabel\ArticleVariantLabelValueObject;
use Sportisimo\Erp\PrintSets\ArticleVariantLabel\Model\Services\ArticleVariantLabelService;

/**
 * Class ArticleDetailSrpService
 * Copyright (c) 2017 Sportisimo s.r.o.
 * @package Sportisimo\Ecommerce\StockAdmin\Components\ArticleDetailSrp\Model\Services
 */
class ArticleDetailSrpService
{

  /**
   * @var \Sportisimo\Core\Database\IDatabase
   */
  private $srpDatabase;

  /**
   * @var \Sportisimo\Ecommerce\StockAdmin\Model\IContext
   */
  private $context;

  /**
   * @var \Sportisimo\Core\Database\IDatabase
   */
  private $database;

  /**
   * @var \Sportisimo\Ecommerce\StockAdmin\Model\Services\ProductImageService
   */
  private $productImageService;

  /**
   * @var \Sportisimo\Core\Nette\Config\Providers\IPathProvider
   */
  private $pathProvider;

  /**
   * @var \Sportisimo\Ecommerce\Model\Core\Entities\Services\CurrencyService
   */
  private $currencyService;

  /**
   * Aktualni mena.
   * @var Currency|null
   */
  private $currency;

  /**
   * Cena bez meny (nenaformatovana).
   * @var float|null
   */
  private $price;

  /**
   * @var \Kdyby\Translation\ITranslator
   */
  private $translator;

  /**
   * @var \Sportisimo\Erp\PrintSets\ArticleVariantLabel\ArticleVariantLabel
   */
  private $articleVariantLabel;

  /**
   * @var \Sportisimo\Api\Printer\PrinterApi
   */
  private $printerApi;

  /**
   * abych ji nenacital vickrat, tak ulozena zde
   * @var ArticleVariant
   */
  private $articleVariant;

  /**
   * @var \Sportisimo\Erp\Model\Articles\Entities\Lists\ArticleGroupList
   */
  private $articleGroupList;

  /**
   * Srpackej language pro stock.
   * @var \Sportisimo\Erp\Model\Core\Entities\Language
   */
  private $stockSrpLanguage;

  /**
   * Srpackej Language defaultni stockAdmin jazyka.
   * @var \Sportisimo\Erp\Model\Core\Entities\Language
   */
  private $defaultSrpAdminLanguage;

  /**
   * @var \Sportisimo\Erp\Model\Articles\Entities\Lists\ArticleList
   */
  private $articleList;

  /**
   * @var \Sportisimo\Erp\Model\Core\Entities\Services\ExternSystemService
   */
  private $srpExternSystemService;

  /**
   * @var \Sportisimo\Core\Log\ILogger
   */
  private $logger;

  /**
   * @var \Sportisimo\Ecommerce\Model\Stocks\Entities\Services\StockService
   */
  private $stockService;

  /**
   * @var \Sportisimo\Erp\Model\Categories\Entities\Services\CategoryService
   */
  private $categoryService;

  /**
   * @var \Sportisimo\Erp\Model\Categories\Entities\Services\CategoryDescriptionService
   */
  private $categoryDescriptionService;

  /**
   * ArticleDetailSrpService constructor.
   * @param \Sportisimo\Core\Database\IDatabase $srpDatabase
   * @param \Sportisimo\Core\Database\IDatabase $database
   * @param \Sportisimo\Ecommerce\StockAdmin\Model\Context $context
   * @param \Sportisimo\Ecommerce\StockAdmin\Model\Services\ProductImageService $productImageService
   * @param \Sportisimo\Core\Nette\Config\Providers\IPathProvider|\Sportisimo\Ecommerce\StockAdmin\Config\Providers\PathProvider $pathProvider
   * @param \Sportisimo\Ecommerce\Model\Core\Entities\Services\CurrencyService $currencyService
   * @param \Kdyby\Translation\ITranslator $translator
   * @param \Sportisimo\Erp\PrintSets\ArticleVariantLabel\ArticleVariantLabel $articleVariantLabel
   * @param \Sportisimo\Api\Printer\PrinterApi $printerApi
   * @param \Sportisimo\Erp\Model\Articles\Entities\Lists\ArticleGroupList $articleGroupList
   * @param \Sportisimo\Erp\Model\Articles\Entities\Lists\ArticleList $articleList
   * @param \Sportisimo\Erp\Model\Core\Entities\Services\ExternSystemService $srpExternSystemService
   * @param \Sportisimo\Core\Log\ILogger $logger
   * @param \Sportisimo\Ecommerce\Model\Stocks\Entities\Services\StockService $stockService
   * @param \Sportisimo\Erp\Model\Categories\Entities\Services\CategoryService $categoryService
   * @param \Sportisimo\Erp\Model\Categories\Entities\Services\CategoryDescriptionService $categoryDescriptionService
   */
  public function __construct(
    IDatabase $srpDatabase,
    IDatabase $database,
    Context $context,
    ProductImageService $productImageService,
    IPathProvider $pathProvider,
    CurrencyService $currencyService,
    ITranslator $translator,
    ArticleVariantLabel $articleVariantLabel,
    PrinterApi $printerApi,
    ArticleGroupList $articleGroupList,
    ArticleList $articleList,
    ExternSystemService $srpExternSystemService,
    ILogger $logger,
    StockService $stockService,
    CategoryService $categoryService,
    CategoryDescriptionService $categoryDescriptionService
  ) {
    $this->srpDatabase = $srpDatabase;
    $this->context = $context;
    $this->database = $database;
    $this->productImageService = $productImageService;
    $this->pathProvider = $pathProvider;
    $this->currencyService = $currencyService;
    $this->translator = $translator;
    $this->articleVariantLabel = $articleVariantLabel;
    $this->printerApi = $printerApi;
    $this->articleGroupList = $articleGroupList;
    $this->articleList = $articleList;
    $this->srpExternSystemService = $srpExternSystemService;
    $this->logger = $logger;
    $this->stockService = $stockService;
    $this->categoryService = $categoryService;
    $this->categoryDescriptionService = $categoryDescriptionService;
  } // __construct()

  /**
   * ziska Article data z articleId.
   * @param string $articleId
   * @return \Sportisimo\Erp\Model\Articles\Entities\Article
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function fetchArticleByArticleId(string $articleId): Article
  {
    return new Article($this->srpDatabase, $articleId);
  } // fetchArticleByArticleId()

  /**
   * Funkce pro kontrolu existence varianty artiklu.
   * @param string $articleVariantCode
   * @return ArticleVariant
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function fetchArticleVariantByArticleVariantCode(string $articleVariantCode): ArticleVariant
  {
    //pokud uz sem ji nacetl, tak ji vratim
    if ($this->articleVariant !== null)
    {
      return $this->articleVariant;
    }

    //vytahnu entitu kodu a articleVariantId z toho pouziji pri vytvoreni.
    $articleVariantCodeEntity = new ArticleVariantCode($this->srpDatabase, null, $articleVariantCode);

    return $this->articleVariant = new ArticleVariant($this->srpDatabase, $articleVariantCodeEntity->getArticleVariantId());
  } // fetchArticleVariantByArticleVariantCode()

  /**
   * ziska pocet artiklu na konkretnim sklade
   * @param \Sportisimo\Erp\Model\Articles\Entities\Article $article
   * @param \Sportisimo\Ecommerce\Model\Stocks\Entities\Stock $stock
   * @return int|null
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function getArticleStockCount(Article $article, Stock $stock): ?int
  {
    $srpStockApi = $this->srpExternSystemService->getStockApiByStockId($stock->getId());
    return $srpStockApi->getArticleStockQuantity($article->getId(), $stock->getId());
  } // getArticleStockCount()

  /**
   * ziska pocet varianty artiklu na konkretnim sklade.
   * @param \Sportisimo\Erp\Model\Articles\Entities\ArticleVariant $articleVariant
   * @param \Sportisimo\Ecommerce\Model\Stocks\Entities\Stock $stock
   * @return int|null
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function getArticleVariantStockCount(ArticleVariant $articleVariant, Stock $stock): ?int
  {
    $srpStockApi = $this->srpExternSystemService->getStockApiByStockId($stock->getId());
    return $srpStockApi->getArticleVariantStockQuantity($articleVariant->getCode(), $stock->getId());
  } // getArticleVariantStockCount()

  /**
   * Metoda, ktera ziska celkovou skladovou zasobu na vsech CS pro vsechny zeme.
   * @param \Sportisimo\Erp\Model\Articles\Entities\Article $article
   * @return int|null
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function getArticleStockForCS(Article $article): ?int
  {
    $csStockIds = $this->stockService->findCentralStockIds();
    $totalCSStocksCount = 0;

    //projdu vsechny sklady
    foreach ($csStockIds as $csStockId)
    {
      //zjistim skladovou zasobu na konkretnim centralnim skladu a prictu ji k celkovemu mnozstvi
      $srpStockApi = $this->srpExternSystemService->getStockApiByStockId($csStockId);
      $stockCount = $srpStockApi->getArticleStockQuantity($article->getId(), $csStockId);

      if ($stockCount === null)
      {
        return null;
      }

      $totalCSStocksCount += $stockCount;
    }

    return $totalCSStocksCount;
  } // getArticleStockForCS()

  /**
   * vrati title artiklu (znacka s jmenem)
   * @param \Sportisimo\Erp\Model\Articles\Entities\Article $article
   * @return string
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function getArticleTitle(Article $article): string
  {
    $brand = $article->getBrand();
    try
    {
      $stockSrpLanguage = $this->fetchSrpLanguageByStock();
      $articleDescription = new ArticleDescription($this->srpDatabase,
                                                   $article->getId(),
                                                   $stockSrpLanguage->getId());

      $articleDescriptionName = $articleDescription->getName();
    }
    catch (NoResultException $e)
    {
      //pokud jmeno artiklu neexistuje v jazyce skladu, tak se ho pokusim vytahnout v defaultnim jazyce
      try
      {
        $defaultStockAdminSrpLanguage = $this->fetchSrpLanguageByStockAdminDefaultLanguage();
        $articleDescription = new ArticleDescription($this->srpDatabase,
                                                     $article->getId(),
                                                     $defaultStockAdminSrpLanguage->getId());

        $articleDescriptionName = $articleDescription->getName();
      }
      catch (NoResultException $e)
      {
        //pokud jmeno artiklu neexistuje ani v defaultnim jazyce, tak jmeno artiklunecham prazdny
        $articleDescriptionName = '';
      }

    }
    return $brand->getName() . ' ' . $articleDescriptionName;
  } // getArticleTitle()

  /**
   * Ziska podtitulek. Skupina produktu.
   * @param \Sportisimo\Erp\Model\Articles\Entities\Article $article
   * @return string
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function getArticleSubtitle(Article $article): string
  {
    $categoriesIds = $this->categoryService->findIdsByArticleId($article->getId());

    //pokud neni prirazena k artiklu zadna kategorie, tak vratim prazdny retezec
    if (empty($categoriesIds))
    {
      return '';
    }

    //zpracuju jen prvni nalezenou kategorii
    $categoryId = $categoriesIds[0];
    $stockSrpLanguage = $this->fetchSrpLanguageByStock();

    try
    {
      $description = $this->categoryDescriptionService->fetch($categoryId, $stockSrpLanguage->getId());

      //pokud najdu description v jazycku skladu, tak ho vration
      return $description->getTitle();
    }
    catch (NoResultException $e)
    {
      //pokud nenajdu description v jazyce skladu, tak se pokusim nacist dle defaultniho jazyka
      $defaultStockAdminSrpLanguage = $this->fetchSrpLanguageByStockAdminDefaultLanguage();

      try
      {
        $description = $this->categoryDescriptionService->fetch($categoryId, $defaultStockAdminSrpLanguage->getId());

        return $description->getTitle();
      }
      catch (NoResultException $e)
      {
        //potlacim pak vratim vzdy pak uz pripadne ''
      }
    }

    return '';
  } // getArticleSubtitle()

  /**
   * vrati cestu k obrazku varianty artiklu
   * @param \Sportisimo\Erp\Model\Articles\Entities\ArticleVariant $articleVariant
   * @return string
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   */
  public function getArticleImage(ArticleVariant $articleVariant): string
  {
    try
    {
      $productVariant = new ProductVariant($this->database, null, $articleVariant->getCode());
      $imagePath = $this->productImageService->getPreviewPathByProductVariant($productVariant, new Dim(330, 330));
    }
    catch (NoResultException $e)
    {
      $imagePath = null;
    }

    if($imagePath !== null)
    {
      return $this->pathProvider->getMediaPath() . $imagePath;
    }

    return '';
  } // getArticleImage()

  /**
   * ziska hodnotu velikosti - M,L,XL, ...
   * @param \Sportisimo\Erp\Model\Articles\Entities\ArticleVariant $articleVariant
   * @return null|string
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   */
  public function getAttributeValue(ArticleVariant $articleVariant): ?string
  {
    $name = null;

    // Ziskani popisu hodnoty atributu varianty
    $attributeValueId = $articleVariant->getArticleAttributeValueId();
    if($attributeValueId !== null)
    {
      try
      {
        $attributeValueDescription = new ArticleAttributeValueDescription($this->srpDatabase, $attributeValueId, $this->fetchSrpLanguageByStock()->getId());
        $name = $attributeValueDescription->getName();
      }
      catch(NoResultException $e)
      {
        // Umyslne potlaceno.
      }
    }
    return $name;
  } // getAttributeValue()

  /**
   * ziska obvyklou cenu artiklu
   * @param \Sportisimo\Erp\Model\Articles\Entities\Article $article
   * @return string|null
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\DateTimeException
   * @throws \Sportisimo\Core\Exceptions\IConnectionException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   * @throws \Sportisimo\Core\Exceptions\NotImplementedException
   */
  public function getNormalPrice(Article $article): ?string
  {
    $srpPriceApi = $this->srpExternSystemService->getPriceApiByStockId($this->context->getStock()->getId());
    $normalPrice = $srpPriceApi->getArticleNormalPrice($article->getId(), $this->context->getStock()->getId(), $this->getCurrency()->getIsoCode(), new DateTime());
    if($normalPrice !== null && $this->getPriceWithoutCurrency($article) !== null && $normalPrice - $this->getPriceWithoutCurrency($article) > 0.0001)
    {
      return $this->currencyService->formatPriceWithCurrency($this->getCurrency(), $normalPrice, true, true);
    }

    return null;
  } // getNormalPrice()

  /**
   * ziska aktualni cenu artiklu
   * @param \Sportisimo\Erp\Model\Articles\Entities\Article $article
   * @return string|null
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\IConnectionException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function getPrice(Article $article): ?string
  {
    $price = $this->getPriceWithoutCurrency($article);
    if($price !== null)
    {
      return $this->currencyService->formatPriceWithCurrency($this->getCurrency(), $price, true, true);
    }

    return null;
  } // getPrice()

  /**
   * Odkaz pro signal na vytisknuti stitku produktu.
   * @param \Sportisimo\Erp\Model\Articles\Entities\ArticleVariant $articleVariant
   * @param \Sportisimo\Core\Nette\Model\Data\LinkData $linkData
   * @return \Sportisimo\Core\Nette\Model\Data\LinkData
   */
  public function getControlPrintLabelLinkData(ArticleVariant $articleVariant, LinkData $linkData): LinkData
  {
    $linkData->params[$this->translator->translate('urlParams.articleVariant')] = $articleVariant->getId();
    $linkData->params[$this->translator->translate('urlParams.signal')] = $this->translator->translate('urlParams.articleDetailSrp.signals.printLabel');

    return $linkData;
  } // getControlPrintLabelLinkData()

  /**
   * odkaz na tisk stitku ve storeShelfFIlling
   * @param \Sportisimo\Erp\Model\Articles\Entities\ArticleVariant $articleVariant
   * @param \Sportisimo\Core\Nette\Model\Data\LinkData $linkData
   * @return \Sportisimo\Core\Nette\Model\Data\LinkData
   */
  public function getStoreShelfFillingPrintLabelLinkData(ArticleVariant $articleVariant, LinkData $linkData): LinkData
  {
    $linkData->params[$this->translator->translate('urlParams.store.articleVariantId')] = $articleVariant->getId();
    $linkData->params[$this->translator->translate('urlParams.signal')] = $this->translator->translate('urlParams.store.signals.printLabel');

    return $linkData;
  } // getStoreShelfFillingPrintLabelLinkData()

  /**
   * odkaz na zvyseni item quantity ve storeShelfFilling
   * @param \Sportisimo\Erp\Model\Articles\Entities\ArticleVariant $articleVariant
   * @param \Sportisimo\Core\Nette\Model\Data\LinkData $linkData
   * @return \Sportisimo\Core\Nette\Model\Data\LinkData
   */
  public function getStoreShelfFillingIncrementQuantityLinkData(ArticleVariant $articleVariant, LinkData $linkData): LinkData
  {
    $linkData->params[$this->translator->translate('urlParams.store.articleVariantId')] = $articleVariant->getId();
    $linkData->params[$this->translator->translate('urlParams.signal')] = $this->translator->translate('urlParams.store.signals.incrementItemQuantity');

    return $linkData;
  } // getStoreShelfFillingIncrementQuantityLinkData()

  /**
   * odkaz na snizeni item quantity ve storeShelfFilling
   * @param \Sportisimo\Erp\Model\Articles\Entities\ArticleVariant $articleVariant
   * @param \Sportisimo\Core\Nette\Model\Data\LinkData $linkData
   * @return \Sportisimo\Core\Nette\Model\Data\LinkData
   */
  public function getStoreShelfFillingDecrementQuantityLinkData(ArticleVariant $articleVariant, LinkData $linkData): LinkData
  {
    $linkData->params[$this->translator->translate('urlParams.store.articleVariantId')] = $articleVariant->getId();
    $linkData->params[$this->translator->translate('urlParams.signal')] = $this->translator->translate('urlParams.store.signals.decrementItemQuantity');

    return $linkData;
  } // getStoreShelfFillingDecrementQuantityLinkData()

  /**
   * odkaz pro signal na vytisknuti stitku vsech velikosti.
   * @param int $id
   * @param \Sportisimo\Core\Nette\Model\Data\LinkData $linkData
   * @return \Sportisimo\Core\Nette\Model\Data\LinkData
   */
  public function getPrintAllLabelsLinkData(int $id, LinkData $linkData): LinkData
  {
    $linkData->params[$this->translator->translate('urlParams.articleVariant')] = $id;
    $linkData->params[$this->translator->translate('urlParams.signal')] = $this->translator->translate('urlParams.articleDetailSrp.signals.printAllLabels');

    return $linkData;
  } // getPrintAllLabelsLinkData()

  /**
   * Funkce pro tisk stitku varianty artiklu.
   * @param \Sportisimo\Erp\Model\Articles\Entities\ArticleVariant $articleVariant
   * @param \Sportisimo\Ecommerce\Model\Stocks\Entities\Stock $stock
   * @param null|\Sportisimo\Ecommerce\Model\Core\Entities\Printer $printer
   * @param bool $onlyTestPrintPossible
   * @return bool
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function printArticleVariantLabel(ArticleVariant $articleVariant, Stock $stock, ?Printer $printer, bool $onlyTestPrintPossible = false): bool
  {
    $country = $stock->getCountry();
    $currency = new Currency($this->database, $country->getCurrencyId());
    $language = new Language($this->database, $country->getLanguageId());
    $srpPriceApi = $this->srpExternSystemService->getPriceApiByStockId($stock->getId());

    // TODO: Prenest do servisy
    try
    {
      $store = $stock->getStore();
      if($store === null)
      {
        throw new NoResultException(Store::class);
      }
      $domainGroup = new DomainGroup($this->database, $store->getDomainGroupId());
      $domain = new Domain($this->database, $domainGroup->getDefaultDomainId());
      $domainName = 'www.' . $domain->getName();
    }
    catch(Exception $e)
    {
      switch($country->getIsoCode2())
      {
        case 'cs':
          $domainName = 'www.sportisimo.cz';
          break;
        case 'pl':
          $domainName = 'www.sportisimo.pl';
          break;
        case 'ro':
          $domainName = 'www.sportisimo.ro';
          break;
        case 'sk':
          $domainName = 'www.sportisimo.sk';
          break;
        case 'hu':
          $domainName = 'www.sportisimo.hu';
          break;
        default:
          $domainName = 'www.sportisimo.cz';
      }
    }

    // Priprava dat k tisku
    try
    {
      $articleVariantLabelValueObject = new ArticleVariantLabelValueObject(
        $stock->getId(),
        $srpPriceApi,
        $currency->getIsoCode(),
        $language->getIsoCode2(),
        $domainName
      );
      $articleVariantLabelValueObject->setType(ArticleVariantLabel::ZPL);
//      $articleVariantLabelValueObject->setSpecialText('NS');
      $zpl = $this->articleVariantLabel->data([$articleVariant->getId()], $articleVariantLabelValueObject);
    }
    catch (IConnectionException $e)
    {
      //pokud neni k dispozici tpomm - to asi logovat nemusim..prej to bude dost casto
      $this->logger->debugAlert($e);
      return false;
    }
    catch (LogicException $e)
    {
      //pokud dostanu logic exception pri tisku, tak to znamena, ze d365 (kterou nepingam) byla docasne nedostupna..
      $this->logger->debugWarning($e);
      return false;
    }
    catch (NoResultException $e)
    {

      if ($e->getClass() === ArticleVariantLabelService::class)
      {
        //TODO docasne reseni, kdyz neni EAN v srpu, tak zalogovat do extra souboru
        ALogger::deprecatedDebug($e, 'chybnyEan');
//        $this->logger->debug($e, 'chybnyEan');
      }
      else
      {
        $this->logger->debugAlert($e);
      }
      return false;

    }
    catch(Exception | InvalidStateException $e)
    {
      $this->logger->debugException($e);
      return false;
    }

    //pokud sem chtel otestovat pouze zda je umoznen tisk, tak v tuto chvili data prosly, takze umoznen je a vratim true
    if ($onlyTestPrintPossible)
    {
      return true;
    }

    //pokud mam tisknout a neexistuje tiskarna, tak vratim false, jako chybu.
    if ($printer === null)
    {
      return false;
    }

    // Tisk
    try
    {
      $this->printerApi->print($zpl, $printer);
    }
    catch(IOException $e)
    {
      $this->logger->debugAlert($e);
      return false;
    }

    return true;
  } // printArticleVariantLabel()

  /**
   * Nacte polozky doplneni podle doplneni a varianty artiklu.
   * @param int $storeShelfFillingId
   * @param int $articleVariantId
   * @return \Sportisimo\Erp\Model\StoreShelfFilling\Entities\StoreShelfFillingItem
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function fetchStoreShelfFillingItemByFillingIdAndArticleVariantId(int $storeShelfFillingId, int $articleVariantId): StoreShelfFillingItem
  {
    return new StoreShelfFillingItem($this->srpDatabase, null, $storeShelfFillingId, $articleVariantId);
  } // fetchStoreShelfFillingItemByFillingIdAndArticleVariantId()

  /**
   * vrati aktualni cenu artiklu bez meny
   * @param \Sportisimo\Erp\Model\Articles\Entities\Article $article
   * @return float|null
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   * @throws \Sportisimo\Core\Exceptions\IConnectionException
   */
  private function getPriceWithoutCurrency(Article $article): ?float
  {
    if($this->price !== null)
    {
      return $this->price;
    }

    $srpPriceApi = $this->srpExternSystemService->getPriceApiByStockId($this->context->getStock()->getId());
    return $this->price = $srpPriceApi->getArticlePrice($article->getId(), $this->context->getStock()->getId(), $this->getCurrency()->getIsoCode(), new DateTime());
  } // getPriceWithoutCurrency()

  /**
   * ziska aktulani menu pro pouzivany sklad
   * @return \Sportisimo\Ecommerce\Model\Core\Entities\Currency
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  private function getCurrency(): Currency
  {
    if($this->currency !== null)
    {
      return $this->currency;
    }
    $country = $this->context->getStock()->getCountry();

    return $this->currency = new Currency($this->database, $country->getCurrencyId());
  } // getCurrency()

  /**
   * Ziska jazyk srpu ze skladu.
   * @return \Sportisimo\Erp\Model\Core\Entities\Language
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  private function fetchSrpLanguageByStock(): \Sportisimo\Erp\Model\Core\Entities\Language
  {
    if($this->stockSrpLanguage !== null)
    {
      return $this->stockSrpLanguage;
    }

    $country = $this->context->getStock()->getCountry();
    $language = new Language($this->database, $country->getLanguageId());

    $this->stockSrpLanguage = new \Sportisimo\Erp\Model\Core\Entities\Language($this->srpDatabase, null, $language->getIsoCode2());

    return $this->stockSrpLanguage;
  } // fetchSrpLanguageByStock()

  /**
   * Vrati srpackej jazyk z naseho.
   * @return \Sportisimo\Erp\Model\Core\Entities\Language
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Database\Exceptions\DeadlockException
   * @throws \Sportisimo\Core\Database\Exceptions\LockException
   * @throws \Sportisimo\Core\Exceptions\DuplicateException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  private function fetchSrpLanguageByStockAdminDefaultLanguage(): \Sportisimo\Erp\Model\Core\Entities\Language
  {
    if ($this->defaultSrpAdminLanguage !== null)
    {
      return $this->defaultSrpAdminLanguage;
    }

    $defaultAdminLanguage = $this->context->getDefaultAdminLanguage();

    $this->defaultSrpAdminLanguage = new \Sportisimo\Erp\Model\Core\Entities\Language($this->srpDatabase, null, $defaultAdminLanguage->getCode());

    return $this->defaultSrpAdminLanguage;
  } // fetchSrpLanguageByStockAdminDefaultLanguage()

  /**
   * Zkontroluje zda zadany artikle je desort - pokud je v tabulce sm_stocks_to_articles, tak neni desort
   * @param string $articleId
   * @param \Sportisimo\Erp\Model\Stocks\Entities\Stock $srpStock
   * @return bool|null
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   */
  public function testArticleIsDesort(string $articleId, \Sportisimo\Erp\Model\Stocks\Entities\Stock $srpStock): ?bool
  {
    //pokud je v srpStocku nastaven priznak showDesort, tak ho filtraci zkontroluju..pokud neni showDesort, tak vratim null
    if (!$srpStock->isShowDesort())
    {
      return null;
    }

    //pokud se desort ma zobrazit, tak vysledek vyfiltruju
    $filtration = new ArticleFiltration();
    $filtration->filterByLikeId($articleId);
    $filtration->filterByStockId($srpStock->getId());

    $articleIds = $this->articleList->getList($filtration);

    return count($articleIds) === 0;
  } // testArticleIsDesort()

  /**
   * Nacte srp stock podle naseho stockId.
   * @param int $id
   * @return \Sportisimo\Erp\Model\Stocks\Entities\Stock
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   */
  public function fetchSrpStockByStockId(int $id): \Sportisimo\Erp\Model\Stocks\Entities\Stock
  {
    return new \Sportisimo\Erp\Model\Stocks\Entities\Stock($this->srpDatabase, $id);
  } // fetchSrpStockByStockId()

  /**
   * Pripravi link na zobrazeni detailu varianty artiklu
   * @param \Sportisimo\Erp\Model\Articles\Entities\ArticleVariant $articleVariant
   * @param \Sportisimo\Core\Nette\Model\Data\LinkData $linkData
   * @return \Sportisimo\Core\Nette\Model\Data\LinkData
   */
  public function prepareDetailLinkData(ArticleVariant $articleVariant, LinkData $linkData): LinkData
  {
    $linkData->params[$this->translator->translate('urlParams.articleVariant')] = $articleVariant->getId();
    $linkData->params[$this->translator->translate('urlParams.signal')] = $this->translator->translate('urlParams.articleDetailSrp.signals.showDetail');

    return $linkData;
  }

}
