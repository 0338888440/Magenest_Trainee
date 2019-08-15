<?php

namespace Magenest\FrontEnd\Block;

use Magento\Backend\Block\Template;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
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
            $address = $this->customerLocation->create ()->addAttributeToFilter ('parent_id', $id);
            foreach ($address as $addr) {
                $city = $addr->getData ('city');
                $region = $addr->getData ('region');
                $street = $addr->getData ('street');
                $str = $street . ", " . $region . ", " . $city;
                return $str;
            }
        } elseif ($this->cookieManager->getCookie ('location') != null) {
            return $this->cookieManager->getCookie ('location');
        } else return "Choose your location!!!";
    }

    public function getJsonCity()
    {
        $url = "https://thongtindoanhnghiep.co/api/city";
        $this->_curl->get($url);
        $content = $this->_curl->getBody();
//        $content = file_get_contents ($url);
        $json = json_decode ($content, true);
        return $json;
    }
}