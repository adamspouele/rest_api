<?php
namespace App\Controller;

use App\Database\Database;

class TaskController {

    public function getOne($id){
        $result = Database::getInstance()->getTask($id);

        echo json_encode($result);
    }

    public function getTasksFromUser($userId){
        $result = Database::getInstance()->getTasksFromUser($userId);
        echo json_encode($result);
    }

    public function add(){
        $userId = $_POST['userId'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        if(!isset($userId) || !isset($description) || !isset($status))
            $result = false;
        else
            $result = Database::getInstance()->addTask($userId, $description, $status);

        echo json_encode($_POST);
    }

    public function delete($id){
        $result = Database::getInstance()->deleteTask($id);

        echo json_encode($result);
    }

}