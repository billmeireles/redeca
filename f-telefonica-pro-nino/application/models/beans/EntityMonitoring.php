<?php

class EntityMonitoring{

	//Attribute's
	protected $_id_entity;
	protected $_name;
	protected $_categorys;

	//Methods
	public function setIdEntity($id_entity) {
		$this->_id_entity = (int) $id_entity;
		return $this;
	}

	public function getIdEntity() {
		return $this->_id_entity;
	}

	public function setName($name) {
		$this->_name = (string) $name;
		return $this;
	}

	public function getName() {
		return $this->_name;
	}

	public function setCategorys($categorys) {
		$this->_categorys = $categorys;
		return $this;
	}

	public function getCategorys() {
		return $this->_categorys;
	}
}