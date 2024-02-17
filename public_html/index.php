<?php session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Montclair Eats</title>
        <link rel="stylesheet" href="css/style.css"/>
    </head>
    <body>
        <div class="container">
            <div class="page-title">Montclair Eats</div>
            <form action="home.php" method="post"><br><br>
                <input type="text" name="username" placeholder="username" required><br><br>
                <input type="password" name="password" placeholder="password" required><br><br>
                <input class="center" type="submit" name="submit" value="Submit">
            </form>
        </div>
    </body>
</html>