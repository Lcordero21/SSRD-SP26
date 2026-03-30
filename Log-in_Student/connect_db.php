<?php
//modify these variables for your installation
$connectionString = "mysql:host=localhost;dbname=final_project";

// you may need to add this if db had UTF data

$connectionString.= ";charset=utf8mb4";
$user = "root";
$pass = "";

try{
        $pdo = new PDO($connectionString, $user,$pass);
        //print("Connection is successful");
    }
    catch(PDOException $e){
        die($e.("Error message"));
    }
?>