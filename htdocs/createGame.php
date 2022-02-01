<?php
    //Start a session
    session_start();
    //Connect to database
    include_once 'connect.php';
    $number = rand(1, 100);
    $_SESSION["number"] = $number;
    $username = $_SESSION["username"];
    $insertGame = "INSERT games(username, actualNumber) VALUES('$username', '$number')";
    mysqli_query($conn, $insertGame);
    $latestRecord = "SELECT gameId FROM games ORDER BY gameId DESC LIMIT 1";
    $record = mysqli_query($conn, $latestRecord);
    if (mysqli_num_rows($record) > 0)
    {
        $row = mysqli_fetch_assoc($record);
        $_SESSION["gameId"] = $row["gameId"];
    }
    mysqli_close($conn);
    header("Location: http://yanvendercs3750.great-site.net/gameindex.php");
    exit();
?>
