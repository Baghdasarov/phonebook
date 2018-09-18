<?php
class Database {

    private $host   = DB_HOST;
    private $user   = DB_USER;
    private $pass   = DB_PASS;
    private $dbname = DB_NAME;
    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            $this->migrate();
        } catch(PDOException $e){
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function update($table, $field, $value, $id){
        return $this->dbh->exec("UPDATE $table SET $field=$value WHERE id=$id");
    }

    public function delete($table, $field, $value){
        if ($table == 'numbers') {
            $this->query("SELECT * FROM $table WHERE id=$value");
            $row = $this->resultSet();
            $contact_id = $row[0]->contact_id;
            $this->query("SELECT * FROM $table WHERE contact_id=$contact_id");
            $rows = $this->resultSet();
            if (count($rows) > 1) {
                return $this->dbh->exec("DELETE FROM $table WHERE $field = $value");
            } else {
                return $this->update($table, 'number', "''", $value);
            }
        } else {
            return $this->dbh->exec("DELETE FROM $table WHERE $field = $value");
        }
    }

    public function create($table, $fields, $values, $id){
        if ($table == 'numbers') {
            $this->query("SELECT * FROM $table WHERE contact_id=$id AND number=''");
            $rows = $this->resultSet();
            if (count($rows) === 0) {
                $create = $this->dbh->exec("INSERT INTO $table ($fields) VALUES ($values)");
                return $create ? $this->dbh->lastInsertId() : false;
            } else {
                $valuesArray = explode(', ', $values);
                $this->update($table, 'number', $valuesArray[1], $rows[0]->id);
                return $rows[0]->id;
            }
        } else {
            $create = $this->dbh->exec("INSERT INTO $table ($fields) VALUES ($values)");
            return $create ? $this->dbh->lastInsertId() : false;
        }
    }

    public function createContact($name, $numbers){
        $create = $this->dbh->exec("INSERT INTO contacts (name) VALUES ($name);
        INSERT INTO numbers (contact_id, number) VALUES $numbers");

        return $create ? true : false;
    }

    public function execute(){
        return $this->stmt->execute();
    }

    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    private function migrate() {
        $tables[] = 'CREATE TABLE IF NOT EXISTS contacts (
          id INT NOT NULL AUTO_INCREMENT,
          name VARCHAR(50) NOT NULL,
          created_at TIMESTAMP NOT NULL DEFAULT NOW(),
          updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE now(),
          PRIMARY KEY (id)
        ) ENGINE=InnoDB';

        $tables[] = 'CREATE TABLE IF NOT EXISTS numbers (
          id INT NOT NULL AUTO_INCREMENT,
          contact_id INT,
          number VARCHAR(20) NOT NULL,
          created_at TIMESTAMP NOT NULL DEFAULT NOW(),
          updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE now(),
          PRIMARY KEY (id),
          INDEX par_ind (contact_id),
          FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE
        ) ENGINE=InnoDB';

        foreach ($tables as $createTableSql) {
            $this->dbh->query($createTableSql);
        }
    }
}
  