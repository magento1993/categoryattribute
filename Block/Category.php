<?php

namespace Honey\Category\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

class Category extends Template
{
    protected $_coreRegistry = null;
    protected $_categoryFactory; 

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry)
    {
        $this->_coreRegistry = $registry;
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context);
                
    }

    public function getMediaUrl()
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
           \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
       );
        return $mediaDirectory;
    }
    
    public function getCurrentCategory()
    {
        if (!$this->hasData('current_category')) {
            $this->setData('current_category', $this->_coreRegistry->registry('current_category'));
        }
        return $this->getData('current_category');
    }

    public function getCategoryData($subcatid)
    {
        $category = $this->_categoryFactory->create()->load($subcatid);
        return $category;
    }

}
