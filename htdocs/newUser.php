<?php
//Start a session
session_start();
//Connect to database 
include_once 'connect.php';

if (isset($_POST["username"]))
{
  $username = $_POST["username"];
  $password = $_POST["password"];
  $confirmPassword = $_POST["confirmPassword"];
  $findUser = "SELECT * FROM users WHERE username = '$username'";
  $users = mysqli_query($conn, $findUser);
  $isUsed = false;
  $confirm = false;
  if (mysqli_num_rows($users) > 0) 
  {
    $isUsed = true;
  }
  else if($password != $confirmPassword)
  {
    $confirm = true;
  } 
  else
  {
    $salt = random_bytes(16);
    $saltedPass = $password . $salt;
    $hashedPass = hash('sha256', $saltedPass);
    //$number = rand(1, 100);

    $insertUser = "INSERT INTO users (username, salt, hashedpass) VALUES ('$username', '$salt', '$hashedPass')";
    mysqli_query($conn, $insertUser);

    $_SESSION["username"] = $username;
    header("Location: http://yanvendercs3750.great-site.net/createGame.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">

  <title>Create Account</title>
</head>

<body style="margin-top: 1rem; background-color: gray">
  <div class="card mx-auto" style="width: 25%;">
    <h4 class="text-center" style="margin-top: .25rem">Create Account</h4>
    <div class="card-body">
      <form action="" method="POST">
        <input type="username" class="form-control" id="email" name="username" aria-describedby="emailHelp" placeholder="Enter username" autofocus>
        <?php 
        if($isUsed == true)
        {
          echo ('<p class="text-danger mx-auto">This username already exists</p>');
        }
        ?>
        <input style="margin-top: 1rem; margin-bottom: .5rem;" type="password" class="form-control" id="password" name="password" placeholder="Password">
        <?php 
        if($confirm == true)
        {
          echo ('<p class="text-danger mx-auto">Passwords do not match</p>');
        }
        ?>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
        <div style="margin-top: .5rem; margin-bottom: .5rem;"><a href="login.php" class="link-primary">Already have an Account</a></div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
  <?php
  mysqli_close($conn);
  ?>
  <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

</body>

</html>