<?php  

namespace SainsBot;

class Product {

	private $_name;
	private $_href;
	private $_price;
	private $_page_size;
    private $_description;

	public function getName(){
        return $this->_name;
    }

    public function setName($_name){
        $this->_name = $_name;
        return $this;
    }

    public function getHref(){
        return $this->_href;
    }

    public function setHref($_href) {
        $this->_href = $_href;
        return $this;
    }

    public function getPrice() {
        return $this->_price;
    }

    public function setPrice($_price) {
        $this->_price = $_price;
        return $this;
    }

    public function getPageSize() {
        return $this->_page_size;
    }

    public function setPageSize($_page_size) {
        $this->_page_size = $_page_size;
        return $this;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function setDescription($description) {
        $this->_description = $description;
        return $this;
    }

}