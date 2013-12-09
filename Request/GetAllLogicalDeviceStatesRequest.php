<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 16:20
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class GetAllLogicalDeviceStatesRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class GetAllLogicalDeviceStatesRequest extends BaseRequest
{
	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'GetAllLogicalDeviceStatesResponse', $useSession = true, $try = 1)
	{
		if($this->smartHome->getConfigVersion() === null)
		{
			$this->smartHome->login();
		}
		$request = $this->getRequest();
		$request['BasedOnConfigVersion'] = $this->smartHome->getConfigVersion();
		return parent::send($expectedResponse, $useSession, $try);
	}
}
