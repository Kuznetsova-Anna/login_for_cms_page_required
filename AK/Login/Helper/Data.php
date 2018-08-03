<?php

namespace AK\Login\Helper;


use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;


class Data extends AbstractHelper
{

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_page;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    protected $session;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param \Magento\Cms\Model\Page $page
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(Context $context,
        \Magento\Cms\Model\Page $page,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $session
    ) {
        $this->_page = $page;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function cmsPageRequiresLogin($pageId = null)
    {
        if ($pageId == NULL) {
            $pageId = $this->_getRequest()->getParam('page_id', $this->_getRequest()->getParam('id', false));
        }

        if ($pageId) {
            //m1-way of getting cms page, need to use 'custom attributes' or 'extension attributes'
            //if they exist for this entity (do not exist for cms)
            $isLoginProtected = $this->_page->load($pageId)->getIsLoginProtected();

            if ($isLoginProtected == NULL) {
                return false;
            }
            return $isLoginProtected;
        }

        return false;
    }

}