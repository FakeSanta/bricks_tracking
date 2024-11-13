<?php
session_start();

define('dbhost', 'localhost');
define('dbuser', 'root');
define('dbpass', '');
define('dbname', 'bricks_tracking');

function dbConnect(){
    // Connecting database
    try {
        $connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connect;
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }
}

function register($login, $password, $mail) {
    $db = dbConnect();
    $query = $db->prepare("INSERT INTO users (login, password, mail) VALUES (?,?,?)");
    $query->execute([$login, $password, $mail]);
}

function login($mail, $password) {
    $db = dbConnect();
    $query = "SELECT * FROM users WHERE mail = :mail";  
    $statement = $db->prepare($query);  
    $statement->execute(array('mail' => $mail));
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        var_dump($row['password']); // Affiche le mot de passe haché stocké
        var_dump($password);        // Affiche le mot de passe saisi par l'utilisateur
        var_dump(password_verify($password, $row['password'])); // Affiche le résultat de la vérification

        if (password_verify($password, $row['password'])) {
            header('Location: index.php');
        } else {
            //header('Location: indexx.php');
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
    exit();
  }