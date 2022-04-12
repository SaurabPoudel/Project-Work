<?php
    session_start();
    // Check if user is already logged in 
    if(isset($_SESSION['username'])){
        header("location: welcome.php");
        exit;
    }

    require_once('config.php');
    $username = $password = "";
    $username_err = $password_err = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(empty(trim($_POST['username'])) || empty(trim($_POST['password']))){
            $err = "Please enter username + password";
        }else{
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
        }
        if(empty($err)){
            $sql = "SELECT id, username, password FROM users WHERE username = ?";
            $stmt = mysqli_prepare($conn,$sql);
            mysqli_stmt_bind_param($stmt,"s",$param_username);
            $param_username = $username;
            // Try to execute this statement
            if(mysqli_stmt_execute($stmt))
            {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt)==1){
                    mysqli_stmt_bind_result($stmt,$id,$username,$hashed_password);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($password,$hashed_password)){
                            // password is correct. Allow user to login i.e. start the login session
                            session_start();
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;
                            // Redirect user to home page 
                            header("location: welcome.php");
                        }
                    }
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login for Babbler</title>
    <link rel="shortcut icon" type="image/png" href="images/favicon.png">
    <link rel="stylesheet" href="style/register.css">
</head>
<body>
<nav>
		<ul>
			<li><a href="welcome.php">home</a></li>
			<li><a href="register.php">Register</a></li>
			<li><a href="#">Game</a></li>
			<li><a href="login.php" class="active">Login</a></li>
		</ul>
</nav>
    <div class="container">
    <div class="info">
    <h3>Please Login here </h3>
    </div>
    <br>
   <form action="" method="POST">
   <label for="username">Username:</label>
   <input type="text" name= "username" placeholder="Username" required>
    <br><br>
   <label for="password">Password:</label>
   <input type="password" name= "password" placeholder="Password" required>
   <br><br>
   <button type="submit" >Sign in</button>
   </form>  
    </div>
</body>
</html>