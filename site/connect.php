<?php
    $servername = getenv('IP');
    $username = "projet6";
    $password = "PS9976ct";
    $database = "projet6";
    $dbport = 3306;

    // Create connection
    $BDD = new mysqli($servername, $username, $password, $database, $dbport);

    // Check connection
    if ($BDD->connect_error) {
        die("Connection failed: " . $BDD->connect_error);
    }
?>
<?php
    /*$SERVEUR = "localhost";
    $LOGIN = "root";
    $MDP = "";
    $MABASE = "projet6";
    $BDD = mysqli_connect($SERVEUR,$LOGIN,$MDP,$MABASE);*/
?>
