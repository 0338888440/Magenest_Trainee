<?php

namespace Magenest\FrontEnd\Controller\Index;

use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class SaveLocation extends \Magento\Framework\App\Action\Action
{
    protected $_cookieManager;
    protected $_cookieMetadataFactory;
    protected $_sessionManager;
    protected $_customerFactory;
    protected $_customerLocation;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $address,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    )
    {
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_sessionManager = $sessionManager;
        $this->_customerLocation = $address;
        $this->_customerFactory = $customerFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $city = $this->getRequest ()->getParam ('city');
        $region = $this->getRequest ()->getParam ('region');
        $street = $this->getRequest ()->getParam ('street');
        $str = $street . ", " . $region . ", " . $city;
        $id = $this->getSessionID ();
        if ($id != null) {
            $colCusAdd = $this->_customerLocation->create ()->addAttributeToFilter ('parent_id', $id);
            $colCusAdd->setDataToAll ('city', $city);
            $colCusAdd->setDataToAll ('region', $region);
            $colCusAdd->setDataToAll ('street', $street);
            $colCusAdd->save ();
        } else {
            $metadata = $this->_cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setDuration(600)  /*10 min*/
                ->setPath($this->_sessionManager->getCookiePath())
                ->setDomain($this->_sessionManager->getCookieDomain());

            $this->_cookieManager->setPublicCookie(
                'location',
                $str,
                $metadata
            );
        }
    }

    public function getSessionID()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $customerSession = $objectManager->create ('\Magento\Customer\Model\Session');

        if ($customerSession->isLoggedIn ())
            return $customerSession->getCustomer ()->getId ();
        else return null;
    }
}