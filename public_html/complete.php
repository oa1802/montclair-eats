<?php require_once("login.php");?>
<html>
        <?php
            require_once("modules/Connection.php");
            $connection = new Connection();
            $connection = $connection->connect();
            
            if(isset($_SESSION["cart_id"])) {
                
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	        
        	        $stmt = $connection->prepare("SELECT delivery_id FROM deliveries ORDER BY delivery_id desc LIMIT 0, 1");
        	        $stmt->execute();
        	        
        	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        	        
        	        $stmt = $connection->prepare("UPDATE carts SET delivery_id = " . $row["delivery_id"] . ", status = \"inactive\" WHERE cart_id = " . $_SESSION["cart_id"] . ";");
        	        $stmt->execute();
        	        
        	        $stmt = $connection->prepare("INSERT INTO orders (order_id, user_id) VALUES (NULL, 0);");
        	        $stmt->execute();
        	        
        	        $stmt = $connection->prepare("SELECT order_id FROM orders ORDER BY order_id desc LIMIT 0, 1");
        	        $stmt->execute();
        	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        	        
        	        $row["order_id"];
        	        $stmt = $connection->prepare("UPDATE inventory SET order_id = " . $row["order_id"] . " WHERE cart_id = " . $_SESSION["cart_id"] . ";");
        	        $stmt->execute();
                    
                    echo "<script>document.getElementsByTagName(\"li\")[5].style=\"background-color:#184d47;\"</script>";
                    $_SESSION["cart_id"] = NULL;
                }
                
                echo "<div class=\"center\"><h1>Purchase complete</h1></div>";
                
            } else {
                
                echo "<div class=\"center\"><h1>Products not selected</h1></div>";
                
            }
           
        ?>
    </body>
</html>