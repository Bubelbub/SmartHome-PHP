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
	 * @var array
	 */
	private $responseHeader;

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
		if(($clientId = $this->smartHome->getClientId()) !== null || $this->action === 'upd')
		{
			if($clientId === null && $this->action === 'upd')
			{
				throw new \Exception('Unable to get updates if no client id is specified!', 107);
			}
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('ClientId: ' . $clientId));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->action === 'upd' ? 'upd' : $this->request->asXML());
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		list($header, $body) = explode("\r\n\r\n", curl_exec($ch), 2);
		$this->setResponse($body);
		$this->setResponseHeader($header);
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

	/**
	 * @param string|array $responseHeader
	 */
	public function setResponseHeader($responseHeader)
	{
		if(is_string($responseHeader))
		{
			require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR . 'HttpParseHeaders.php';
			$responseHeader = http_parse_headers($responseHeader);
		}
		if(isset($responseHeader['ClientId']))
		{
			$this->smartHome->setClientId($responseHeader['ClientId']);
		}
		$this->responseHeader = $responseHeader;
	}

	/**
	 * @param null|string|integer $key the key if needed
	 * @return array|string the response header array or the needed value from key
	 */
	public function getResponseHeader($key = null)
	{
		return $key === null || !is_array($this->responseHeader) ? $this->responseHeader : $this->responseHeader[$key];
	}
}
