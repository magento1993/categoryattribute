<?php

namespace Honey\Category\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

class FeaturedCategories extends Template
{
    protected $_categoryCollectionFactory;
 
    protected $_categoryHelper;

    protected $_categoryFactory;
  
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        array $data = []
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context, $data);
    }

    public function getMediaUrl()
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
           \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
       );
        return $mediaDirectory;
    }
    
    public function getFeaturedCategories($isActive = true)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('is_featured',['eq'=>1]);
        $collection->addAttributeToSort('updated_at','desc');
        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }
        //$collection->setPageSize(6);
   
        return $collection;
    }

    public function getCategoryData($id)
    {
        $category = $this->_categoryFactory->create()->load($id);
        return $category;
    }

}
