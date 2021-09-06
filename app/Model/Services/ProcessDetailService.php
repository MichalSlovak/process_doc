<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Services;

use Codeception\PHPUnit\ResultPrinter\UI;
use Kdyby\Translation\ITranslator;
use Nette\Application\UI\Component;
use Nette\Application\UI\Presenter;
use Prophecy\Doubler\ClassPatch\ThrowablePatch;
use Sportisimo\Core\Database\Exceptions\DatabaseException;
use Sportisimo\Core\Database\IDatabase;
use Sportisimo\Core\Exceptions\InvalidArgumentException;
use Sportisimo\Core\Exceptions\LogicException;
use Sportisimo\Core\Exceptions\NoResultException;
use Sportisimo\Core\Exceptions\NotImplementedException as NotImplementedExceptionAlias;
use Sportisimo\Core\Nette\Model\Data\LinkData;
use Sportisimo\ProcessDoc\Model\Data\ApplicationData;
use Sportisimo\ProcessDoc\Model\Data\ProcessData;
use Sportisimo\ProcessDoc\Model\Data\ProcessDetailDefaultData;
use Sportisimo\ProcessDoc\Model\Data\ProcessStepData;
use Sportisimo\ProcessDoc\Model\Data\ProcessStepScreenshotsData;
use Sportisimo\ProcessDoc\Model\Data\ProcessVersionsData;
use Sportisimo\ProcessDoc\Model\Entities\Applications;
use Sportisimo\ProcessDoc\Model\Entities\Authors;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Filters\ProcessStepFiltration;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Filters\ProcessStepScreenshotFiltration;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Filters\ProcessVersionsFiltration;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessDetailList;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessStepList;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessStepScreenshotList;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessStepTypesList;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessVersionsList;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Sorts\ProcessDetailSortation;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Sorts\ProcessStepTypesSortation;
use Sportisimo\ProcessDoc\Model\Entities\Processes;
use Sportisimo\ProcessDoc\Model\Entities\ProcessSteps;
use Sportisimo\ProcessDoc\Model\Entities\ProcessStepScreenshots;
use Sportisimo\ProcessDoc\Model\Entities\ProcessStepTypes;
use Sportisimo\ProcessDoc\Model\Entities\ProcessVersions;
use Sportisimo\ProcessDoc\Model\Facades\ProcessTreeFacade;
use Sportisimo\ProcessDoc\Presenters\ApplicationListPresenter;
use Sportisimo\ProcessDoc\Presenters\ProcessDetailPresenter;
use Sportisimo\ProcessDoc\Presenters\ProcessTreePresenter;

/**
 * Class ProcessDetailService
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Services
 */
class ProcessDetailService extends Presenter
{
	/**
	 * @var ProcessTreeService
	 */
	private ProcessTreeService $processTreeService;

	/**
	 * @var ProcessStepList
	 */
	private ProcessStepList $processStepList;

	/**
	 * @var ProcessTreeFacade
	 */
	private ProcessTreeFacade $processTreeFacade;

	/**
	 * @var ProcessDetailList
	 */
	private ProcessDetailList $processDetailList;

	/**
	 * @var ProcessStepTypesList
	 */
	private ProcessStepTypesList $processStepTypesList;

	/**
	 * @var ProcessStepScreenshotList
	 */
	private ProcessStepScreenshotList $processStepScreenshotList;

	/**
	 * @var ProcessVersionsList
	 */
	private ProcessVersionsList $processVersionsList;

	/**
	 * @var ITranslator
	 */
	private ITranslator $translator;

	/**
	 * Databazove spojeni
	 * @var IDatabase
	 */
	private IDatabase $database;

	/**
	 * ProcessDetailFacade constructor.
	 * @param IDatabase $database
	 * @param ProcessDetailList $processDetailList
	 * @param ProcessStepList $processStepList
	 * @param ProcessStepTypesList $processStepTypesList
	 * @param ProcessStepScreenshotList $processStepScreenshotList
	 * @param ProcessTreeFacade $processTreeFacade
	 * @param ProcessVersionsList $processVersionsList
	 * @param ProcessTreeService $processTreeService
	 * @param ITranslator $translator
	 */
	public function __construct(
	IDatabase $database,
	ProcessDetailList $processDetailList,
	ProcessStepList $processStepList,
	ProcessStepTypesList $processStepTypesList,
	ProcessStepScreenshotList $processStepScreenshotList,
	ProcessTreeFacade $processTreeFacade,
	ProcessVersionsList $processVersionsList,
	ProcessTreeService $processTreeService,
  ITranslator $translator
	)
	{
		$this->database = $database;
		$this->processDetailList = $processDetailList;
		$this->processTreeFacade = $processTreeFacade;
		$this->processStepList = $processStepList;
		$this->processStepTypesList = $processStepTypesList;
		$this->processStepScreenshotList = $processStepScreenshotList;
		$this->processVersionsList = $processVersionsList;
		$this->processTreeService = $processTreeService;
		$this->translator = $translator;
	} // __construct()

