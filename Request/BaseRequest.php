<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 13:33
 */

namespace Bubelbub\SmartHomePHP\Request;

use Bubelbub\SmartHomePHP\SmartHome;

/**
 * Class BaseRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
abstract class BaseRequest
{
	/**
	 * @var \Bubelbub\SmartHomePHP\SmartHome
	 */
	protected $smartHome;

	/**
	 * @var \SimpleXMLElement
	 */
	private $request;

	/**
	 * @var \SimpleXMLElement
	 */
	private $response;

	/**
	 * @var string the action (cmd|upd)
	 */
	protected $action = 'cmd';

	/**
	 * @param SmartHome $smartHome
	 */
	public function __construct(SmartHome $smartHome)
	{
		$this->smartHome = $smartHome;
		$reflectionClass = new \ReflectionClass($this);

		$this->request = new \SimpleXMLElement('<BaseRequest xmlns:xsd="http://www.w3.org/2001/XMLSchema" />');
		$this->request->addAttribute('xsi:type', $reflectionClass->getShortName(), 'http://www.w3.org/2001/XMLSchema-instance');
	}

	/**
	 * @param string|null $expectedResponse the response type which expected
	 * @param bool $useSession should the request use an automatic session id?
	 * @param bool $secondFail is this the second try of this request?
	 * @return \SimpleXMLElement
	 * @throws \Exception
	 */
	public function send($expectedResponse = null, $useSession = true, $try = 1)
	{
		$this->request['RequestId'] = '33300000-2200-1000-0000-' . substr(md5(uniqid()), 0, 12);
		$this->request['Version'] = $this->smartHome->getVersion();

		if($useSession)
		{
			$this->request['SessionId'] = $this->smartHome->getSessionId();
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://' . $this->smartHome->getHost() . '/' . $this->action);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request->asXML());
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		$this->setResponse(curl_exec($ch));
		curl_close($ch);

		/**
		 * Fix the problems
		 */
		$responseType = (string) $this->getResponse()->attributes('xsi', true)->type;
		if($expectedResponse !== null && strtolower($responseType) !== strtolower($expectedResponse))
		{
			if($try > 1)
			{
				throw new \Exception('Request failed second time. Error: ' . $responseType, 99);
			}

			if($responseType === 'GenericSHCErrorResponse' || $responseType === 'AuthenticationErrorResponse')
			{
				$this->smartHome->login(++$try);
				return $this->send($expectedResponse, $useSession, $try);
			}
			else
			{
				$try--;
			}

			if($responseType === 'VersionMismatchErrorResponse')
			{
				$this->smartHome->setVersion((string) $this->getResponse()->attributes()->ExpectedVersion);
				return $this->send($expectedResponse, $useSession, ++$try);
			}
			else
			{
				$try--;
			}
		}

		return $this->getResponse();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * Set the last response of an request
	 *
	 * @param string $response the response of shc
	 */
	private function setResponse($response)
	{
		$xml = new \SimpleXMLElement('<BaseResponse />');
		try
		{
			$xml = new \SimpleXMLElement($response);
		}
		catch(\Exception $ex){}
		$xml->registerXPathNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$this->response = $xml;
	}
}
