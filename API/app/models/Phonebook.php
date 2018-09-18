<?php

class Phonebook extends Model {

    public function sqlQuery($sql) {
        $this->db->query($sql);
        return  $this->db->resultSet();
    }

     public function delete($table, $field, $value) {
         return parent::delete($table, $field, $value);
    }

    public function update($table, $field, $value, $id)
    {
        return parent::update($table, $field, $value, $id);
    }

    public function create($table, $fields, $values, $id)
    {
        return parent::create($table, $fields, $values, $id);
    }

    public function createContact($name, $numbers) {
        return parent::createContact("'$name'", "$numbers");
    }
}
  