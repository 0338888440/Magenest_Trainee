<?php

namespace Magenest\Movie\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Rating extends Column
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
                if (array_key_exists ('movie_id', $item) && !empty($item['movie_id'])) {
                    $width = $item[$this->getData ('name')] * 20;
                    $item[$this->getData ('name')] = "<div class='field-summary-rating'><div class='rating-box'><div class='rating' style='width:" . $width . "%'></div></div></div>";
                }
            }
        }
        return $dataSource;
    }
}