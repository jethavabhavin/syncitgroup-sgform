<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Controller\Adminhtml\Contacts;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use SyncitGroup\SgForm\Model\ResourceModel\SgFormContact\CollectionFactory;

class MassDelete extends \Magento\Backend\App\Action {
	/**
	 * Authorization level of a basic admin session
	 *
	 * @see _isAllowed()
	 */
	const ADMIN_RESOURCE = 'SyncitGroup_SgForm::sgform';
	/**
	 * Mass Action Filter
	 *
	 * @var \Magento\Ui\Component\MassAction\Filter
	 */
	protected $_filter;

	/**
	 * Collection Factory
	 *
	 * @var \SyncitGroup\SgForm\Model\ResourceModel\SgFormContact\CollectionFactory
	 */
	protected $_collectionFactory;

	/**
	 * constructor
	 *
	 * @param Filter $filter
	 * @param CollectionFactory $collectionFactory
	 * @param Context $context
	 */
	public function __construct(
		Filter $filter,
		CollectionFactory $collectionFactory,
		Context $context
	) {
		$this->_filter = $filter;
		$this->_collectionFactory = $collectionFactory;
		parent::__construct($context);
	}

	/**
	 * execute action
	 *
	 * @return \Magento\Backend\Model\View\Result\Redirect
	 */
	public function execute() {
		$collection = $this->_filter->getCollection($this->_collectionFactory->create());

		$delete = 0;

		foreach ($collection as $syncitgroup_sgform) {
			/** @var \SyncitGroup\SgForm\Model\SgFormContact $syncitgroup_sgform */
			$syncitgroup_sgform->delete();

			$delete++;
		}

		$this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $delete));

		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);

		return $resultRedirect->setPath('*/*/');
	}
}