	/**
	 * @param int $processStepId
	 * @param array $processStepTypesData
	 * @return ProcessStepData
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NoResultException
	 * @throws NotImplementedExceptionAlias
	 */
	protected function mapStepsToTreeData(int $processStepId, array $processStepTypesData): ProcessStepData
	{

		$processStep = new ProcessSteps($this->database, $processStepId);
		$processStepType = new ProcessStepTypes($this->database, $processStep->getProcessStepTypeId());
		$processStepData = new ProcessStepData();

		$processStepData->name = $processStep->getName();
		$processStepData->sortOrder = $processStep->getSortOrder();
		$processStepData->stepType = $processStep->getProcessStepTypeId();
		$processStepData->stepSubtype = $processStep->getProcessStepSubtypeId();
		$processStepData->stepTypeName = $processStepType->getCode();
		$processStepData->userDescription = $processStep->getUserDescription();
		$processStepData->technicalDescriptionOrigin = $processStep->getTechnicalDescriptionOrigin();
		$processStepData->technicalDescription = $processStep->getTechnicalDescription();
		$processStepData->idea = $processStep->getIdeas();

		$processStepData->id = $processStep->getId();
		$processStepData->nextProcessId = $processStep->getNextProcessId();
		$processStepData->nextProcessStepId = $processStep->getNextProcessStepId();

		if(!empty($processStepData->nextProcessStepId))
    {
      $nextProcessStep = new ProcessSteps($this->database, $processStepData->nextProcessStepId);

      $processStepData->nextProcessStepName = $nextProcessStep->getName();
    }

		if(!empty($processStepData->nextProcessId))
    {
      $nextProcess = new Processes($this->database, $processStep->getNextProcessId());

      $processStepData->nextProcessName = $nextProcess->getName();

      $processStepData->nextProcessLinkData = new LinkData();
      $processStepData->nextProcessLinkData->module = "";
      $processStepData->nextProcessLinkData->presenter = ProcessDetailPresenter::PRESENTER_NAME;
      $processStepData->nextProcessLinkData->action = ProcessDetailPresenter::ACTION_DEFAULT_NAME;
      $processStepData->nextProcessLinkData->params = [
        'processId' => $nextProcess->getId(),
        'version' => $nextProcess->getCurrentProcessVersionId()
      ];
    }

		$processStepFiltration = new ProcessStepFiltration();
		$processStepFiltration->filterByParentProcessStepId($processStepId);

		$processStepScreenshotFiltration = new ProcessStepScreenshotFiltration();
		$processStepScreenshotFiltration->filterByProcessStepId($processStepId);

		$processStepSortation = new ProcessDetailSortation();
		$processStepSortation->sortBySortOrder();

		$subProcessStepsIds = $this->processStepList->getList($processStepFiltration, null, $processStepSortation);
		$processStepScreenshots = $this->processStepScreenshotList->getList($processStepScreenshotFiltration, null, null);

		$processStepData->processStepScreenshotData = $this->MapArrayToScreenshotsData($processStepScreenshots);

		$processStepData->children = $this->getSortedProcessStepsData($processStepTypesData, $subProcessStepsIds);

		if($processStepData->children == null)
		{
			$processStepData->children = array(); // TODO: doosetrit lepe situaci kdy je prazdna promena children
		}

		return $processStepData;
	} // mapStepsToTreeData()

