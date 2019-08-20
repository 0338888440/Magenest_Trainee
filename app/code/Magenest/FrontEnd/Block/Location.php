<?php

namespace Magenest\FrontEnd\Block;

use Magento\Backend\Block\Template;
use Magento\Framework\Stdlib\CookieManagerInterface;

class Location extends Template
{
    protected $customerLocation;
    protected $_customerFactory;
    protected $cookieManager;
    protected $_curl;

    public function __construct(Template\Context $context,
                                \Magento\Customer\Model\CustomerFactory $customerFactory,
                                \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $address,
                                CookieManagerInterface $cookieManager,
                                \Magento\Framework\HTTP\Client\Curl $curl,
                                array $data = [])
    {
        $this->cookieManager = $cookieManager;
        $this->customerLocation = $address;
        $this->_customerFactory = $customerFactory;
        $this->_curl = $curl;
        parent::__construct ($context, $data);
    }

    public function getCusLogIn()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $customerSession = $objectManager->create ('\Magento\Customer\Model\Session');

        if ($customerSession->isLoggedIn ())
            return $customerSession->getCustomer ()->getId ();
        else return null;
    }

    public function getCusLocation($id)
    {
        if ($id != null) {
            $address = $this->customerLocation->create ()->addAttributeToFilter ('parent_id', $id)->getFirstItem ();
            if (!empty($address)) {
                $city = $address->getData ('city');
                $region = $address->getData ('region');
                $street = $address->getData ('street');
                $str = $street . ", " . $region . ", " . $city;
                return $str;
            }
        } elseif
        ($this->cookieManager->getCookie ('location') != null) {
            return $this->cookieManager->getCookie ('location');
        } else
            return "Choose your location!!!";
    }

    public function getCityId($id)
    {
        $address = $this->customerLocation->create ()->addAttributeToFilter ('parent_id', $id)->getFirstItem ();
        $entity_id = $address->getData ('entity_id');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        /** @var \Magento\Catalog\Model\Product $product */
        $addr = $objectManager->get('Magento\Customer\Model\Address');
        $addre = $addr->load($entity_id);
        return $addre->getData('city_id');
    }

    public function getRegionId($id)
    {
        $addr = $this->customerLocation->create ()->addAttributeToFilter ('parent_id', $id);
        if (!empty($addr)) {
            foreach ($addr as $add) {
                $region_id = $add->getData ('region_id');
                return $region_id;
            }
        }
    }

    public function getStreetId($id)
    {
        $address = $this->customerLocation->create ()->addAttributeToFilter ('parent_id', $id)->getFirstItem ();
        $entity_id = $address->getData ('entity_id');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        /** @var \Magento\Catalog\Model\Product $product */
        $addr = $objectManager->get('Magento\Customer\Model\Address');
        $addre = $addr->load($entity_id);
        return $addre->getData('street_id');
    }

    public function getDataLocationCookie()
    {
        if ($this->getCusLogIn () != "") {
            return json_encode (['city_id' => $this->getCityId ($this->getCusLogIn ()),
                'region_id' => $this->getRegionId ($this->getCusLogIn ()),
                'street_id' => $this->getStreetId ($this->getCusLogIn ())]);
        } else {
            return $this->cookieManager->getCookie ('location_data');
        }
    }

    public function getJsonCity()
    {
        $url = "https://thongtindoanhnghiep.co/api/city";
        $this->_curl->get ($url);
        $content = $this->_curl->getBody ();
//        $content = file_get_contents ($url);
        $json = json_decode ($content, true);
        return $json;
    }
}