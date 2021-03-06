<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Sarp
 * @copyright  Copyright (c) 2010-2011 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */
class AW_Sarp_Block_Checkout_Cart_Item_Renderer_Configurable extends Mage_Checkout_Block_Cart_Item_Renderer_Configurable
{
    protected function _getSarpOptions()
    {
        $product = $this->getProduct();
        $startDateLabel = $product->getAwSarpHasShipping() ? $this->__("First delivery:")
                : $this->__("Subscription from:");

        $subscription_options = array();
        if ($product->getCustomOption('aw_sarp_subscription_type') && $product->getCustomOption('aw_sarp_subscription_type')->getValue() > 0) {
            $subscription_options[] = array(
                'label' => $this->__('Subscription type:'),
                'value' => Mage::getModel('sarp/period')->load($product->getCustomOption('aw_sarp_subscription_type')->getValue())->getName()
            );
            if ($product->getCustomOption('aw_sarp_subscription_start')) {
                $date = new Zend_Date($product->getCustomOption('aw_sarp_subscription_start')->getValue(), 'Y-MM-dd');
                $subscription_options[] = array('label' => $startDateLabel, 'value' => $this->formatDate($date));
            }
        }

        return $subscription_options;
    }

    public function getOptionList()
    {
        $item = $this->getItem();
        if (file_exists(Mage::getBaseDir() . '/app/code/core/' . 'Mage/Catalog/Helper/Product/Configuration.php')) {
            $helper = Mage::helper('catalog/product_configuration');
            $product = $item->getProduct();
            $attributes = $product->getTypeInstance(true)
                    ->getSelectedAttributesInfo($product);
            return array_merge($this->_getSarpOptions(), $attributes, $helper->getCustomOptions($item));
        }
        else
            return array_merge($this->_getSarpOptions(), parent::getOptionList());
    }
}