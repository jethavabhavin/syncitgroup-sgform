<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Registry;
use SyncitGroup\SgForm\Helper\Data;

class ContactForm extends \Magento\Framework\View\Element\Template {
	/**
	 * @var _storeManager
	 */
	protected $_storeManager;
	/**
	 * @var \SyncitGroup\SgForm\Helper\Data
	 */
	protected $_helper;
	/**
	 * @var \Magento\Customer\Model\Session
	 */
	protected $_customerSession;
	/**
	 * @var \Magento\Framework\Data\Form\FormKey
	 */
	protected $_formKey;
	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry = null;

	/**
	 * @param Data $helper
	 * @param Registry $coreRegistry
	 * @param Context $context
	 * @param CustomerSession $_customerSession
	 * @param array $data
	 */
	public function __construct(
		Data $helper,
		Registry $coreRegistry,
		Context $context,
		CustomerSession $_customerSession,
		array $data = []
	) {
		$this->_helper = $helper;
		$this->_customerSession = $_customerSession;
		$this->_formKey = $context->getFormKey();
		$this->_coreRegistry = $coreRegistry;
		parent::__construct($context, $data);

	}
	/**
	 * getLoginCustomer
	 *
	 * get current login customer
	 *
	 * return object
	 */
	public function getLoginCustomer() {
		return $this->_customerSession->getCustomer();
	}

	/**
	 * getEmail
	 *
	 * return string email
	 */
	public function getCustomerEmail() {
		return $this->getLoginCustomer()->getEmail();
	}

	/**
	 * getName
	 *
	 * return string name
	 */
	public function getCustomerName() {
		return $this->getLoginCustomer()->getName();
	}
	/**
	 * isLogin
	 *
	 * return boolean
	 */
	public function isLogin() {
		if ($this->_customerSession->isLoggedIn()) {
			return true;
		}
		return false;
	}
	/**
	 * getFormUrl
	 *
	 * return string url
	 */
	public function getFormUrl() {
		return $this->getUrl("sgform/index/post");
	}
	/**
	 * getFormKey
	 *
	 * return string form key
	 */
	public function getFormKey() {
		return $this->_formKey->getFormKey();
	}
	/**
	 * Retrieve Module Data Helper
	 *
	 * @return _helper
	 */
	public function getDataHelper() {
		return $this->_helper;
	}
	/**
	 * Retrieve current Store Id
	 *
	 * @return store_id
	 */
	public function getCurrentStoreId() {
		return $this->_storeManager->getStore()->getId();
	}
}