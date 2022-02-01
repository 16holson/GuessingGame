<?php
//Start a session
    session_start();
    include_once 'connect.php';
    $username = $_SESSION["username"];
    $toHigh = 0;
    $needSubmit = true;
    if(isset($_POST["Guess"]))
    {
        $guess = $_POST["Guess"];
        $number = $_SESSION["number"];
        $gameId = $_SESSION["gameId"];

        $insertGameRound = "INSERT INTO gameRounds (gameId, guess) VALUES ('$gameId', '$guess')";
        $getNumRounds = "SELECT COUNT(*) AS count FROM gameRounds AS GR WHERE gameId = '$gameId' GROUP BY '$gameId'";
        $getTopTen = "SELECT G.username, GR.gameId, COUNT(*) AS score, ROW_NUMBER() OVER (ORDER BY COUNT(*)) 'rank' FROM games AS G JOIN gameRounds GR on G.gameId = GR.gameId GROUP BY GR.gameId, G.username ORDER BY score LIMIT 10";
        mysqli_query($conn, $insertGameRound);
        if($guess > $number)
        {
            $toHigh = 1;
        }
        elseif($guess < $number)
        {
            $toHigh = 2;
        }
        else
        {
            $toHigh = 3;
        }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <title>Game</title>
</head>
    <body style="margin-top: 1rem; background-color: gray">
    <div class="card mx-auto" style="width: 25%">
        <div class="card-body">
        <a style="float: right" class='btn btn-sm btn-secondary' href='login.php'>Logout<br></a>
        <form action="" method="post">
            <h5 style="text-align: center" class="card-title">Welcome to the Guessing Game <?=$username?> <br></h5>
            <p style="margin-top: 1rem" class="card-text">Your Guess: <input type="text" name="Guess" autofocus/></p>
            <?php
                if($toHigh == 1)
                {
                    echo ('<p class="card-text">To high</p>');
                }
                elseif($toHigh == 2)
                {
                    echo ('<p class="card-text">To low</p>');
                }
                elseif($toHigh == 3)
                {
                    $needSubmit = false;
                    $findGuesses = mysqli_query($conn, $getNumRounds);
                    if (mysqli_num_rows($findGuesses) > 0)
                    {
                        $row = mysqli_fetch_assoc($findGuesses);
                        $numRounds = $row["count"];
                        $topTen = mysqli_query($conn, $getTopTen);
                        ?>
                        <p>Correct<br>Score: <?=$numRounds?></p>

                        <table class="table table-striped">
                            <caption style="text-align:center; caption-side: top; font-size: 2rem">Top 10 Scores</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Rank</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Score</th>
                                </tr>
                            </thead>

                        <?php

                        while($row = mysqli_fetch_assoc($topTen))
                        {
                            $user = $row["username"];
                            $score = $row["score"];
                            $rank = $row["rank"];
                            echo "<tr scope='row'><td>$rank</td><td>$user</td><td>$score</td></tr>";
                        }
                    }
                }
            ?>
            </table>

            <?php
                if($needSubmit)
                {
                    echo '<input type="submit" value="Submit"/>';
                }
                else
                {
                    echo "<a class='btn btn-outline-primary' href='createGame.php'>New Game<br></a>";
                }
            ?>

        </form>
        </div>
    </div>
    <?php
    mysqli_close($conn);
    ?>
        <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>