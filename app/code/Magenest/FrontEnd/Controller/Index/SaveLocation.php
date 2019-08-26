<?php

namespace Magenest\FrontEnd\Controller\Index;

use Magento\Framework\Controller\Result\Raw;
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
    protected $_addressRepository;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $address,
        \Magento\Customer\Model\Address $addressFactory,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    )
    {
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_sessionManager = $sessionManager;
        $this->_customerLocation = $address;
        $this->_addressRepository = $addressFactory;
        $this->_customerFactory = $customerFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $city = $this->getRequest ()->getParam ('city');
        $region = $this->getRequest ()->getParam ('region');
        $street = $this->getRequest ()->getParam ('street');
        $str = $street . ", " . $region . ", " . $city;

        $city_id = $this->getRequest ()->getParam ('city_id');
        $region_id = $this->getRequest ()->getParam ('region_id');
        $street_id = $this->getRequest ()->getParam ('street_id');
        $dataLocation = ['city_id' => $city_id, 'region_id' => $region_id, 'street_id' => $street_id];

        $id = $this->getSessionID ();

        $metadata = $this->_cookieMetadataFactory
            ->createPublicCookieMetadata ()
            ->setDuration (600)/*10 min*/
            ->setPath ($this->_sessionManager->getCookiePath ())
            ->setDomain ($this->_sessionManager->getCookieDomain ());

        if ($city == "-- Choose your city --" || $region == "-- Choose your region --" || $street == "-- Choose your street --") {
            /** @var Raw $raw */
            $raw = $this->resultFactory->create ('raw');
            $raw->setHttpResponseCode (404);
            return $raw;
        } else {
            if ($id != null) {
                $colCusAdd = $this->_customerLocation->create ()->addAttributeToFilter ('parent_id', $id)->getFirstItem ();
                $colCusAdd->setData ('city', $city);
                $colCusAdd->setData ('region', $region);
                $colCusAdd->setData ('street', $street);
                $colCusAdd->setData ('region_id', $region_id);
                $colCusAdd->save ();

                $addr = $this->_addressRepository->load ($colCusAdd->getData ('entity_id'));
                $addr_city_id = $addr->getDataModel ()->setCustomAttribute ('city_id', $city_id);
                $addr->updateData ($addr_city_id);
                $addr_street_id = $addr->getDataModel ()->setCustomAttribute ('street_id', $street_id);
                $addr->updateData ($addr_street_id);
                $this->_addressRepository->save ($addr);

            } else {
                $this->_cookieManager->setPublicCookie (
                    'location',
                    $str,
                    $metadata
                );
                $this->_cookieManager->setPublicCookie (
                    'location_data',
                    json_encode ($dataLocation),
                    $metadata
                );
            }
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