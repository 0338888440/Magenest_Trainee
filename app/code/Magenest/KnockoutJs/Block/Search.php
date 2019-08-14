<?php
namespace Magenest\KnockoutJs\Block;

use Magento\Framework\View\Element\Template;

class Search extends Template
{
    /**
     * @var array|\Magento\Checkout\Block\Checkout\LayoutProcessorInterface[]
     */
    protected $layoutProcessors;
    protected $_movie;

    public function __construct(
        Template\Context $context,
        \Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory $movie,
        array $layoutProcessors = [],
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
        $this->_movie = $movie;
    }

    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }
        return \Zend_Json::encode($this->jsLayout);
    }
    public function getMovie(){
        $collection = $this->_movie->create ()
            ->addFieldToSelect ('*')
            ->setOrder ('movie_id', 'ASC');
        $collection->getSelect ()->join (
            ['d' => 'magenest_director'],
            'main_table.director_id = d.director_id',
            'd.name as director_name'
        );
        return $collection;

    }

}