<?php

class PersonMonitoring {

	//Attribute's
	protected $_id_person;
	protected $_name;
	protected $_sex;
	protected $_birth_date;
	protected $_kinship;

	//Methods
	public function setIdPerson($id_person) {
		$this->_id_person = (int) $id_person;
		return $this;
	}

	public function getIdPerson() {
		return $this->_id_person;
	}

	public function setName($name) {
		$this->_name = (string) $name;
		return $this;
	}

	public function getName() {
		return $this->_name;
	}

	public function setSex($sex) {
		$this->_sex = (string) $sex;
		return $this;
	}

	public function getSex() {
		return $this->_sex;
	}
	
	public function setBirthDate($birth_date) {
		$this->_birth_date = (string) $birth_date;
		return $this;
	}

	public function getBirthDate() {
		return $this->_birth_date;
	}
	
	public function setKinship($kinship) {
		$this->_kinship = (string) $kinship;
		return $this;
	}

	public function getKinship() {
		return $this->_kinship;
	}
}