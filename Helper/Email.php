<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Helper;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;
use SyncitGroup\SgForm\Helper\Data;

class Email {
	/**
	 * @var \Magento\Framework\App\Config\ScopeConfigInterfac
	 */
	protected $_transportBuilder;
	/**
	 * @var \SyncitGroup\SgForm\Helper\Data
	 */
	protected $_helper;
	/**
	 * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
	 */
	protected $_timezone;
	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;
	/**
	 * @param TransportBuilder $transportBuilder
	 * @param Data $helper
	 * @param TimezoneInterface $timezone
	 * @param StoreManagerInterface $storeManager
	 */
	public function __construct(
		TransportBuilder $transportBuilder,
		Data $helper,
		TimezoneInterface $timezone,
		StoreManagerInterface $storeManager
	) {
		$this->_helper = $helper;
		$this->_timezone = $timezone;
		$this->_storeManager = $storeManager;
		$this->_transportBuilder = $transportBuilder;
	}

	/**
	 * Retrieve contact per page
	 *
	 * @return boolean
	 */
	public function sendEmail($contact) {

		$to = [];
		$data = [];
		$store_id = $contact->getStoreId();
		$store = $this->_storeManager->getStore($store_id);

		if ($this->_helper->isEmailEnable($store)) {
			$to[] = $contact->getEmail();
			$data = [
				'store' => $store,
				'store_id' => $contact->getStoreId(),
				"name" => $contact->getName(),
				"email" => $contact->getEmail(),
				"message" => $contact->getMessage(),
				"ip" => $contact->getIp(),
				"submit_date" => $this->_timezone->formatDate($contact->getCreatedAt()),
			];

			$store_id = $data['store_id'];
			$store = $data['store'];
			$data["store_obj"] = $store;

			$store_email = $store->getConfig('trans_email/ident_general/email');

			$store_name = $store->getConfig('trans_email/ident_general/name');

			if (!empty($to)) {
				try {

					$transport = $this->_transportBuilder
						->setTemplateIdentifier('sgform_email_template')
						->setTemplateModel('SyncitGroup\SgForm\Model\Email\BackendTemplate')
						->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store_id])
						->setTemplateVars($data)
						->setFrom(['name' => $store_name, 'email' => $store_email])
						->addTo($to)
						->getTransport();

					$transport->sendMessage();

				} catch (\Exception $e) {}
			}
		}
	}
}