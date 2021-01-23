<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use SyncitGroup\SgForm\Helper\Data;
use \Magento\Framework\Exception\NotFoundException;

class Index extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface {
	/**
	 * @var \SyncitGroup\SgForm\Helper\Data
	 */
	protected $_helper;
	/**
	 * @param Context $context
	 * @param Data $helper
	 */
	public function __construct(
		Context $context,
		Data $helper
	) {
		parent::__construct($context);
		$this->_helper = $helper;
	}
	/**
	 * Show Contact Us page
	 *
	 * @return \Magento\Framework\Controller\ResultInterface
	 */
	public function execute() {
		return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
	}

	/**
	 * Dispatch request
	 *
	 * @param RequestInterface $request
	 * @return \Magento\Framework\App\ResponseInterface
	 * @throws \Magento\Framework\Exception\NotFoundException
	 */
	public function dispatch(RequestInterface $request) {
		if (!$this->_helper->isExtentionEnable()) {
			$this->_redirect('noroute');
			return;
		} else {
			return parent::dispatch($request);
		}
	}
}
