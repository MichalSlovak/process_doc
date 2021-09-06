<?php declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Presenters;

use Sportisimo\Core\Exceptions\NotImplementedException;
use Sportisimo\Core\Nette\Control\BasePresenter;
use Sportisimo\Core\Nette\Model\Services\RequestResponseService;

/**
 * Class ProcessTreePresenter
 * Copyright (c) 2020 Sportisimo s.r.o.
 * @package Sportisimo\ProcessDoc\Presenters;
 */
abstract class AppBasePresenter extends BasePresenter
{
	/**
	 * @var RequestResponseService
	 */
	protected $requestResponseService;

	/**
	 * @param RequestResponseService $requestResponseService
	 */
	public function injectRequestResponse(RequestResponseService $requestResponseService )
  {
		$this->requestResponseService=$requestResponseService;
	} // injectRequestResponse()

  /**
   * @throws \Nette\Application\AbortException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   */
	protected function startup()
	{
		parent::startup(); // TODO: Change the autogenerated stub
		if($this->checkSignal('backlink'))
		{
			$this->handleRedirectToBack();
		}
	} // startup()

  /**
   * @throws \Nette\Application\AbortException
   * @throws \Sportisimo\Core\Exceptions\InvalidArgumentException
   */
	protected function handleRedirectToBack()
	{
		$this->requestResponseService->restoreRequest();
	} // handleRedirectToBack()

	/**
	 * @inheritDoc
	 * @throws NotImplementedException
	 */
	public function getAuthorizationModuleName(): ?string
	{
		// TODO: Implement getAuthorizationModuleName() method.
		throw new NotImplementedException();
	} // getAuthorizationModuleName()

	/**
	 * @inheritDoc
	 * @throws NotImplementedException
	 */
	public function getAuthorizationPresenterName(): string
	{
		// TODO: Implement getAuthorizationPresenterName() method.
		throw new NotImplementedException();
	} // getAuthorizationPresenterName()

	/**
	 * @inheritDoc
	 * @throws NotImplementedException
	 */
	public function getAuthorizationActionName(): string
	{
		// TODO: Implement getAuthorizationActionName() method.
		throw new NotImplementedException();
	} // getAuthorizationActionName()

	/**
	 * @param string $signalParamValue
	 * @return bool
	 */
  protected function checkSignal(string $signalParamValue): bool
  {
		$signal = $this->getStringParam($this->translator->translate("urlParams.signal"));

		if($signal === $signalParamValue)
		{
			return true;
		}

		return false;

  } // checkSignal()

} // AppBasePresenter
