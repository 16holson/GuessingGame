<?php
    //Start a session
    session_start();
    include_once 'connect.php';

    $userInData = true;
    $corrPass = true;

    if(isset($_POST["username"]))
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $findUser = "SELECT * FROM users WHERE username = '$username'";
        $user = mysqli_query($conn, $findUser);


        if(mysqli_num_rows($user) > 0)
        {
            $row = mysqli_fetch_assoc($user);
            $databasePass = $row["hashedpass"];
            $salt =$row["salt"];

            $saltedPass = $password . $salt;
            $hashedPass = hash('sha256', $saltedPass);
            if($hashedPass == $databasePass)
            {
                $_SESSION["username"] = $username;
                header("Location: http://yanvendercs3750.great-site.net/createGame.php");
                exit();
            }
            else
            {
                $corrPass = false;
            }
        }
        else
        {
            $userInData = false;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">

        <title>Login</title>
    </head>

    <body style="margin-top: 1rem; background-color: gray">
    <div class="card mx-auto" style="width: 25%;">
        <h4 class="text-center" style="margin-top: .25rem">Sign In</h4>
        <div class="card-body">
            <form action="" method="POST">
                <input type="username" class="form-control" id="email" name="username" aria-describedby="emailHelp" placeholder="Enter Username" autofocus>
                <?php
                    if($userInData == false)
                    {
                        echo ('<p class="text-danger mx-auto">This username does not exists</p>');
                    }
                ?>
                <input style="margin-top: 1rem; margin-bottom: .5rem" type="password" class="form-control" id="password" name="password" placeholder="Password">
                <?php
                if($corrPass == false)
                {
                    echo ('<p class="text-danger mx-auto">Incorrect password</p>');
                }
                ?>
                <div style="margin-top: .25rem; margin-bottom: .25rem;"><a href="newUser.php" class="link-primary">Create Account</a></div>
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
