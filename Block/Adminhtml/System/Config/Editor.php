<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Registry;

class Editor extends \Magento\Config\Block\System\Config\Form\Field {
	/**
	 * @var  Registry
	 */
	protected $_coreRegistry;
	/**
	 * @var \Magento\Framework\Module\Dir\Reader
	 */
	protected $_moduleDirReader;

	/**
	 * @param Context       $context
	 * @param WysiwygConfig $wysiwygConfig
	 * @param array         $data
	 */
	public function __construct(
		Context $context,
		WysiwygConfig $wysiwygConfig,
		Reader $moduleDirReader,
		array $data = []
	) {
		$this->_wysiwygConfig = $wysiwygConfig;

		$this->_moduleDirReader = $moduleDirReader;

		parent::__construct($context, $data);
	}

	protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
		// set wysiwyg for element
		$element->setWysiwyg(true);
		// set configuration values
		$config = $this->_wysiwygConfig->getConfig($element);
		$plugins_conf = $config->getData();

		$plugins_conf['add_variables'] = false;
		$plugins_conf['add_widgets'] = false;
		$plugins_conf['add_images'] = true;
		$config->setData($plugins_conf);

		$element->setConfig($config);

		if (!$element->getValue()) {
			$filePath = $this->_moduleDirReader->getModuleDir('', "SyncitGroup_SgForm") . "/view/frontend/email/sgform_email_template.html";
			$value = file_get_contents($filePath);
			$element->setValue($value);
		}

		return parent::_getElementHtml($element);
	}
}