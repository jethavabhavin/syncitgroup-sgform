<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */

namespace SyncitGroup\SgForm\Model;

class SgFormContact extends \Magento\Framework\Model\AbstractModel {
	/**
	 * Model Initialization
	 *
	 * @return void
	 */
	protected function _construct() {
		parent::_construct();

		$this->_init('SyncitGroup\SgForm\Model\ResourceModel\SgFormContact');
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValues() {
		$values = [];

		return $values;
	}
}
