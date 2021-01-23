<?php
declare (strict_types = 1);
/**
 * @category  Syncit Group Custom Contact Form
 * @package   SyncitGroup_SgForm
 * @copyright Copyright (c) 2021 Bhavin
 * @author    Bhavin
 */
namespace SyncitGroup\SgForm\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;

/**
 * Class Router
 */
class Router implements RouterInterface {
	/**
	 * @var ActionFactory
	 */
	private $actionFactory;
	/**
	 * @var ResponseInterface
	 */
	private $response;
	/**
	 * Router constructor.
	 *
	 * @param ActionFactory $actionFactory
	 * @param ResponseInterface $response
	 */
	public function __construct(
		ActionFactory $actionFactory,
		ResponseInterface $response
	) {
		$this->actionFactory = $actionFactory;
		$this->response = $response;
	}
	/**
	 * @param RequestInterface $request
	 * @return ActionInterface|null
	 */
	public function match(RequestInterface $request):  ? ActionInterface {

		if ($request->getFrontName() == 'syncit-group-form') {
			$request->setModuleName('sgform');
			$request->setControllerName('index');
			$request->setActionName('index');
			return $this->actionFactory->create(Forward::class, ['request' => $request]);
		}

		return null;
	}
}