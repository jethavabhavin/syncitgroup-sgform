<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */

namespace SyncitGroup\SgForm\Model\Source;

/**
 * Class StatusExtention
 * @package SyncitGroup\SgForm\Model\Source
 */
class StatusExtention implements \Magento\Framework\Data\OptionSourceInterface {
	/**
	 * Status values
	 */
	const STATUS_ENABLE = 1;
	const STATUS_DISABLE = 0;

	/**
	 * @return array
	 */
	public function getOptionArray() {
		$optionArray = ['' => ' '];

		foreach ($this->toOptionArray() as $option) {
			$optionArray[$option['value']] = $option['label'];
		}

		return $optionArray;
	}

	/**
	 * @return array
	 */
	public function toOptionArray() {
		return [
			['value' => self::STATUS_ENABLE, 'label' => __('Enable')],
			['value' => self::STATUS_DISABLE, 'label' => __('Disable')],
		];
	}
}
