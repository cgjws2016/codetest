<?php

namespace SainsBot;

class Product
{
    private $_name;
    private $_href;
    private $_price;
    private $_page_size;
    private $_description;

    public function getName()
    {
        return $this->_name;
    }

    public function setName($_name)
    {
        $this->_name = $_name;

        return $this;
    }

    public function getHref()
    {
        return $this->_href;
    }

    public function setHref($_href)
    {
        $this->_href = $_href;

        return $this;
    }

    public function getPrice()
    {
        return $this->_price;
    }

    public function setPrice($_price)
    {
        $this->_price = $_price;

        return $this;
    }

    public function getPageSize()
    {
        return $this->_page_size;
    }

    public function setPageSize($_page_size)
    {
        $this->_page_size = $_page_size;

        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setDescription($description)
    {
        $this->_description = $description;

        return $this;
    }

    public function toArray()
    {
        $output = array();
        $output['title'] = $this->getName();
        $output['size'] = $this->formatPageSize($this->getPageSize());
        $output['unit_price'] = $this->getPrice();
        $output['description'] = $this->getDescription();

        return $output;
    }

    private function formatPageSize($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2).'GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2).'MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2).'KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes.' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes.' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
