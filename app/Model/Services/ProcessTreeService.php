<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Services;

use Hoa\File\Link\Link;
use Kdyby\Translation\ITranslator;
use Sportisimo\Core\Database\Exceptions\DatabaseException;
use Sportisimo\Core\Database\IDatabase;
use Sportisimo\Core\Exceptions\InvalidArgumentException;
use Sportisimo\Core\Exceptions\LogicException;
use Sportisimo\Core\Exceptions\NoResultException;
use Sportisimo\Core\Exceptions\NotImplementedException as NotImplementedExceptionAlias;
use Sportisimo\Core\Nette\Model\Data\LinkData;
use Sportisimo\ProcessDoc\Model\Data\ApplicationData;
use Sportisimo\ProcessDoc\Model\Data\ProcessData;
use Sportisimo\ProcessDoc\Model\Data\ProcessTreeDefaultData;
use Sportisimo\ProcessDoc\Model\Entities\Applications;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Filters\ProcessFiltration;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessList;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Sorts\ProcessSortation;
use Sportisimo\ProcessDoc\Model\Entities\Processes;
use Sportisimo\ProcessDoc\Model\Entities\ProcessStatuses;
use Sportisimo\ProcessDoc\Presenters\ApplicationListPresenter;
use Sportisimo\ProcessDoc\Presenters\ProcessDetailPresenter;
use Sportisimo\ProcessDoc\Presenters\ProcessTreePresenter;

/**
 * Class ProcessTreeService
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Services
 */
class ProcessTreeService
{
	/**
	 * Databazove spojeni
	 * @var IDatabase
	 */
	private IDatabase $database;

	/**
	 * @var ProcessList
	 */
	private ProcessList $processList;

	/**
	 * @var ITranslator
	 */
	private ITranslator $translator;

	/**
	 * ProcessTreeFacade constructor.
	 * @param IDatabase $database
	 * @param ProcessList $processList
	 * @param ITranslator $translator
	 */
	public function __construct(
	  IDatabase $database,
	  ProcessList $processList,
	  ITranslator $translator
	)
	{
		$this->database = $database;
		$this->processList = $processList;
		$this->translator = $translator;
	} // __construct()

  /**
   * @param \Sportisimo\ProcessDoc\Model\Entities\Processes $process
   * @param int|null $processParentId
   * @return \Sportisimo\ProcessDoc\Model\Data\ProcessData
   */
	private function mapProcessToTreeData(Processes $process, ?int $processParentId = null): ProcessData
	{
		$processTreeData = new ProcessData();
		$processTreeData->name = $process->getName();
		$processTreeData->code = $process->getCode();
		$processTreeData->modul = $process->getModul();
		$processTreeData->presenter = $process->getPresenter();
		$processTreeData->url = $process->getUrl();
		$processTreeData->target = $process->getTarget();

		$processStatusId = $process->getProcessStatusId();
		if($processStatusId !== null)
    {
      $processStatus = new ProcessStatuses($this->database, $processStatusId);
      // todo jinak - idealne mit primo v tabulce css class
      switch($processStatus->getCode())
      {
        case 'červená':
          $processTreeData->processStatusCode = 'red';
          break;
        case 'žlutá':
          $processTreeData->processStatusCode = 'yellow';
          break;
        case 'zelená':
          $processTreeData->processStatusCode = 'green';
          break;
        case 'modrá':
          $processTreeData->processStatusCode = 'blue';
          break;
        default:
          $processTreeData->processStatusCode = null;
      }
    }

		$processTreeData->processDetailLinkData = new LinkData();
		$processTreeData->processDetailLinkData->action = ProcessTreePresenter::ACTION_DEFAULT_NAME;
		$processTreeData->processDetailLinkData->module = '';
		$processTreeData->processDetailLinkData->presenter = 'ProcessTree';
		$processTreeData->processDetailLinkData->params = [
      'processId' => $process->getId(),
      'version' => $process->getCurrentProcessVersionId(),
      'applicationId' => $process->getApplicationId(),
      'processParentId' => $processParentId,
      $this->translator->translate('urlParams.signal') => 'redirectToProcessDetail'
		];

		$processFiltration = new ProcessFiltration();
		$processFiltration->filterByParentProcessId($process->getId());

		try {
			if (!empty($this->processList->getList($processFiltration, null, null)))
			{
				$processTreeData->subProcessTreeLinkData = new LinkData();
				$processTreeData->subProcessTreeLinkData->action = ProcessTreePresenter::ACTION_DEFAULT_NAME;
				$processTreeData->subProcessTreeLinkData->module = '';
				$processTreeData->subProcessTreeLinkData->presenter = 'ProcessTree';
				$processTreeData->subProcessTreeLinkData->params = [
				  'targetProcessParentId' => $process->getId(),
					'processParentId' => $processParentId,
				  'applicationId' => $process->getApplicationId(),
				  $this->translator->translate('urlParams.signal') => 'redirectToProcessTree'
				];
			}
      else
      {
				$processTreeData->subProcessTreeLinkData = null;
			}
		}catch (\Throwable $e) {
			// TODO:// dodelat vyjimku
		}

		return $processTreeData;
	} // mapProcessToTreeData()

