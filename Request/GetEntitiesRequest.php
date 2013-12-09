<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 15:58
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class GetEntitiesRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class GetEntitiesRequest extends BaseRequest
{
	/**
	 * @var string
	 */
	private $entityType = 'Configuration';

	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'GetEntitiesResponse', $useSession = true, $try = 1)
	{
		$req = $this->getRequest();
		$req->addChild('EntityType', $this->getEntityType());
		return parent::send($expectedResponse, $useSession, $try);
	}

	/**
	 * @param string $entityType
	 */
	public function setEntityType($entityType)
	{
		$this->entityType = $entityType;
	}

	/**
	 * @return string
	 */
	public function getEntityType()
	{
		return $this->entityType;
	}
}