	/**
	 * @param array $processStepTypesData
	 * @param array|null $processDetailListResult
	 * @return array
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NoResultException
	 * @throws NotImplementedExceptionAlias
	 */
	private function getSortedProcessStepsData(array $processStepTypesData, ?array $processDetailListResult ): array
	{
		$data = [];
//			Správné pořadí
//			1. C - Vstupní podmínky
//			2. P - Kroky procesu
//			3. A - Alternativní toky

		foreach($processDetailListResult as $specificationId)
		{
			$childProcessStepData = $this->mapStepsToTreeData($specificationId, $processStepTypesData);

			$data[$childProcessStepData->stepType][] = $childProcessStepData;
		}

		$newData = [];

		foreach($processStepTypesData as $k => $v )
		{
			if(isset($data[$k]))
			{
				$newData[$k] = $data[$k];
			}
		}

		return $newData;
	} // getSortedProcessStepsData()

	/**
	 * @param array|null $processDetailListResult
	 * @return array|null
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NoResultException
	 * @throws NotImplementedExceptionAlias
	 */
	protected function getProcessStepsData(?array $processDetailListResult): ?array
	{
		$data = [];

		foreach ($processDetailListResult as $specificationId)
		{
			$childProcessStepData = $this->mapStepsToTreeData($specificationId);
			$data[$childProcessStepData->stepType][] = $childProcessStepData;
		}

		if(empty($data))
		{
			$data = null;
		}

		return $data;
	}// getProcessStepsData()

	/**
	 * @param array|null $processStepScreenshots
	 * @return array|null
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NoResultException
	 */
	private function MapArrayToScreenshotsData(?array $processStepScreenshots): ?array
	{
		$data = [];

		foreach($processStepScreenshots as $processStepScreenshot)
		{
			$processStepScreenshotData = new ProcessStepScreenshots($this->database,$processStepScreenshot);
			$processStepScreenshotsData = new ProcessStepScreenshotsData();
			$processStepScreenshotsData->printScreenPath = $processStepScreenshotData->getFilePath();
			$data[] = $processStepScreenshotsData;
		}

		if(empty($data))
		{
			$data = null;
		}

		return $data;
	} // MapArrayToScreenshotsData()

	/**
	 * @param int $processId
	 * @param int $actualProcessVersionId
	 * @return array
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NoResultException
	 * @throws NotImplementedExceptionAlias
	 */
	private function getProcessVersions( int $processId, int $actualProcessVersionId): array
	{
		$data=[];
		$versionFilter = new ProcessVersionsFiltration();
		$process = new Processes($this->database, $processId);
		$versionFilter->filterByProcessId($processId);

		$versionListResult = $this->processVersionsList->getList($versionFilter, null,null);

		foreach($versionListResult as $processVersionId)
		{
			$processVersionData = new ProcessVersions($this->database,$processVersionId);
			$processVersionsData = new ProcessVersionsData();
			$author = new Authors($this->database,$processVersionData->getAuthorId());

			$processVersionsData->request = ($processVersionData->getRequest()) ? $processVersionData->getRequest() : '';
			$processVersionsData->dateCreated = $processVersionData->getDateCreated();
			$processVersionsData->author = $author->getAuthor();
			$processVersionsData->displayed = $actualProcessVersionId === $processVersionData->getId();

			$processVersionsData->processStepNextVersionLinkData = new LinkData();
			$processVersionsData->processStepNextVersionLinkData->action = ProcessDetailPresenter::ACTION_DEFAULT_NAME;
			$processVersionsData->processStepNextVersionLinkData->module = "";
			$processVersionsData->processStepNextVersionLinkData->presenter = "ProcessDetail";
			$processVersionsData->processStepNextVersionLinkData->params = ['processId' => $processId, 'version' => $processVersionData->getId()];
			($process->getCurrentProcessVersionId() == $processVersionId)?$processVersionsData->default = true : $processVersionsData->default = false; // pokud se jedna o aktualni process nastav default na true

			$data[] = $processVersionsData;
		}

		return $data;

	} // getProcessVersions()

