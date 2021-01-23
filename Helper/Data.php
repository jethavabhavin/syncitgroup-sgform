<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {
	/**
	 * @var \Magento\Framework\App\Config\ScopeConfigInterfac
	 */
	protected $_scopeConfig;
	/**
	 * @var \Magento\Backend\Helper\Data
	 */
	protected $_helperBackend;
	/**
	 * @var \Magento\Backend\Model\UrlInterface
	 */
	protected $_url;

	/*Extention Enable Disable Constant*/
	CONST ENABLE = 'sgform/general/enable';
	CONST CAPTCHA = 'sgform/general/captcha';
	CONST CONF_SEND_EMAIL = 'sgform/general/sendemail';
	CONST CONF_SEND_EMAIL_TEMPLATE = 'sgform/general/email_template';
	CONST CONF_SEND_EMAIL_TEMPLATE_CSS = 'sgform/general/email_template_css';
	CONST CONF_SEND_EMAIL_SUBJECT = 'sgform/general/email_subject';
	/**
	 *	construct
	 *
	 * @param Context $context,
	 * @param ScopeConfigInterface $scopeConfig
	 * @param Data $HelperBackend
	 */
	public function __construct(
		Context $context,
		\Magento\Backend\Helper\Data $HelperBackend
	) {

		$this->_url = $context->getUrlBuilder();

		$this->_scopeConfig = $context->getScopeConfig();

		$this->_helperBackend = $HelperBackend;
	}

	/**
	 * Retrieve editor variable url
	 *
	 * @return string
	 */
	public function getEditorVariableUrl() {
		return $this->_url->getUrl('sgform/variable/template');
	}
	/**
	 * Retrieve extention enable or disable
	 *
	 * @return boolean
	 */
	public function isExtentionEnable() {
		return $this->_scopeConfig->getValue(Self::ENABLE, ScopeInterface::SCOPE_STORE);
	}

	/**
	 * Retrieve captcha enable or disable
	 *
	 * @return boolean
	 */
	public function isCaptchaEnable() {
		return $this->_scopeConfig->getValue(Self::CAPTCHA, ScopeInterface::SCOPE_STORE);
	}
	/**
	 * Retrieve is email send enable or not
	 *
	 * @return boolean
	 */
	public function isEmailEnable($store = null) {
		if ($store) {
			$data = $store->getConfig(Self::CONF_SEND_EMAIL);
		} else {
			$data = $this->_scopeConfig->getValue(Self::CONF_SEND_EMAIL, ScopeInterface::SCOPE_STORE);
		}
		return $data;
	}
	/**
	 * Retrieve email subject
	 *
	 * @return string
	 */
	public function getEmailSubject($store = null) {
		if ($store) {
			$data = $store->getConfig(Self::CONF_SEND_EMAIL_SUBJECT);
		} else {
			$data = $this->_scopeConfig->getValue(Self::CONF_SEND_EMAIL_SUBJECT, ScopeInterface::SCOPE_STORE);
		}
		return $data;
	}
	/**
	 * Retrieve email html template
	 *
	 * @return string
	 */
	public function getEmailTemplate($store = null) {
		if ($store) {
			$data = $store->getConfig(Self::CONF_SEND_EMAIL_TEMPLATE);
		} else {
			$data = $this->_scopeConfig->getValue(Self::CONF_SEND_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE);
		}
		return $data;
	}

	/**
	 * Retrieve email template css
	 *
	 * @return string
	 */
	public function getEmailTemplateCss($store = null) {
		$data = '';
		if ($store) {
			$data = $store->getConfig(Self::CONF_SEND_EMAIL_TEMPLATE_CSS);
		}
		return $data;
	}

	/**
	 * Retrieve serialize setting
	 *
	 * @return array
	 */
	public function serializeSetting($data) {
		return serialize($data);
	}

	/**
	 * Retrieve unserialize setting
	 *
	 * @return array
	 */
	public function unserializeSetting($string) {
		$data = [];

		if (!empty($string)) {
			$data = unserialize($string);
		}

		return $data;
	}
}