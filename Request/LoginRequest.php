<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 13:46
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class LoginRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class LoginRequest extends BaseRequest
{
	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'LoginResponse', $useSession = false, $try = 1)
	{
		$this->smartHome->setSessionId(null);
		$req = $this->getRequest();
		$req['UserName'] = $this->smartHome->getUsername();
		$req['Password'] = self::encrypt($this->smartHome->getPassword());

		$response = parent::send($expectedResponse, $useSession, $try);
		$this->smartHome->setSessionId((string) $response['SessionId']);
		$this->smartHome->setConfigVersion((string) $response['CurrentConfigurationVersion']);
		return $response;
	}

	/**
	 * @param string $password
	 * @return string the encrypted password
	 */
	static function encrypt($password)
	{
		return base64_encode(hash('sha256', utf8_encode($password), true));
	}
}
