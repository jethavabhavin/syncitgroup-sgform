<?php
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Model\Email;

use Magento\Framework\App\Config\ScopeConfigInterface;
use SyncitGroup\SgForm\Helper\Data;

/**
 * Adminhtml email template model
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BackendTemplate extends \Magento\Email\Model\BackendTemplate {

	/**
	 * @var mixed
	 */
	private $_helper;

	/**
	 * @param \Magento\Framework\Model\Context $context
	 * @param \Magento\Framework\View\DesignInterface $design
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Store\Model\App\Emulation $appEmulation
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Magento\Framework\View\Asset\Repository $assetRepo
	 * @param \Magento\Framework\Filesystem $filesystem
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	 * @param \Magento\Email\Model\Template\Config $emailConfig
	 * @param \Magento\Email\Model\TemplateFactory $templateFactory
	 * @param \Magento\Framework\Filter\FilterManager $filterManager
	 * @param \Magento\Framework\UrlInterface $urlModel
	 * @param \Magento\Email\Model\Template\FilterFactory $filterFactory
	 * @param \Magento\Config\Model\Config\Structure $structure
	 * @param \SyncitGroup\SgForm\Helper\Data $helper
	 * @param array $data
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
	 */
	public function __construct(
		\Magento\Framework\Model\Context $context,
		\Magento\Framework\View\DesignInterface $design,
		\Magento\Framework\Registry $registry,
		\Magento\Store\Model\App\Emulation $appEmulation,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\View\Asset\Repository $assetRepo,
		\Magento\Framework\Filesystem $filesystem,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Email\Model\Template\Config $emailConfig,
		\Magento\Email\Model\TemplateFactory $templateFactory,
		\Magento\Framework\Filter\FilterManager $filterManager,
		\Magento\Framework\UrlInterface $urlModel,
		\Magento\Email\Model\Template\FilterFactory $filterFactory,
		\Magento\Config\Model\Config\Structure $structure,
		Data $helper,
		array $data = []
	) {
		$this->_helper = $helper;
		parent::__construct(
			$context,
			$design,
			$registry,
			$appEmulation,
			$storeManager,
			$assetRepo,
			$filesystem,
			$scopeConfig,
			$emailConfig,
			$templateFactory,
			$filterManager,
			$urlModel,
			$filterFactory,
			$structure,
			$data
		);
	}
	/**
	 * Load default email template
	 *
	 * @param string $templateId
	 * @return $this
	 */
	public function loadDefault($templateId) {
		$designParams = $this->getDesignParams();
		$templateType = $this->emailConfig->getTemplateType($templateId);
		$templateTypeCode = $templateType == 'html' ? self::TYPE_HTML : self::TYPE_TEXT;
		$this->setTemplateType($templateTypeCode);

		$vars = $this->_getVars();
		$store_obj = $vars['store_obj'];

		$templateText = '<!--@subject {{trans "' . $this->_helper->getEmailSubject($store_obj) . '"}} @-->';
		$templateText .= '{{template config_path="design/email/header_template"}} ';
		$templateText .= $this->_helper->getEmailTemplate($store_obj);
		$templateText .= "<style>" . $this->_helper->getEmailTemplateCss($store_obj) . "</style>";
		$templateText .= '{{template config_path="design/email/footer_template"}}';

		/**
		 * trim copyright message
		 */
		if (preg_match('/^<!--[\w\W]+?-->/m', $templateText, $matches) && strpos($matches[0], 'Copyright') > 0) {
			$templateText = str_replace($matches[0], '', $templateText);
		}

		if (preg_match('/<!--@subject\s*(.*?)\s*@-->/u', $templateText, $matches)) {
			$this->setTemplateSubject($matches[1]);
			$templateText = str_replace($matches[0], '', $templateText);
		}

		if (preg_match('/<!--@vars\s*((?:.)*?)\s*@-->/us', $templateText, $matches)) {
			$this->setData('orig_template_variables', str_replace("\n", '', $matches[1]));
			$templateText = str_replace($matches[0], '', $templateText);
		}

		if (preg_match('/<!--@styles\s*(.*?)\s*@-->/s', $templateText, $matches)) {
			$this->setTemplateStyles($matches[1]);
			$templateText = str_replace($matches[0], '', $templateText);
		}

		// Remove comment lines and extra spaces
		$templateText = trim(preg_replace('#\{\*.*\*\}#suU', '', $templateText));

		$this->setTemplateText($templateText);
		$this->setId($templateId);

		return $this;
	}
}
