<?php
namespace App\Controller;

class IndexController {

    public function show(){
        ?>

        <html>
        <head>
        <title>Api Rest</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <style>
            p, h1, h2, h3{
                font-family: 'Roboto', sans-serif;
            }

            .text-center{text-align: center;}

            #title{
                margin-top: 4em;
            }
        </style>
        </head>
        <body>
            <h1 id="title" class="text-center">Api REST</h1>
            <h2>Gestion des utilisateurs :</h2>
            <button id="add-user">Ajouter un utilisateur</button>
            <form id="add-user-form" action="/user/add" method="post" style="display: none">
                <input type="text" name="name" placeholder="nom">
                <input type="email" name="email" placeholder="email">
                <button type="submit">Enregistrer</button>
            </form>
            <h3>Liste des utilisateurs :</h3>
            <ul id="users-list">
            </ul>
            <h2>Gestion des tâches</h2>
            <form id="add-task-form" action="/task/add" method="post" style="display: none">
                <p></p>
                <input type="hidden" name="userId" value="secret" />
                <textarea name="description" rows="4" cols="50" >Description de la tâche..</textarea> <br>
                <input typeof="text" name="status" placeholder="status">
                <button type="submit">Enregistrer</button>
            </form>
            <h3>Liste des tâches :</h3>
            <ul id="tasks-list">
            </ul>

<!--        --><?php
//        $url = 'http://username:password@hostname:9090/path?arg=value#anchor';
//
//        var_dump(parse_url($url));
//        ?>

        </body>
        <script>
            $(document).ready(function(){
                var $newUserForm = $('#add-user-form');
                var $newTaskForm = $('#add-task-form');

                getAllUsers();

                function getAllUsers() {
                    $.get("/api/v1/users", function(data, status){
                        var $usersArea = $("#users-list");
                        var result = JSON.parse(data);

                        $usersArea.empty();

                        for (var key in result) {
                            if (result.hasOwnProperty(key)) {
                                $usersArea.append("<li> nom : "+ result[key]['name'] +
                                    " <br> email : "
                                    + result[key]['email']
                                    + " <button class='show-user-tasks-btn' data-userid='" + result[key]['id'] + "'> Voir ses tâches</button>"
                                    + "<button class='add-task-btn' data-userid='" + result[key]['id'] + "' data-useremail='" + result[key]['email'] + "'> Ajouter une tâche</button>"
                                    + "<button class='delete-user-btn' data-userid='" + result[key]['id'] + "'>Supprimer </button>"
                                    + "</li>");
                            }
                        }
                        return result;
                    });
                }

                function getUserTasks(userId) {
                    $.get("/api/v1/user/" + userId + "/tasks", function(data, status){
                        var $tasksArea = $("#tasks-list");
                        var result = JSON.parse(data);

                        $tasksArea.empty();

                        for (var key in result) {
                            if (result.hasOwnProperty(key)) {
                                $tasksArea.append("<li> Status : "+ result[key]['status'] +
                                    " <br> Description : "
                                    + result[key]['description']
                                    + "<button class='delete-task-btn' data-taskid='" + result[key]['id'] + "'>Supprimer </button>"
                                    + "</li>");
                            }
                        }
                        return result;
                    });
                }

                $("#add-user").click(function(){
                    $newUserForm.toggle();
                });

                $(document).on("click", ".delete-user-btn", function(){
                    var $tasksArea = $("#tasks-list");
                    $.post("/api/v1/user/delete/" +$(this).data('userid') , {},
                        function(data, status){
                            getAllUsers();
                            $tasksArea.empty();
                    });
                });

                $(document).on("click", ".delete-task-btn", function(){
                    var $tasksArea = $("#tasks-list");

                    $.post("/api/v1/task/delete/" +$(this).data('taskid') , {},
                        function(data, status){
                            getAllUsers();
                            $tasksArea.empty();
                        });
                });

                $(document).on("click", ".show-user-tasks-btn", function(){
                    var userId = $(this).data('userid');
                    getUserTasks(userId);
                });

                $(document).on("click", ".add-task-btn", function(){
                    var $usersFormData = $("#add-task-form input[type='hidden']");
                    var userId = $(this).data('userid');
                    var userEmail = $(this).data('useremail');
                    $usersFormData.attr('value', userId);
                    getAllUsers();
                    $newTaskForm.find('p').html('Utilisateur : ' + userEmail);
                    $newTaskForm.toggle();
                });

                $newUserForm.children("button").click(function(e){
                    e.preventDefault();
                    $.post("/api/v1/user/add",
                        {
                            name: $newUserForm.children("input[name='name']").val(),
                            email: $newUserForm.children("input[name='email']").val()
                        },
                        function(data, status){
                            $newUserForm.hide();
                            getAllUsers();
                    });
                });

                $newTaskForm.children("button").click(function(e){
                    e.preventDefault();
                    $.post("/api/v1/task/add",
                        {
                            userId: $newTaskForm.children("input[name='userId']").val(),
                            description: $newTaskForm.children("textarea[name='description']").val(),
                            status: $newTaskForm.children("input[name='status']").val()
                        },
                        function(data, status){
                            $newTaskForm.hide();
                            getAllTasks();
                    });
                });

            });
        </script>
        </html>

        <?php 
    }
}