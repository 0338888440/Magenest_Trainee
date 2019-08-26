<?php

namespace Magenest\Movie\Ui\Component\Listing\MassAction;

use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;

/**
 * Class Options
 */
class Options implements JsonSerializable
{
    protected $options;
    protected $data;
    protected $urlBuilder;
    protected $urlPath;
    protected $paramName;
    protected $additionalData;

    public function __construct(
        UrlInterface $urlBuilder,
        array $data = []
    )
    {
        $this->data = $data;
        $this->urlBuilder = $urlBuilder;
    }
    public function jsonSerialize()
    {
        if ($this->options === null) {
            $options = array(
                array(
                    "value" => "1",
                    "label" => ('Enable'),
                ),
                array(
                    "value" => "2",
                    "label" => ('Disable'),
                )
            );
            $this->prepareData ();
            foreach ($options as $optionCode) {
                $this->options[$optionCode['value']] = [
                    'type' => 'status' . $optionCode['value'],
                    'label' => $optionCode['label'],
                ];

                if ($this->urlPath && $this->paramName) {
                    $this->options[$optionCode['value']]['url'] = $this->urlBuilder->getUrl (
                        $this->urlPath,
                        [$this->paramName => $optionCode['value']]
                    );
                }

//                $this->options[$optionCode['value']] = array_merge_recursive (
////                    $this->options[$optionCode['value']],
////                        $this->additionalData
////                );
            }
            $this->options = array_values ($this->options);
        }
        return $this->options;
    }

    /**
     * Prepare addition data for subactions
     *
     * @return void
     */
    protected function prepareData()
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->paramName = $value;
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}