	/**
	 * @param int|null $processParentId
	 * @param array $loadedParents
	 * @return array|null
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NoResultException
	 * @throws NotImplementedExceptionAlias
	 */
	public function getBreadcrumb(?int $processParentId, array &$loadedParents = []): ?array // TODO: pouzit misto pole ?ProcessData ?
	{
		$actualProcess = new ProcessFiltration();
		$actualProcess->filterByProcessId($processParentId);
		$dataResult = $this->processList->getList($actualProcess, null, null);

		foreach($dataResult as $processId)
		{
			$process = new Processes($this->database, $processId);

			if(empty($loadedParents))
			{
				$loadedParents[] = $this->mapProcessToTreeData($process);
			}

			if($process->getProcessParentId())
			{
				$parent = new Processes($this->database, $process->getProcessParentId());
				$loadedParents[] = $this->mapProcessToTreeData($parent);
				$this->getBreadcrumb($parent->getId(), $loadedParents);
			}
		}

		if(empty($loadedParents))
		{
			$loadedParents = new ProcessData();
		  $loadedParents[0]->subProcessTreeLinkData = null;
		}

		return array_reverse($loadedParents);
	} // getBreadcrumb()

	/**
	 * @param int|null $applicationId
	 * @param int|null $processParentId
	 * @return ProcessTreeDefaultData
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NoResultException
	 * @throws NotImplementedExceptionAlias
	 */
	public function getDefaultData(?int $applicationId, ?int $processParentId): ProcessTreeDefaultData
	{
		$data = new ProcessTreeDefaultData();
		$application = null;
		$processFiltration = new ProcessFiltration();
		if ($applicationId !== null)
		{
			$application = new Applications($this->database, $applicationId);
			$processFiltration->filterByApplicationId($applicationId)->filterByParentProcessId(null); // filtruji dle id aplikace a chci zobrazit pouze ty processy které mají parent null (nyní je v procesech i aplikace a to je spatne takze nejvyssi je aplikace ale po uprave dat budou mit nulloveho rodice pouze root processy aplikace a nazvy aplikace tam nebudou)
		}
		else
		{
			$process = new Processes($this->database, $processParentId);
			$application = new Applications($this->database, $process->getApplicationId());
			$processFiltration->filterByParentProcessId($processParentId);
			$data->breadcrumbProcessesData = $this->getBreadcrumb($processParentId);
		}

		// backlink
		$data->backlink = new LinkData();
		$data->backlink->module = '';
		$data->backlink->presenter = 'ProcessTree';
		$data->backlink->action = ProcessTreePresenter::ACTION_DEFAULT_NAME;
		$data->backlink->params=[
		$this->translator->translate('urlParams.signal')=>'backlink'
		];


		$data->applicationData = new ApplicationData();
		$data->applicationData->name = $application->getName();
		$data->applicationData->subProcessTreeLinkData = new LinkData();
		$data->applicationData->subProcessTreeLinkData->action = ProcessTreePresenter::ACTION_DEFAULT_NAME;;
		$data->applicationData->subProcessTreeLinkData->module = "";
		$data->applicationData->subProcessTreeLinkData->presenter = "ProcessTree";
		$data->applicationData->subProcessTreeLinkData->params = ['applicationId' => $application->getId()];

		$processSortation = new ProcessSortation();
		$processSortation->sortBySortOrder();

		$processListResult = $this->processList->getList($processFiltration, null, $processSortation);

		foreach($processListResult as $processId)
		{
			try
			{
				$process = new Processes($this->database, $processId);
				$data->processesData[] = $this->mapProcessToTreeData($process, $processParentId);
			}
			catch(NoResultException $e)
			{
				// TODO: dodelat vyjimku pokud je prazdny vysledek
			}
		}
		return $data;

	} // getDefaultData()

}// ProcessTreeService()
