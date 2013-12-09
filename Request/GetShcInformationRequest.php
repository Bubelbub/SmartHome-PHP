<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 16:04
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class GetShcInformationRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class GetShcInformationRequest extends BaseRequest
{
	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'ShcInformationResponse', $useSession = true, $try = 1)
	{
		return parent::send($expectedResponse, $useSession, $try);
	}
}
