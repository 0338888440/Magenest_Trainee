<?php

namespace Magenest\Movie\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class OddEven extends Column
{
    public function __construct(ContextInterface $context,
                                UiComponentFactory $uiComponentFactory,
                                array $components = [],
                                array $data = [])
    {
        parent::__construct ($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $value = "";
                $class = "";
                if ($item['increment_id'] % 2 == 0) {
                    $value = "SUCCESS";
                    $class = "grid-severity-notice";
                } else {
                    $value = "ERROR";
                    $class = "grid-severity-critical";
                }
                $item[$this->getData ('name')] = '<span class="' . $class . '"><span>' . $value . '</span></span>';

            }
        }
        return $dataSource;
    }
}