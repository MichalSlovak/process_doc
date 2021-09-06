<?php declare(strict_types = 1);

namespace Sportisimo\ProcessDoc\Model\Services;

use Kdyby\Translation\ITranslator;
use Sportisimo\Core\Database\Exceptions\DatabaseException;
use Sportisimo\Core\Database\IDatabase;
use Sportisimo\Core\Exceptions\InvalidArgumentException;
use Sportisimo\Core\Exceptions\LogicException;
use Sportisimo\Core\Exceptions\NoResultException;
use Sportisimo\Core\Exceptions\NotImplementedException;
use Sportisimo\Core\Log\ILogger;
use Sportisimo\Core\Nette\Model\Data\LinkData;
use Sportisimo\ProcessDoc\Model\Data\ApplicationData;
use Sportisimo\ProcessDoc\Model\Data\ApplicationListDefaultData;
use Sportisimo\ProcessDoc\Model\Entities\Applications;
use Sportisimo\ProcessDoc\Model\Entities\Lists\ApplicationList;
use Sportisimo\ProcessDoc\Model\Entities\Lists\Sorts\ApplicationSortation;
use Sportisimo\ProcessDoc\Presenters\ApplicationListPresenter;
use Sportisimo\ProcessDoc\Presenters\ProcessTreePresenter;

/**
 * Class ApplicationListService
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Model\Services
 */
class ApplicationListService
{
	/**
	 * Databazove spojeni
	 * @var IDatabase
	 */
	private IDatabase $database;

	/**
	 * @var ApplicationList
	 */
	private ApplicationList $applicationList;

	/**
	 * @var ILogger
	 */
	private ILogger $logger;

	/**
	 * @var ITranslator
	 */
	private ITranslator $translator;

  /**
   * ApplicationListService constructor.
   * @param \Sportisimo\Core\Database\IDatabase $database
   * @param \Sportisimo\ProcessDoc\Model\Entities\Lists\ApplicationList $applicationList
   * @param \Sportisimo\Core\Log\ILogger $logger
   * @param \Kdyby\Translation\ITranslator $translator
   */
	public function __construct(
	IDatabase $database,
	ApplicationList $applicationList,
	ILogger $logger,
	ITranslator $translator
	)
	{
		$this->database = $database;
		$this->applicationList = $applicationList;
		$this->logger = $logger;
		$this->translator = $translator;
	} // __construct()

	/**
	 * @return ApplicationListDefaultData
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 * @throws NotImplementedException
	 */
	public function prepareDataForDefaultData()
  {

		$data = new ApplicationListDefaultData();

		$applicationSortation = new ApplicationSortation();
		$applicationSortation->sortByName();

		$applicationListResult = $this->applicationList->getList(null, null, $applicationSortation); // nacteni vsech aplikaci

		foreach($applicationListResult as $applicationId) // prochazeni aplikaci jednu po druhe
		{
			try
			{
				$application = new Applications($this->database, $applicationId);

				$applicationData = new ApplicationData();
				$applicationData->name = $application->getName();

				$applicationData->subProcessTreeLinkData = new LinkData();
				$applicationData->subProcessTreeLinkData->action = ApplicationListPresenter::ACTION_DEFAULT_NAME;
				$applicationData->subProcessTreeLinkData->module = "";
				$applicationData->subProcessTreeLinkData->presenter = "ApplicationList";
				$applicationData->subProcessTreeLinkData->params = [
				'applicationId' => $application->getId(),
				$this->translator->translate('urlParams.signal') => "redirectToApplicationList"]; // odkaz na ProcessTreePresenter a predani parametru id aplikace

				$data->applicationsData[] = $applicationData;
			}
			catch(NoResultException $e) // umyslne potlaceno, zalogovani by teoreticky nemelo nikdy nastat
			{
				$this->logger->debugException($e);
			}
		}
		return $data;

	} // prepareDataForDefaultData()

} // ApplicationListService()
