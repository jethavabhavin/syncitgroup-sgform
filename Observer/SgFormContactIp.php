<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */

namespace SyncitGroup\SgForm\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class SgFormContactIp implements ObserverInterface {
	/**
	 * @var Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
	 */
	protected $_remoteAddress;
	/**
	 * @param RemoteAddress $remoteAddress
	 */
	public function __construct(
		RemoteAddress $remoteAddress
	) {
		$this->_remoteAddress = $remoteAddress;
	}
	/**
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return $this|void
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 */
	public function execute(\Magento\Framework\Event\Observer $observer) {
		$this->writelog($this->_remoteAddress->getRemoteAddress());
		return $this;
	}
	/**
	 * @param $value
	 *
	 * @return void
	 */
	public function writelog($value) {
		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/sgcontactsIp.txt');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);
		$logger->info($value);
	}
}
