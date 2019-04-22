<?php

/**
 *
 */

namespace Honey\Category\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

/**
 *
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * EAV setup factory.
     *
     * @var EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * Init
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $eavSetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'is_featured', [
                'type'     => 'int',
                'label'    => 'Featured',
                'input'    => 'boolean',
                'source'   => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'visible'  => true,
                'default'  => '0',
                'required' => false,
                'global'   => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group'    => 'General Information',
            ]);
            /** @var EavSetup $eavSetup */
            $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);
            $setup = $this->categorySetupFactory->create(['setup' => $setup]);
            $setup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY, 'featured_image', [
                    'type' => 'varchar',
                    'label' => 'Featured Image',
                    'input' => 'image',
                    'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                    'required' => false,
                    'sort_order' => 9,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'General Information',
                ]
            );
        }
        
        $installer->endSetup();
    }
}
