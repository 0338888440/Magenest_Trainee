<?php

namespace Magenest\FrontEnd\Block;

use Magento\Backend\Block\Template;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;

class Location extends Template
{
    const COOKIE_NAME = 'location';
    protected $customerLocation;
    protected $_customerFactory;
    protected $cookieManager;
    protected $cookieMetadataFactory;
    protected $sessionManager;

    public function __construct(Template\Context $context,
                                \Magento\Customer\Model\CustomerFactory $customerFactory,
                                \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $address,
                                CookieManagerInterface $cookieManager,
                                CookieMetadataFactory $cookieMetadataFactory,
                                SessionManagerInterface $sessionManager,
                                array $data = [])
    {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
        $this->customerLocation = $address;
        $this->_customerFactory = $customerFactory;
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
            $address = $this->customerLocation->create ()->addAttributeToFilter ('parent_id',$id);
            foreach ($address as $addr) {
                $city = $addr->getData ('city');
                $region = $addr->getData ('region');
                $street = $addr->getData ('street');
                $str = $street ." ". $region ." ". $city;
                return $str;
            }
        } elseif ($this->cookieManager->getCookie(self::COOKIE_NAME) != null){
            return $this->cookieManager->getCookie(self::COOKIE_NAME);
        }
        else return "Choose your location!!!";
    }

    public function getCity(){
        $url = "https://thongtindoanhnghiep.co/api/city";
        $response = file_get_contents($url);
        $json = json_decode($response, true);
        return $json;
    }
}