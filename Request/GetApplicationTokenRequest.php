<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 16:27
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class GetApplicationTokenRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class GetApplicationTokenRequest extends BaseRequest
{
	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'GetApplicationTokenResponse', $useSession = true, $try = 1)
	{
		return parent::send($expectedResponse, $useSession, $try);
	}
}
