<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 16:40
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class GetMessageListRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class GetMessageListRequest extends BaseRequest
{
	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'MessageListResponse', $useSession = true, $try = 1)
	{
		return parent::send($expectedResponse, $useSession, $try);
	}
}
