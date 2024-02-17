<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		require_once("modules/Connection.php");
        $connection = new Connection();
        $connection = $connection->connect();
        
        if(isset($_POST['username']) && isset($_POST['password'])) {
        
            $stmt = $connection->prepare("SELECT email, password FROM users WHERE email = \"" . $_POST['username'] . "\" AND password = \"" . md5($_POST['password']) . "\";");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($_POST['username'] == $row["email"] && md5($_POST['password']) == $row["password"]) {
                
                    $_SESSION["username"] = $_POST['username'];
                    $_SESSION["password"] = md5($_POST['password']);
                   
                    $stmt = $connection->prepare("SELECT e.type FROM users u JOIN employees e ON u.user_id = e.user_id WHERE u.email = \"" . $_POST['username'] . "\" AND u.password = \"" . md5($_POST['password']) . "\";");
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                   
                   $_SESSION["type"] = $row["type"];
                   
                   require_once("header.php");
                   
            } else {
                
                echo "<h1>Bad credentials. Please try again.</h1><br>";
                echo "<button><a href=\"index.php\">Go back<a/></button>";
                exit();
                
            }
            
        } else {
     
            if(!isset($_SESSION["username"]) || !isset($_SESSION["password"])) {
                
                echo "<h1>Please log in.</h1><br>";
                echo "<button><a href=\"index.php\">Login<a/></button>";
                exit();
                
            } else {
                
                require_once("header.php");
    
            }
        }
        
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
     
        if(!isset($_SESSION["username"]) || !isset($_SESSION["password"])) {
            
            echo "<h1>Please log in.</h1><br>";
            echo "<button><a href=\"index.php\">Login<a/></button>";
            exit();
            
        } else {
            
            require_once("header.php");

        }
    }
?>