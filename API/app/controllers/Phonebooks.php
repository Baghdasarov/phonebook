<?php

class Phonebooks extends Controller {

    protected $model;

    public function __construct() {
        $this->model = $this->model('Phonebook');
    }

    public function index() {
        $result = $this->getContacts();
        echo json_response($result, 'OK', 200);
    }

    public function show() {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $result = $this->getContacts("contacts.id=$id");

        if($result) {
            echo json_response($result, "OK");
        } else {
            echo json_response($result, "Sorry, something went wrong!", 400);
        }
    }

    public function store() {
        $data = json_decode(file_get_contents("php://input"));
        $name = isset($data->name) ? $data->name : '';
        $numbersArray = isset($data->numbers) ? $data->numbers : [];
        if($name == '' && count($numbersArray) == 0) {
            echo json_response("What did you think about when try to create NOTHING?!", 400);
            return;
        }

        $numbers = '';
        $msgNumbers = '';
        if (count($numbersArray) != 0) {
            for ($i=0; $i<count($numbersArray); $i++) {
                $number = ($numbersArray[$i] != '') ? $numbersArray[$i] : NULL;
                $numbers .= "(LAST_INSERT_ID(),'".$number."'),";
                $msgNumbers .= $numbersArray[$i]. ', ';
            }
            $numbers=rtrim($numbers,",");
            $msgNumbers=rtrim($msgNumbers,", ");
        } else {
            $numbers = "(LAST_INSERT_ID(),'')";
        }
        $result = $this->model->createContact($name, $numbers);

        if($result) {
            $msg = "Contact ". ($name!='' ? "with name $name" : "without name") ." and ". ($msgNumbers!='' ? "with $msgNumbers" : "without") ." phone number(s) is created.";
            echo json_response([], $msg);
        } else {
            echo json_response([], "Sorry, something went wrong!", 400);
        }
    }

    public function addNumber() {
        $data = json_decode(file_get_contents("php://input"));
        $table = 'numbers';
        $id = $data->id;
        $number = $data->number;
        $values = "$id, '$number'";
        $fields = "contact_id, number";
        $result = $this->model->create($table, $fields, $values, $id);

        if($result) {
            echo json_response($result, "Updated successfully!");
        } else {
            echo json_response($result, "Sorry, something went wrong!", 400);
        }
    }

    public function update() {
        $data = json_decode(file_get_contents("php://input"));
        $table = $data->name;
        $field = $data->field;
        $value = "'$data->value'";
        $id = $data->id;
        $result = $this->model->update($table, $field, $value, $id);

        if($result) {
            echo json_response($result, "Updated successfully!");
        } else {
            echo json_response($result, "Sorry, something went wrong!", 400);
        }
    }

    public function delete() {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $table = $data->name;
        $result = $this->model->delete($table, 'id', $id);

        if($result) {
            echo json_response($result, "OK");
        } else {
            echo json_response($result, "Sorry, something went wrong!", 400);
        }
    }

    public function search() {
        $data = json_decode(file_get_contents("php://input"));
        $keyWord = isset($data->keyWord) ? $data->keyWord : '';
        if ($keyWord) {
            $result = $this->getContacts("contacts.name LIKE '%$keyWord%' OR numbers.number LIKE '%$keyWord%'");
            if($result) {
                echo json_response($result, "OK");
            } else {
                echo json_response($result, "Sorry, we didn't find anything match your search!", 404);
            }
        } else {
            echo json_response([], "Sorry, but you haven't keyword!", 404);
        }
    }

    private function getContacts($condition = false) {
        $sql = "SELECT contacts.id, contacts.name, numbers.id AS number_id, numbers.number FROM contacts LEFT JOIN numbers ON numbers.contact_id = contacts.id";
        if ($condition) {
            $sql .= ' WHERE ' . $condition;
        }
        return $this->model->sqlQuery($sql);
    }
}
