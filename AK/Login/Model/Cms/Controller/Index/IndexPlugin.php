<?php

namespace AK\Login\Model\Cms\Controller\Index;


use \Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;

class IndexPlugin
{
    protected $helper;
    protected $session;
    protected $resultFactory;
    protected $scopeConfig;

    protected $route = 'customer';
    protected $controller = 'account';
    protected $action = 'login';

    /**
     * IndexPlugin constructor.
     *
     * @param \AK\Login\Helper\Data $helper
     * @param Session $session
     * @param ResultFactory $resultFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\AK\Login\Helper\Data $helper,
        Session $session,
        ResultFactory $resultFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->helper = $helper;
        $this->session = $session;
        $this->resultFactory = $resultFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Framework\App\Action\Action $subject
     * @param \Closure $proceed
     * @param null $coreRoute
     * @return \Closure|\Magento\Framework\Controller\ResultInterface|mixed
     */
    public function aroundExecute(
        \Magento\Framework\App\Action\Action $subject,
        \Closure $proceed,
        $coreRoute = null
    )
    {
        //preferable to rewrite dispatch() method (aroundDispatch) to forward/redirect on the earlier step
        $pageId = $this->scopeConfig->getValue(
            \Magento\Cms\Helper\Page::XML_PATH_HOME_PAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($this->helper->cmsPageRequiresLogin($pageId) && !($this->session->isLoggedIn())) {
            $result = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            if ($this->session->getBeforeRequestParams()) {
                $result->setParams($this->session->getBeforeRequestParams());
            }
            $result
                ->setModule($this->route)
                ->setController($this->controller)
                ->forward($this->action);
            return $result;
        }

        $proceed = $proceed($coreRoute);
        return $proceed;
    }
}