<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

class SgFormContact extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {

	/**
	 * @var \Magento\Framework\Stdlib\DateTime\DateTime
	 */
	protected $_date;

	/**
	 * Construct
	 *
	 * @param Context $context
	 * @param DateTime $date
	 * @param string|null $resourcePrefix
	 */

	public function __construct(
		Context $context,
		DateTime $date,
		$resourcePrefix = null
	) {
		parent::__construct($context, $resourcePrefix);

		$this->_date = $date;
	}

	/**
	 * Model Initialization
	 *
	 * @return void
	 */
	protected function _construct() {
		$this->_init('sgform_contacts', 'id');
	}

	/**
	 * Process post data before saving
	 *
	 * @param \Magento\Framework\Model\AbstractModel $object
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object) {
		if ($object->isObjectNew() && !$object->hasCreatedAt()) {
			$object->setCreatedAt($this->_date->gmtDate());
		}

		$object->setUpdatedAt($this->_date->gmtDate());

		return parent::_beforeSave($object);
	}
}