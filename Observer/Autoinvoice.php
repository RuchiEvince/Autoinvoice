<?php
/**
 * Created by Phong Phan.
 * Copyright Mercuriel - 2016
 * Date: 01/06/2017
 * Time: 14:08
 */
namespace Mercuriel\Autoinvoice\Observer;

use Magento\Framework\Event\ObserverInterface;

class Autoinvoice implements ObserverInterface
{
    /**
     * Mercuriel Helper Class
     *  @var \Magento\Catalog\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Mercuriel\Autoinvoice\Helper\Data
     */
    public function __construct( \Mercuriel\Autoinvoice\Helper\Data $helper)
    {
        $this->_helper = $helper;
    }
    /**
     * Execute when customer checkout successfully
     * @param \Magento\Framework\Event\Observer $observer
     * return void
     */

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(!$this->_helper->_isEnabledModule())
        {
            return;
        }

        $orderIds = $observer->getEvent()->getOrderIds();
        $orderId = $orderIds[0];
        $order = $this->_helper->getOrderByOrderId($orderId);
        if(is_array($this->_helper->getEnabledForStores())&&!empty($this->_helper->getEnabledForStores()))
        {
            if(!in_array($order->getStoreId(), $this->_helper->getEnabledForStores()))
            {
                return;
            }   
        }

        $this->_helper->assignInvoice($order);
        //$this->_helper->createShipments($order);
        return;
    }

}