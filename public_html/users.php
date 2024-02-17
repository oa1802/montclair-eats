<?php require_once("login.php");?>
<html>
        <?php
            require_once("modules/Connection.php");
            $connection = new Connection();
            $connection = $connection->connect();
            
            echo "<div class=\"center\"><h1>Users</h1></div>";
            
            echo "<table class=\"container\" style='border: solid 1px black;'>";
            echo "<tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Employee Type</th></tr>";
            
	        $stmt = $connection->prepare("SELECT u.user_id, u.first_name, u.last_name, u.email, e.type FROM users u LEFT JOIN employees e on u.user_id = e.user_id ORDER BY u.user_id");
	        $stmt->execute();
	        
	        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	            $user_id = $row['user_id'];
    	        $first_name = $row['first_name'];
    	        $last_name = $row['last_name'];
    	        $email = $row['email'];
    	        $type = $row['type'];
	            echo "<tr><td>$user_id</td><td>$first_name</td><td>$last_name</td><td>$email</td><td>$type</td></tr>";
	        }
    	        
            if ($_SESSION["type"] == "admin") {
             
                echo "<div class=\"center\" style=\"width: 100%\">" .
                    "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"POST\">" .
                        "First Name <input type=\"text\" name=\"first_name\" required>" .
                        "    Last Name <input type=\"text\" name=\"last_name\" required>" .
                        "    Email <input type=\"email\" name=\"email\" required>" .
                        "    Password <input type=\"password\" name=\"password\" required>" .
                        "    Employee <select name=\"type\" id=\"type\" required>" .
                          "<option value=\"none\">None</option>" .
                          "<option value=\"associate\">Associate</option>" .
                          "<option value=\"manager\">Manager</option>" .
                          "<option value=\"admin\">Admin</option>" .
                        "</select><br><br>" .
                        "<input type=\"submit\"><br><br>" .
                    "</form>" .
                "</div>";
                
            }
            
            if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["type"] == "admin") {
    	        $first_name = $_POST['first_name'];
    	        $last_name = $_POST['last_name'];
    	        $email = $_POST['email'];
    	        $password = $_POST['password'];
    	        $type = $_POST['type'];
    	        
                $stmt = $connection->prepare("INSERT INTO users (user_id, first_name, last_name, email, password) VALUES (NULL, \"$first_name\", \"$last_name\", \"$email\", \"" . md5($password) . "\");");
	            $stmt->execute();
	            
	            echo "<div class=\"center\" style=\"width: 100%\">User created!</div><br>";
	            
	            if($_POST['type'] != "none") {
	                
    	            $stmt = $connection->prepare("SELECT user_id FROM users WHERE first_name = \"$first_name\" AND last_name = \"$last_name\" AND email = \"$email\"");
    	            $stmt->execute();
    	            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    	            $user_id = $row["user_id"];
    	            
    	            $stmt = $connection->prepare("INSERT INTO employees (employee_id, type, user_id) VALUES (NULL, \"$type\", \"$user_id\")");
    	            $stmt->execute();   
	            }
            }
           
        ?>
        
    </body>
</html>