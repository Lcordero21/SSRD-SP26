<?php 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
session_start();
//include 'data.inc.php';
require_once 'connect_db.php';


function generateOptions($val, $selected = null) {
    $tag = "";
    foreach ($val as $v) {
        $tag .= "<option value='{$v}'";
        if ($v == $selected) {
            $tag .= ' selected';
        }
        $tag .= ">{$v}</option>";
    }
    return $tag;
}

function errMessage($message){
  return "<span style='color:red; padding: 20px;'>"."**".$message."</span>";
}
function successMessage($message){
  return "<span style='color:blue;'>".$message."</span>";
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $user = $_POST["user"];
    $password = $_POST["password"];

    if(empty($user)){
      $errUser = "The email cannot be empty";
    }
    else{
      $DUser = $user;
    }
    if(empty($password)){
      $errPassword = "The password cannot be empty";
    }
    else{
      $DPassword = $password;
    }
}

if (isset($DUser)&& isset($DPassword)) {
    $sql = "SELECT * from users WHERE email = ?";
    $statement = $pdo -> prepare($sql);
    $statement -> execute([$DUser]);
    $user = $statement -> fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($DPassword, $user['password'])) {
        $_SESSION['user'] = $user['email'];
        header("Location: Student_end/homepage.php");
        exit();
    } else {
        $errLogin = "Invalid email or password.";
        }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="log_in_page.css">
    <title>Log In Page</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img class = "compass_logo" src="../images/compass-4c1.jpg"/></a>
        </div>
    </nav>

    <div class="row">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
    <form action = "log_in_page.php" method = "POST">
        <div class="mb-3">
            <label for="InputEmail" class="form-label">Email address</label>
            <span><?php if(isset($errLogin) && $errLogin !=""){print(errMessage($errLogin));}?></span><br/>
            <input type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" name= "user" required>
            <div id="emailHelp" class="form-text">Please Enter Your Willamette University Email!</div>
        </div>

        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <span><?php if(isset($errPassword) && $errPassword !=""){print(errMessage($errPassword));}?></span><br/>
            <input type="password" class="form-control" id="exampleInputPassword1" name = "password" required>
        </div>
        <span><?php if(isset($errUser) && $errUser !=""){print(errMessage($errUser));}?></span><br/>
        <button type="submit" class="btn btn-primary">Submit</button>    
    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

