<?php
    $servername = "38.94.245.136";
    $username = "hunterolson";
    $password = "Yo-YoMan123";
    $dbName = "guessinggame";

    $conn = mysqli_connect($servername, $username, $password, $dbName);
    if($conn->connect_error)
    {
        die("Connection failed: " . mysqli_connect_error());
    }

?>