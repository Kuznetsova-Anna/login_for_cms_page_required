<?php

namespace AK\Login\Model\Cms\Controller\Page;


use \Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;

class ViewPlugin
{
    /**
     * @var \AK\Login\Helper\Data
     */
    protected $helper;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    protected $route = 'customer';
    protected $controller = 'account';
    protected $action = 'login';

    /**
     * ViewPlugin constructor.
     *
     * @param \AK\Login\Helper\Data $helper
     * @param Session $session
     * @param ResultFactory $resultFactory
     */
    public function __construct(\AK\Login\Helper\Data $helper,
        Session $session,
        ResultFactory $resultFactory)
    {
        $this->helper = $helper;
        $this->session = $session;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @param \Magento\Framework\App\Action\Action $subject
     * @param \Closure $proceed
     * @return \Closure|\Magento\Framework\Controller\ResultInterface|mixed
     */
    public function aroundExecute(
        \Magento\Framework\App\Action\Action $subject,
        \Closure $proceed
    )
    {
        if ($this->helper->cmsPageRequiresLogin() && !($this->session->isLoggedIn())) {
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

        $proceed = $proceed();
        return $proceed;
    }

}
