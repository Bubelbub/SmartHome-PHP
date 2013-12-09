<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 16:37
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class GetAllPhysicalDeviceStatesRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class GetAllPhysicalDeviceStatesRequest extends BaseRequest
{
	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'GetAllPhysicalDeviceStatesResponse', $useSession = true, $try = 1)
	{
		return parent::send($expectedResponse, $useSession, $try);
	}
}
