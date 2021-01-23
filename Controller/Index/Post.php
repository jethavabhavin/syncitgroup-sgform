<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Controller\Index;

use Magento\Captcha\Helper\Data as CaptchaHelper;
use Magento\Captcha\Observer\CaptchaStringResolver;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Store\Model\StoreManagerInterface;
use SyncitGroup\SgForm\Helper\Data;
use SyncitGroup\SgForm\Helper\Email;
use SyncitGroup\SgForm\Model\SgFormContactFactory;

class Post extends \Magento\Framework\App\Action\Action {
	CONST CAPTCHA_FORM_ID = 'sgform_captcha_form_1';
	/**
	 * @var \SyncitGroup\SgForm\Model\ResourceModel\MessageFactory
	 */
	protected $_sgformMessage = false;
	/**
	 * @var _storeManager
	 */
	protected $_storeManager;
	/**
	 * @var \Magento\Customer\Model\Session
	 */
	protected $_customerSession;
	/**
	 * @var \SyncitGroup\SgForm\Helper\Data
	 */
	protected $_helper;
	/**
	 * @var \Magento\Captcha\Observer\CaptchaStringResolver
	 */
	protected $_captchaStringResolver;
	/**
	 * @var \Magento\Captcha\Helper\Data
	 */
	protected $_captchaHelper;
	/**
	 * @var Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
	 */
	protected $_remoteAddress;
	/**
	 * @var Magento\Framework\Controller\Result\JsonFactory
	 */
	protected $_resultJsonFactory = false;

	/**
	 * @param Data $helper
	 * @param Context $context
	 * @param JsonFactory $resultJsonFactory
	 * @param CustomerSession $_customerSession
	 * @param SgformFactory $sgFormContact
	 * @param CaptchaStringResolver $captchaStringResolver
	 * @param StoreManagerInterface $storeManager
	 * @param CaptchaHelper $captchaHelper
	 * @param RemoteAddress $remoteAddress
	 */
	public function __construct(
		Context $context,
		Data $helper,
		Email $emailHelper,
		JsonFactory $resultJsonFactory,
		CustomerSession $_customerSession,
		SgFormContactFactory $sgFormContact,
		CaptchaStringResolver $captchaStringResolver,
		StoreManagerInterface $storeManager,
		CaptchaHelper $captchaHelper,
		RemoteAddress $remoteAddress
	) {
		$this->_helper = $helper;
		$this->_sgformMessage = $sgFormContact;
		$this->_customerSession = $_customerSession;
		$this->_resultJsonFactory = $resultJsonFactory;
		$this->_storeManager = $storeManager;
		$this->_captchaStringResolver = $captchaStringResolver;
		$this->_captchaHelper = $captchaHelper;
		$this->_remoteAddress = $remoteAddress;
		$this->_emailHelper = $emailHelper;

		parent::__construct($context);
	}

	/**
	 * @return mixed
	 */
	public function execute() {
		$formId = Self::CAPTCHA_FORM_ID;

		$captchaModel = $this->_captchaHelper->getCaptcha($formId);

		if ($captchaModel->isCorrect($this->_captchaStringResolver->resolve($this->getRequest(), $formId)) || !$this->_helper->isCaptchaEnable()) {
			$name = $this->getRequest()->getPost("name");

			$email = $this->getRequest()->getPost("email");

			$message = $this->getRequest()->getPost("message");

			if ($message && $email && $name) {
				$user_id = 0;
				if ($customer = $this->_customerSession->getCustomer()) {
					$user_id = $customer->getId();
				}
				$ip = $this->_remoteAddress->getRemoteAddress();
				$store_id = $this->_storeManager->getStore()->getId();

				$sgFormContact = $this->_sgformMessage->create();

				$sgFormContact
					->setUserId($user_id)
					->setStoreId($store_id)
					->setIp($ip)
					->setName($name)
					->setEmail($email)
					->setMessage($message)
					->save();

				if ($sgFormContact->getId()) {
					$message = __('Thank you for submitting to Syncit Group custom form!​');
					$status = 1;
					if ($this->_helper->isEmailEnable()) {
						$this->_emailHelper->sendEmail($sgFormContact);
					}
					$this->messageManager->addSuccess($message);
				} else {
					$message = __('Error! while saving data. Please try latter.');
					$this->messageManager->addError($message);
					$status = 0;
				}
			} else {
				$message = __('Error! All fields are required.');
				$this->messageManager->addError($message);
				$status = 0;
			}
		} else {
			$message = __('Invalide security code!');
			$this->messageManager->addError($message);
			$status = 0;
		}
		$this->_eventManager->dispatch(
			'syncit_group_form',
			['request' => $this->getRequest()]
		);
		$result = $this->_resultJsonFactory->create();

		$resultData = [
			'status' => $status,
			'message' => $message,
		];

		return $result->setData($resultData);

	}
}