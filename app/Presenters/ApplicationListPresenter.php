<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Presenters;

use Nette\Application\AbortException;
use Sportisimo\Core\Database\IDatabase;
use Sportisimo\Core\Exceptions\InvalidArgumentException;
use Sportisimo\Core\Log\ILogger;
use Sportisimo\ProcessDoc\Model\Facades\ApplicationListFacade;

/**
 * Class ApplicationListPresenter
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Presenters
 */
final class ApplicationListPresenter extends AppBasePresenter
{

  /**
   * Nazev presenteru.
   * @var string
   */
  public const PRESENTER_NAME = 'ApplicationList';

  /** Databazove spojeni
   * @var IDatabase
   */
	private IDatabase $database;

  /** Facade ApplicationList
   * @var ApplicationListFacade
   */
	private ApplicationListFacade $applicationListFacade;

	/**
	 * @var ILogger
	 */
	private ILogger $logger;


  /**
   * ApplicationListPresenter constructor.
   * @param IDatabase $database
   * @param ApplicationListFacade $applicationListFacade
   * @param ILogger $logger
   */
  public function __construct(
    IDatabase $database,
    ApplicationListFacade $applicationListFacade,
		Ilogger $logger
  )
	{
    parent::__construct();
    $this->database = $database;
    $this->applicationListFacade = $applicationListFacade;
		$this->logger = $logger;
	} // __construct()

  /**
   * Funkce pro vykresleni vychozi akce presenteru
   * @inheritDoc
   */
  public function renderDefault(): void
  {
  	$data = array();

		try {
			$data = $this->applicationListFacade->prepareDefaultData();
		}catch (\Throwable $e) {

			$this->logger->debugException($e);
			$this->flashMessage->error('Kritická chyba, kontaktuj podporu!');
			//$this->forward('ApplicationList::default');
		}

		$this->getTemplate()->setParameters([
    'data' => $data,
    ]);

   bdump($data); // TODO:// bdump
  } // renderDefault()

  /**
   * @throws AbortException
   * @throws InvalidArgumentException
   */
	public function actionDefault(): void
	{
    if($this->checkSignal("redirectToApplicationList"))
    {
      $applicationId = $this->getIntParam("applicationId");
      $this->handleRedirectToApplicationList($applicationId);
    }
	} // actionDefault()

  /**
   * @param int $applicationId
   * @throws AbortException
   * @throws InvalidArgumentException
   */
	private function handleRedirectToApplicationList(int $applicationId)
	{
//		$this->storeRequest();
		$this->requestResponseService->storeRequest(true,'+1 hour',['applicationId']);
		$this->redirect("ProcessTree:".ProcessTreePresenter::ACTION_DEFAULT_NAME, [ "applicationId" => $applicationId] );
//

  } // handleRedirectToApplicationList()

} // ApplicationListPresenter
