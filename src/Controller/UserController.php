<?php
namespace App\Controller;

use App\Database\Database;

class UserController {

    public function getOne($id){
        $result = Database::getInstance()->getUser($id);

        echo json_encode($result);
    }

    public function add(){

        $name = $_POST['name'];
        $email = $_POST['email'];

        if(!isset($name) || !isset($email))
            $result = false;
        else
            $result = Database::getInstance()->addUser($name, $email);

        echo json_encode($result);
    }

    public function getAll(){
        $result = Database::getInstance()->getAllUsers();

        echo json_encode($result);
    }

    public function delete($id){
        $result = Database::getInstance()->deleteUser($id);

        echo json_encode($result);
    }
}