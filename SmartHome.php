<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 13:36
 */

namespace Bubelbub\SmartHomePHP;

use Bubelbub\SmartHomePHP\Request\AcquireConfigurationLockRequest;
use Bubelbub\SmartHomePHP\Request\GetAllLogicalDeviceStatesRequest;
use Bubelbub\SmartHomePHP\Request\GetAllPhysicalDeviceStatesRequest;
use Bubelbub\SmartHomePHP\Request\GetApplicationTokenRequest;
use Bubelbub\SmartHomePHP\Request\GetEntitiesRequest;
use Bubelbub\SmartHomePHP\Request\GetMessageListRequest;
use Bubelbub\SmartHomePHP\Request\GetShcInformationRequest;
use Bubelbub\SmartHomePHP\Request\GetShcTypeRequest;
use Bubelbub\SmartHomePHP\Request\LoginRequest;
use Bubelbub\SmartHomePHP\Request\ReleaseConfigurationLockRequest;

/**
 * Class SmartHome
 * @package Bubelbub\SmartHomePHP
 * @author Bubelbub <bubelbub@gmail.com>
 */
class SmartHome
{
	/**
	 * @var string the hostname/ip address of the shc
	 */
	private $host;

	/**
	 * @var string the username of shc user/owner
	 */
	private $username;

	/**
	 * @var string the password of shc user/owner
	 */
	private $password;

	/**
	 * @var bool is the password saved encrypted?
	 */
	private $isPasswordEncrypted = false;

	/**
	 * @var string the session id of the last requests
	 */
	private $sessionId;

	/**
	 * @var string the version of shc
	 */
	private $version;

	/**
	 * @var string the configuration version of entities etc.
	 */
	private $configVersion;

	/**
	 * @param string $host the hostname/ip address of the shc
	 * @param string $username the username of shc user/owner
	 * @param string $password the password of shc user/owner
	 * @param string|null $sessionId the session id of the last requests
	 * @param string|null $version the version of shc
	 * @param string|null $configVersion the configuration version of entities etc.
	 */
	public function __construct($host, $username, $password, $sessionId = null, $version = null, $configVersion = null)
	{
		if(!is_string($host))
		{
			throw new \Exception('Missing parameter 1 "host"', 100);
		}
		$this->host = gethostbyname($host);

		$online = @fsockopen($this->host, 443, $errorNumber, $errorString, 10);
		if($online === false)
		{
			throw new \Exception('Host "' . $this->host . '" (' . $host . ') is offline / Error [' . $errorNumber . '] ' . $errorString, 103);
		}
		@fclose($online);

		if(!is_string($username))
		{
			throw new \Exception('Missing parameter 2 "username"', 101);
		}
		$this->username = $username;

		if(!is_string($password))
		{
			throw new \Exception('Missing parameter 3 "password"', 102);
		}
		$this->password = $password;

		if(is_string($sessionId))
		{
			$this->sessionId = $sessionId;
		}

		if($version !== null)
		{
			$this->version = $version;
		}

		if($configVersion !== null)
		{
			$this->configVersion = $configVersion;
		}
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function login($try = 1)
	{
		$loginRequest = new LoginRequest($this);
		return $loginRequest->send('LoginResponse', false, $try);
	}

	/**
	 * @param string $entityType
	 * @return \SimpleXMLElement
	 */
	public function getEntities($entityType = 'Configuration')
	{
		$getEntitiesRequest = new GetEntitiesRequest($this);
		$getEntitiesRequest->setEntityType($entityType);
		return $getEntitiesRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getShcInformation()
	{
		$getShcInformationRequest = new GetShcInformationRequest($this);
		return $getShcInformationRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getAllLogicalDeviceStates()
	{
		$getAllLogicalDeviceStatesRequest = new GetAllLogicalDeviceStatesRequest($this);
		return $getAllLogicalDeviceStatesRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getApplicationToken()
	{
		$getApplicationTokenRequest = new GetApplicationTokenRequest($this);
		return $getApplicationTokenRequest->send();
	}

	/**
	 * @param string $restriction
	 * @return \SimpleXMLElement
	 */
	public function getShcType($restriction = 'All')
	{
		$getShcTypeRequest = new GetShcTypeRequest($this);
		$getShcTypeRequest->setRestriction($restriction);
		return $getShcTypeRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getAllPhysicalDeviceStates()
	{
		$getAllPhysicalDeviceStatesRequest = new GetAllPhysicalDeviceStatesRequest($this);
		return $getAllPhysicalDeviceStatesRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getMessageList()
	{
		$getMessageListRequest = new GetMessageListRequest($this);
		return $getMessageListRequest->send();
	}

	/**
	 * @param bool $overrideLock
	 * @return \SimpleXMLElement
	 */
	public function lockConfiguration($overrideLock = false)
	{
		$acquireConfigurationLockRequest = new AcquireConfigurationLockRequest($this);
		$acquireConfigurationLockRequest->setOverrideLock($overrideLock);
		return $acquireConfigurationLockRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function unlockConfiguration()
	{
		$releaseConfigurationLockRequest = new ReleaseConfigurationLockRequest($this);
		return $releaseConfigurationLockRequest->send();
	}

	/**
	 * @return string the host of shc
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @return string the username of shc
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @return string the password of shc
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param boolean $isPasswordEncrypted
	 */
	public function setIsPasswordEncrypted($isPasswordEncrypted)
	{
		$this->isPasswordEncrypted = $isPasswordEncrypted;
	}

	/**
	 * @return boolean
	 */
	public function getIsPasswordEncrypted()
	{
		return $this->isPasswordEncrypted;
	}

	/**
	 * @return string the session id of shc
	 */
	public function getSessionId()
	{
		if($this->sessionId === null)
		{
			$this->login();
		}
		return $this->sessionId;
	}

	/**
	 * @param string $sessionId
	 */
	public function setSessionId($sessionId = null)
	{
		$this->sessionId = $sessionId;
	}

	/**
	 * @return string the version of shc
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @param null $version
	 */
	public function setVersion($version = null)
	{
		$this->version = $version;
	}

	/**
	 * @return null
	 */
	public function getConfigVersion()
	{
		return $this->configVersion;
	}

	/**
	 * @param null $configVersion
	 */
	public function setConfigVersion($configVersion = null)
	{
		$this->configVersion = $configVersion;
	}
}
