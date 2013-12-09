<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 16:30
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class GetShcTypeRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class GetShcTypeRequest extends BaseRequest
{
	/**
	 * @var string the restriction (All)
	 */
	private $restriction = 'All';

	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'GetShcTypeResponse', $useSession = true, $try = 1)
	{
		$this->getRequest()->addChild('Restriction', $this->getRestriction());
		return parent::send($expectedResponse, $useSession, $try);
	}

	/**
	 * @param string $restriction
	 */
	public function setRestriction($restriction)
	{
		$this->restriction = $restriction;
	}

	/**
	 * @return string
	 */
	public function getRestriction()
	{
		return $this->restriction;
	}
}
