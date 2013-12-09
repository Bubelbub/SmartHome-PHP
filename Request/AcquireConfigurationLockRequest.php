<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 19:31
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class AcquireConfigurationLockRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class AcquireConfigurationLockRequest extends BaseRequest
{
	/**
	 * @var bool
	 */
	private $overrideLock = false;

	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'AcknowledgeResponse', $useSession = true, $try = 1)
	{
		$request = $this->getRequest();
		$request['OverrideLock'] = $this->getOverrideLock();
		return parent::send($expectedResponse, $useSession, $try);
	}

	/**
	 * @param boolean $overrideLock
	 */
	public function setOverrideLock($overrideLock)
	{
		$this->overrideLock = $overrideLock;
	}

	/**
	 * @return boolean
	 */
	public function getOverrideLock()
	{
		return $this->overrideLock;
	}
}
