<?php

namespace Honey\Category\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Grouped extends Template
{
    protected $_product; 

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        TimezoneInterface $localeDate,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product $product)
    {
        $this->_product = $product;
        $this->localeDate = $localeDate;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
                
    }

    public function getItemData($product)
    {
        return $this->_product->load($product->getId());
    }
    
    public function getProductWeight($product_data)
    {
        $weight_lb = $product_data->getWeight();
        $numberformatted = number_format($weight_lb, 2, '.', '');
        return $numberformatted;
    }

    public function getProductWeightUnit()
    {
        return $this->scopeConfig->getValue(
            'general/locale/weight_unit',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getProductSize($product_data)
    {
        return $product_data->getResource()->getAttribute('size')->getFrontend()->getValue($product_data);
    }

    public function getSalelbl($productid)
    {

     $splFromDate = $productid->getSpecialFromDate();
        $splToDate = $productid->getSpecialToDate();
        if (!$splFromDate && !$splToDate) {
            return false;
        }

        return $this->localeDate->isScopeDateInInterval(
            $productid->getStore(),
            $splFromDate,
            $splToDate
        );

    }


    public function isProductNew($product)
    {
        $newsFromDate = $product->getNewsFromDate();
        $newsToDate = $product->getNewsToDate();
        if (!$newsFromDate && !$newsToDate) {
            return false;
        }

        return $this->localeDate->isScopeDateInInterval(
            $product->getStore(),
            $newsFromDate,
            $newsToDate
        );
    }
    
    public function getDietType($product)
    {
        $diet_data = [];
        $childProductCollection = $product->getTypeInstance()->getAssociatedProducts($product);
        if ($childProductCollection) {
            foreach($childProductCollection as $childProduct) {
                $childProductData = $this->_product->load($childProduct->getId());
                $diet_data = array_merge($diet_data, $childProductData->getAttributeText('diet_type'));
            }
        
        }
        return array_unique($diet_data);
    }
}
