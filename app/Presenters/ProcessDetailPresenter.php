<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Presenters;

use http\Params;
use Nette\Application\AbortException;
use Sportisimo\Core\Database\Exceptions\DatabaseException;
use Sportisimo\Core\Exceptions\InvalidArgumentException;
use Sportisimo\Core\Exceptions\NotImplementedException;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Filters\ProcessFiltration;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Filters\ProcessVersionsFiltration;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessList;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessVersionsList;
use Sportisimo\ProcessDoc\Model\Facades\ProcessDetailFacade;

/**
 * Class ProcessDetailPresenter
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Presenters
 */
final class ProcessDetailPresenter extends AppBasePresenter
{

  /**
   * Nazev presenteru.
   * @var string
   */
  public const PRESENTER_NAME = 'ProcessDetail';

  /**
   * @var ProcessDetailFacade
   */
	private ProcessDetailFacade $processDetailFacade;

  /**
   * @var int|null
   */
	private ?int $processId;

  /**
   * @var int|null
   */
	private ?int $processVersionId;

	/**
	 * @var mixed
	 */
	private ProcessList $processList;

	/**
	 * @var mixed
	 */
	private ProcessVersionsList $processVersionList;

  /**
   * ProcessDetailPresenter constructor.
   * @param \Sportisimo\ProcessDoc\Model\Facades\ProcessDetailFacade $processDetailFacade
   * @param \Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessList $processList
   * @param \Sportisimo\ProcessDoc\Model\Entities\Lists\ProcessVersionsList $processVersionList
   */
  public function __construct(
    ProcessDetailFacade $processDetailFacade,
		ProcessList $processList,
		ProcessVersionsList $processVersionList
  )
	{
    parent::__construct();
    $this->processDetailFacade = $processDetailFacade;
    $this->processList = $processList;
    $this->processVersionList = $processVersionList;
  } // __construct()

	/**
	 * Funkce na zpracovani zakladnich parametru.
	 * @throws AbortException
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws NotImplementedException
	 */
  public function processDetailBaseParams(): void
  {
    $processId = $this->getIntParam('processId');
		$processVersionId = $this->getIntParam('version');

		$this->processId = $processId;
		$this->processVersionId = $processVersionId;

		if( (!empty($processId) || $processId !== 0) && (!empty($processVersionId) || $processVersionId !== 0) )
		{
//			bdump(1);

			$processListIds = $this->processList->getList(null,null,null);

			if(in_array($processId, $processListIds))
			{
//				bdump(2);
				$this->processId = $processId;
				$processVersionFiltration = new ProcessVersionsFiltration();
				$processVersionFiltration->filterByProcessId($processId);
				$processVersionListIds = $this->processVersionList->getList($processVersionFiltration,null,null);

				if(in_array($processVersionId, $processVersionListIds, TRUE))
				{
					//bdump(3);
					$this->processVersionId = $processVersionId;
				}
				else
				{
				//	bdump(4);
					$this->flashMessage->error('Vložen chybný parametr verze procesu');
					$this->redirect("ApplicationList:".ApplicationListPresenter::ACTION_DEFAULT_NAME);
				}
			}
			else
			{
				//	bdump(5);
				$this->flashMessage->error('Vložen chybný parametr identifikace procesu');
				$this->redirect('ApplicationList:'.ApplicationListPresenter::ACTION_DEFAULT_NAME);
			}
		}
		else
		{
			//bdump(6);
			$this->flashMessage->error('Vloženy chybné parametry');
			$this->redirect("ApplicationList:".ApplicationListPresenter::ACTION_DEFAULT_NAME);
		}

  } // processDetailBaseParams()

  /**
   * @throws \Nette\Application\AbortException
   * @throws \Sportisimo\Core\Database\Exceptions\DatabaseException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   * @throws \Sportisimo\Core\Exceptions\NotImplementedException
   */
  public function actionDefault(): void
  {
		try {
			$this->processDetailBaseParams();
		}catch (AbortException $e){
			throw $e;
		}catch (\Throwable $e) {
			throw $e;
		}
	} // actionDefault()

  /**
   * Funkce pro vykresleni vychozi akce presenteru.
   * @throws \Nette\Application\AbortException
   */
  public function renderDefault(): void
  {
  	$data = array();

		try {
			$data = $this->processDetailFacade->prepareDefaultData($this->processId, $this->processVersionId);
		}catch (AbortException $e){
			throw $e;
		}catch (\Throwable $e) {
			// TODO: dodelat vyjimky
		}

		$this->getTemplate()->setParameters([
    'data' => $data,
    ]);

		bdump($data); // TODO:// bdump
  } // renderDefault()

} // ProcessDetailPresenter
