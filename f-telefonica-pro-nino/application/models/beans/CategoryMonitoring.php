<?php

class CategoryMonitoring {

	//Attribute's
	protected $_id_category;
	protected $_name;
	protected $_address_type;
	protected $_address;
	protected $_number;
	protected $_complement;
	protected $_neighborhood;
	protected $_city;
	protected $_uf_abbreviation;
	protected $_total;
	protected $_persons;

	//Methods
	public function setIdCategory($id_category) {
		$this->_id_category = (int) $id_category;
		return $this;
	}

	public function getIdCategory() {
		return $this->_id_category;
	}

	public function setName($name) {
		$this->_name = (string) $name;
		return $this;
	}

	public function getName() {
		return $this->_name;
	}

	public function setAddressType($address_type) {
		$this->_address_type = (string) $address_type;
		return $this;
	}

	public function getAddressType() {
		return $this->_address_type;
	}

	public function setAddress($address) {
		$this->_address = (string) $address;
		return $this;
	}

	public function getAddress() {
		return $this->_address;
	}
	
	public function setNumber($number) {
		$this->_number = (int) $number;
		return $this;
	}

	public function getNumber() {
		return $this->_number;
	}
	
	public function setComplement($complement) {
		$this->_complement = (string) $complement;
		return $this;
	}

	public function getComplement() {
		return $this->_complement;
	}

	public function setNeighborhood($neighborhood) {
		$this->_neighborhood = (string) $neighborhood;
		return $this;
	}

	public function getNeighborhood() {
		return $this->_neighborhood;
	}

	public function setCity($city) {
		$this->_city = (string) $city;
		return $this;
	}

	public function getCity() {
		return $this->_city;
	}
	
	public function setUfAbbreviation($uf_abbreviation) {
		$this->_uf_abbreviation = (string) $uf_abbreviation;
		return $this;
	}

	public function getUfAbbreviation() {
		return $this->_uf_abbreviation;
	}
	
	public function setTotal($total) {
		$this->_total = (string) $total;
		return $this;
	}

	public function getTotal() {
		return $this->_total;
	}
	
	public function setPersons($persons) {
		$this->_persons = $persons;
		return $this;
	}

	public function getPersons() {
		return $this->_persons;
	}
}