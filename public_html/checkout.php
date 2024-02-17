<?php require_once("login.php");?>
    <form action="complete.php" method="POST">
        <?php
            require_once("modules/Connection.php");
            $connection = new Connection();
            $connection = $connection->connect();
            
            if(isset($_SESSION["cart_id"])) {
                
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                 
                    $stmt = $connection->prepare("INSERT INTO deliveries
        	            (delivery_id, delivery_time)
                    VALUES
        	            (NULL, \"" . $_POST["delivery"] . "\");");
        	        $stmt->execute();
        	        
        	        $stmt = $connection->prepare("SELECT delivery_id FROM deliveries ORDER BY delivery_id desc LIMIT 0,1");
        	        $stmt->execute();
        	        
        	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        	        
        	        $stmt = $connection->prepare("UPDATE carts SET delivery_id = " . $row["delivery_id"] . " WHERE cart_id = " . $_SESSION["cart_id"] . ";");
        	        $stmt->execute();
                    
                }
                
                echo "<table class=\"container\" style='border: solid 1px black;'>";
                echo "<tr><th>Name</th><th>Image</th><th>Price</th><th>Quantity</th><th>Select</th></tr>";
                
                $stmt = $connection->prepare("SELECT p.name, p.price, count(i.product_id) as quantity FROM products p
                    join inventory i on p.product_id = i.product_id
                    WHERE i.cart_id = " . $_SESSION["cart_id"] . " GROUP BY i.product_id;");
                $stmt->execute();
                
                $total = 0;
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $name = $row['name'];
                    $price = $row['price'];
                    $quantity = $row['quantity'];
                    $cost = $row['price'] * $row['quantity'];
                    $total += $cost;
                    echo "<tr><td>$name</td><td><img src=\"img/" . str_replace(" ","_",strtolower($name)) . ".jpg\" width=\"100\"></td><td>\$$price</td><td>$quantity</td><td>\$" . number_format($cost, 2) . "</td></tr>";
                }
                
                echo "<tr><td></td><td></td><td></td><td><strong>Total</strong></td><td>\$" . number_format($total, 2) . "</td></tr>";
                
                echo "</table>";
                echo "<br><div class=\"center\">" .
                    "<label for=\"delivery\">Delivery date " . $_POST["delivery"] . "</label><br><br>" .
				    "<input class=\"center\" type=\"submit\" name=\"submit\" value=\"Purchase\">" .
				"</div>";
                
                
            } else {
                
                echo "products not selected";
                
            }
           
        ?>
    </body>
    </form>
</html>