  /**
   * @param int $processId
   * @param int $processVersionId
   * @return \Sportisimo\ProcessDoc\Model\Data\ProcessDetailDefaultData
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\LogicException
   * @throws \Sportisimo\Core\Exceptions\NoResultException
   * @throws \Sportisimo\Core\Exceptions\NotImplementedException
   */
	public function getDefaultData(int $processId, int $processVersionId): ProcessDetailDefaultData
	{
		$data = new ProcessDetailDefaultData();

		$process = array();
		$application = array();
		$processStepTypesListResult = array();
		$processDetailListResult = array();
		$type = array();
		$i = 0;

		try {
			$data->breadcrumbProcessData = $this->processTreeService->getBreadcrumb($processId);
		}catch (\Throwable $e) {
			// TODO:// dodelat vyjimku,
		} // Nacteni drobeckovky pro ProcessData

		try {
			$process = new Processes($this->database, $processId);
		}catch (\Throwable $e) {
			// TODO:// dodelat vyjimku,
		} // Tabulka procesů

		try {
			$application = new Applications($this->database, $process->getApplicationId());
		}catch (\Throwable $e) {
			// TODO:// dodelat vyjimku,
		} // Tabulka Aplikaci v systemu
		$data->applicationData = new ApplicationData();
		$data->applicationData->name = $application->getName();
		$data->applicationData->subProcessTreeLinkData = new LinkData();
		$data->applicationData->subProcessTreeLinkData->action = ProcessTreePresenter::ACTION_DEFAULT_NAME;;
		$data->applicationData->subProcessTreeLinkData->module = "";
		$data->applicationData->subProcessTreeLinkData->presenter = ProcessTreePresenter::PRESENTER_NAME;
		$data->applicationData->subProcessTreeLinkData->params = ['applicationId' => $application->getId()];

		// backlink
		$data->backlink = new LinkData();
		$data->backlink->module = '';
		$data->backlink->presenter = ProcessTreePresenter::PRESENTER_NAME;
		$data->backlink->action = ProcessTreePresenter::ACTION_DEFAULT_NAME;
		$data->backlink->params=[
		$this->translator->translate('urlParams.signal')=>'backlink'
		];

		$data->processDetailData = new ProcessData();
		$data->processDetailData->id = $process->getId();
		$data->processDetailData->name = $process->getName();
		$data->processDetailData->code = $process->getCode();
		$data->processDetailData->modul = $process->getModul();
		$data->processDetailData->presenter = $process->getPresenter();
		$data->processDetailData->action = $process->getAction();
		$data->processDetailData->url = $process->getUrl();
		$data->processDetailData->target = $process->getTarget();

		 // nazev procesu ve kterem se nachazime
		try {
			$data->processVersions = $this->getProcessVersions($processId, $processVersionId);
		}catch (\Throwable $e) {
			// TODO:// dodelat vyjimku
		}

		if(empty($processVersionId))
		{
			$processVersionId = $process->getCurrentProcessVersionId();
		}

		$processStepFiltration = new ProcessStepFiltration();
		$processStepFiltration->filterByProcessId($processId)->filterByParentProcessStepId(null)->filterByProcessVersionId($processVersionId); // Potrebuji rootove kroky procesu tudiz pouziji filtr filterByParentProcessStepId s atributem null a to nam zajisti vsechny rootove kroky ktery maji dany proces a zaroven maji null parenta

		$processDetailSortation = new ProcessDetailSortation();
		$processDetailSortation->sortBySortOrder();
		//bdump($processDetailSortation);
		try {
			$processDetailListResult = $this->processStepList->getList($processStepFiltration, null, $processDetailSortation);
			//bdump($processDetailListResult);
		}catch (\Throwable $e) {
			// TODO:// dodelat vyjimku,
		}

		try {
			$processStepTypesSortation = new ProcessStepTypesSortation();
			$processStepTypesSortation->sortByRelativeOrder(); // razeni typů uzlů
			$processStepTypesListResult = $this->processStepTypesList->getList(null, null, $processStepTypesSortation);

		}catch (\Throwable $e) {
			// TODO:// dodelat vyjimku,
		}

		foreach($processStepTypesListResult as $processStepType){
			try {
				$type = new ProcessStepTypes($this->database, $processStepType);
			}catch (\Throwable $e) {
				// TODO:// dodelat vyjimku,
			}

			if(!empty($type->getType())) // pokud je prazdny typ nezapise se, pokud se tedy bude pridavat novy uzel MUSI mit typ jinak pres tuto podminku neprojde
			{
			//	$data->processStepTypesData[$type->getId()] = $type->getName();
				$data->processStepTypesData[$type->getId()] = [
          'name' => $type->getCode(),
          'type' => $type->getType(),
          'sortOrder' => $i++,
				];
			}
		}

		$data->processStepsData = $this->getSortedProcessStepsData($data->processStepTypesData, $processDetailListResult);

		return $data;
	} // getDefaultData()

}// ProcessDetailService
