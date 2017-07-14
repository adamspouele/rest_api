<?php

namespace App\Database;

class Database{

    private static $_instance = null;

    private $servername = "localhost";
    private $username = "username";
    private $password = "password";
    private $dbname = "apirest_database";

    public $connection = null;

    private function __construct(){
    }

    public static function getInstance() {

        if(is_null(self::$_instance)) {
            self::$_instance = new Database();
        }

        return self::$_instance;
    }

    public function connect($servername, $username, $password){
        if(!$this->connection) {
            try {
                $this->servername = $servername;
                $this->username = $username;
                $this->password = $password;

                $this->connection = new \PDO('mysql:dbname='.$this->dbname.';host'.$servername, $username, $password);
                $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                return true;
            } catch (\PDOException $e) {
                echo "Erreur de connexion.";

                return false;
            }
        }
        return false;
    }

    public function init(){
        if($this->connection)
        {
            $sql = "CREATE DATABASE IF NOT EXISTS apirest_database";
            if($this->connection->exec($sql)){
                $this->createTables();
                return true;
            }
            else{
                return false;
            }
        }
        return false;
    }

    private function createTables(){
        if($this->connection)
        {
            $sql = "use $this->dbname; 
                CREATE TABLE IF NOT EXISTS User (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30),
                email VARCHAR(30)
            );";

            $this->connection->exec($sql);

            $sql = "use $this->dbname;
                CREATE TABLE IF NOT EXISTS Task (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT(30) NOT NULL,
                description VARCHAR(5000),
                creation_date TIMESTAMP,
                status VARCHAR(30)
            );";

            $this->connection->exec($sql);

            return true;
        }
        return false;
    }

    public function addUser($name, $email){
        if($this->connection)
        {
            $sql = "SELECT * FROM User WHERE email = :email";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if(count($result) == 0 ) {
                $sql = "
                INSERT INTO User (name, email)
                VALUES (:name, :email);";

                $stmt = $this->connection->prepare($sql);

                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);

                $stmt->execute();
            }

            return true;
        }
        return false;
    }

    public function getUser($id)
    {
        if($this->connection)
        {
            $sql = "SELECT * FROM User WHERE id = :id";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetch();

            return $result;
        }
        return null;
    }

    public function getAllUsers()
    {
        if($this->connection)
        {
            $sql = "SELECT * FROM User";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();

            return $result;
        }
        return null;
    }

    public function deleteUser($id){
        if ($this->connection)
        {
            $sql = "DELETE FROM Task
                WHERE user_id = :id";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            $sql = "DELETE FROM User
                WHERE id = :id";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            return true;
        }
        return false;
    }

    public function addTask($userId, $description, $status){
        if($this->connection)
        {
            $sql = "
            INSERT INTO Task (user_id, description, creation_date, status)
            VALUES (:userid, :description, NOW(), :status);";

            $stmt = $this->connection->prepare($sql);

            $stmt->bindParam(':userid', $userId);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':status', $status);

            $stmt->execute();

            return true;
        }
        return false;
    }

    public function getTasksFromUser($id){
        if($this->connection)
        {
            $sql = "SELECT * FROM Task WHERE user_id = :id";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();

            return $result;
        }
        return null;
    }

    public function getTask($id)
    {
        if($this->connection)
        {
            $sql = "SELECT * FROM Task WHERE id = :id";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetch();

            return $result;
        }
        return null;
    }

    public function deleteTask($id){
        if ($this->connection)
        {
            $sql = "DELETE FROM Task
                WHERE id = :id";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            return true;
        }
        return false;
    }


}