<?php
class Model {

    protected $db;

    public function __construct() {
      $this->db = new \Database();
    }

    public function create($table, $fields, $values, $id) {
        return  $this->db->create($table, $fields, $values, $id);
    }

    public function createContact($name, $numbers) {
        return  $this->db->createContact($name, $numbers);
    }

    public function update($table, $field, $value, $id) {
        return  $this->db->update($table, $field, $value, $id);
    }

    public function delete($table, $field, $value) {
        return  $this->db->delete($table, $field, $value);
    }
}
