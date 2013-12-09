<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 19:36
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class ReleaseConfigurationLockRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class ReleaseConfigurationLockRequest extends BaseRequest
{
	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'AcknowledgeResponse', $useSession = true, $try = 1)
	{
		return parent::send($expectedResponse, $useSession, $try);
	}
}
