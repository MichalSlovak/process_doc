<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Presenters;

use Nette\Application\AbortException;
use Sportisimo\Core\Database\Exceptions\DatabaseException;
use Sportisimo\Core\Exceptions\InvalidArgumentException;
use Sportisimo\Core\Exceptions\NotImplementedException;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ApplicationList;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Filters\ProcessFiltration;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessList;
use Sportisimo\ProcessDoc\Model\Facades\ProcessTreeFacade;

/**
 * Class ProcessTreePresenter
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Presenters
 */
final class ProcessTreePresenter extends AppBasePresenter
{

  /**
   * Nazev presenteru.
   * @var string
   */
  public const PRESENTER_NAME = 'ProcessTree';

  /**
	 * Nacteni fasady
   * @var ProcessTreeFacade
   */
	private ProcessTreeFacade $processTreeFacade;

  /**
   * ID rodice procesu
   * @var int|null
   */
	private ?int $processParentId;

  /**
	 * Id aplikace pro kterou chceme zobrazit procesy
   * @var int|null
   */
	private ?int $applicationId;

	/**
	 * @var mixed
	 */
	private ApplicationList $applicationList;

	/**
	 * @var mixed
	 */
	private ProcessList $processList;

  /**
   * ProcessTreePresenter constructor.
   * @param \Sportisimo\ProcessDoc\Model\Facades\ProcessTreeFacade $processTreeFacade
   * @param \Sportisimo\ProcessDoc\Model\Entities\Lists\ApplicationList $applicationList
   * @param \Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessList $processList
   */
	public function __construct(
    ProcessTreeFacade $processTreeFacade,
		ApplicationList $applicationList,
		ProcessList $processList
  )
	{
    parent::__construct();
    $this->processTreeFacade = $processTreeFacade;
		$this->applicationList = $applicationList;
		$this->processList = $processList;
  } // __construct()

	/**
	 * Funkce na zpracovani zakladnich parametru. TODO: Doresim popis funkce
	 * @throws AbortException
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws NotImplementedException
	 */
	public function processBaseParams(): void
	{
		$applicationId = $this->getIntParam('applicationId'); // id aplikace pro ktere chci zobrazit rodice
		$processParentId = $this->getIntParam('processParentId'); // processParentId definuje zanoreni


		if(!empty($applicationId) AND empty($processParentId)) // kdyz JE vlozeno application id a zaroven NENI $processParentId
		{
			$applicationListIds = $this->applicationList->getList(); // vytahnu si ID vsech aplikaci

			if(in_array($applicationId, $applicationListIds)) // kontrola zda je vlozene ID v parametru obsazeno v nasi db
			{
				$this->applicationId = $applicationId;
			}
			else
			{
				$this->flashMessage->error('Vložen chybné id aplikace');
				$this->redirect("ApplicationList:".ApplicationListPresenter::ACTION_DEFAULT_NAME);
			}
		}

		if(empty($applicationId) AND !empty($processParentId))
		{
			$processFiltration = new ProcessFiltration();
			$processFiltration->filterByProcessId($processParentId);
			$processListIds = $this->processList->getList($processFiltration,null,null);

			if(in_array($processParentId, $processListIds)){
				$this->processParentId = $processParentId;
			}
			else
			{
				$this->flashMessage->error('Vložen chybné ProcessParentID');
				$this->redirect("ApplicationList:".ApplicationListPresenter::ACTION_DEFAULT_NAME);
			}
		}

		if(empty($applicationId) AND empty($processParentId))
		{
			$this->flashMessage->error('Vložen chybný parametr');
			$this->redirect("ApplicationList:".ApplicationListPresenter::ACTION_DEFAULT_NAME);
		}

		if ($processParentId !== null)
		{
			$this->processParentId = $processParentId;
			$this->applicationId = $applicationId;

			if($this->applicationId !== null)
			{
				$params = $this->getParameters();
				unset($params['applicationId']);
				$this->redirect('this',$params);
			}
		}
		else
		{
			$this->processParentId = null;
		}
	}  // processBaseParams()

  /**
   * @throws \Nette\Application\AbortException
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\NotImplementedException
   */
	public function actionDefault(): void
  {
     $this->processBaseParams();

			if($this->checkSignal("redirectToProcessTree"))
			{
				$processParentId = $this->getIntParam("targetProcessParentId");
				$this->handleRedirectToProcessTree($processParentId);
			}

		if($this->checkSignal("redirectToProcessDetail"))
		{
			$processId = $this->getIntParam("processId");
			$version = $this->getIntParam("version");
			$this->handleRedirectToProcessDetail($processId,$version);
		}
  } // actionDefault()

  /**
   * @param int $processParentId
   * @throws \Nette\Application\AbortException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   */
	private function handleRedirectToProcessTree(int $processParentId): void
	{
		$this->requestResponseService->storeRequest(true,'+1 hour',['targetProcessParentId']);
		$this->redirect("ProcessTree:".ProcessTreePresenter::ACTION_DEFAULT_NAME, [ "processParentId" => $processParentId] );
	} // handleRedirectToProcessTree()

  /**
   * @param int $processId
   * @param int $version
   * @throws \Nette\Application\AbortException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   */
	private function handleRedirectToProcessDetail(int $processId, int $version): void
	{
		$this->requestResponseService->storeRequest(true,'+1 hour',['processId', 'version']);
		$this->redirect("ProcessDetail:".ProcessDetailPresenter::ACTION_DEFAULT_NAME, [ "processId" => $processId, "version" => $version ] );
	} // handleRedirectToProcessDetail()

  /**
   * Funkce pro vykresleni vychozi akce presenteru.
   */
  public function renderDefault(): void
  {
		$data = array(); // inicializace promene data

		try {
			$data = $this->processTreeFacade->prepareDefaultData($this->applicationId, $this->processParentId);
		}catch (\Throwable $e) {
		//	$this->forward(""); // TODO: dodelat vyjimku
		}

		$this->getTemplate()->setParameters([
    'data' => $data,
    ]);

    bdump($data); // TODO: bdump
  } // renderDefault()

} // ProcessTreePresenter
