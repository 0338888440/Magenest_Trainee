<?php

namespace Magenest\Movie\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ActorActions extends Column
{
    protected $urlBuilder;

    public function __construct(ContextInterface $context,
                                UiComponentFactory $uiComponentFactory,
                                UrlInterface $urlBuilder,
                                array $components = [],
                                array $data = [])
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct ($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData ('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl ('movie/actor/edit', ['id' => $item['actor_id']]),
                    'label' => __ ('Edit'),
                    'hidden' => false
                ];
                $item[$this->getData ('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl ('movie/actor/delete', ['id' => $item['actor_id']]),
                    'label' => __ ('Delete'),
                    'hidden' => false
                ];
            }
        }

        return $dataSource;
    }
}