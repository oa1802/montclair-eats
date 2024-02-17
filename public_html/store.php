<?php require_once("login.php");?>
<html>
    <div class="container">
            <?php
                
                $product_category = null;
                $product_selected = false;
                
                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    $product_category = $_GET["product_category"];   
                }
            
                require_once("modules/Connection.php");
                $connection = new Connection();
                $connection = $connection->connect();
            
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    
                    $product_category = $_POST["product_category"];
                    
                    if(!isset($_SESSION["cart_id"])) {
                        $stmt = $connection->prepare("INSERT INTO carts
            	            (cart_id, status, user_id, delivery_id)
                        VALUES
            	            (NULL, \"active\", 0, 0);");
            	        $stmt->execute();
            	        
            	        $stmt = $connection->prepare("SELECT * FROM carts ORDER BY cart_id desc LIMIT 0,1");
            	        $stmt->execute();
            	        
            	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
            	        $_SESSION["cart_id"] = $row['cart_id'];
        	            
        	            if($_SESSION["type"] !== "manager" && $_SESSION["type"] !== "associate") {
        	                
        	                echo "<script>document.getElementsByTagName(\"li\")[5].style=\"background-color:orange;\"</script>";
        	                
        	            }
        	            
                    }
        	        
        	        try {
        	        
            	        foreach($_POST as $key => $value) {  
                            if($key != "submit" && $key != "product_category" && $value > 0) {
                                $product_selected = true;
                                $stmt = $connection->prepare("SELECT product_id FROM products WHERE name LIKE \"" . str_replace("_"," ","$key") . "\"");
                	            $stmt->execute();
                	            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                for ($x = 0; $x < $value; $x++) {
                                    $stmt = $connection->prepare("UPDATE inventory SET cart_id = " . $_SESSION["cart_id"] . " WHERE product_id = " . $row['product_id'] . " AND cart_id = 0 LIMIT 1;");
            	                    $stmt->execute();
                                } 
                                
                            }
                                
                        }
                    
        	        } catch(Exception $e) {
        	            
                        echo $e->getMessage();
                        echo $stmt;
                    }
    
    			}
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
    			<?php
    			
    			    if ($product_selected) {
        		        echo "<br>Added to cart!<br><br>";
        		    }
                    echo "<table class=\"container\" style='border: solid 1px black;'>";
                    if($_SESSION["type"] == "admin") {
                    
                        echo "<tr><th>Name</th><th>Image</th><th>Price</th><th>Quantity</th><th>Select</th><th>Refill</th></tr>";
                    
                    } else if ($_SESSION["type"] == "manager") {
                            
                        echo "<tr><th>Name</th><th>Image</th><th>Price</th><th>Quantity</th><th>Refill</th></tr>";
 
                    } else if($_SESSION["type"] == "associate") {
                        
                        echo "<tr><th>Name</th><th>Image</th><th>Price</th><th>Quantity</th></tr>";

                    } else {
                    
                        echo "<tr><th>Name</th><th>Image</th><th>Price</th><th>Select</th></tr>";

                    }
                    
                    $stmt = $connection->prepare("SELECT p.name, p.price, count(i.product_id) as quantity FROM products p
                        join inventory i on p.product_id = i.product_id
                        WHERE p.type = \"$product_category\" and i.cart_id = 0
                        GROUP BY i.product_id;");
                    $stmt->execute();
                    
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $name = $row['name'];
                        $price = $row['price'];
                        $quantity = $row['quantity'];
                        if($_SESSION["type"] == "admin")  {
                            
                            echo "<tr><td>$name</td><td><img src=\"img/" . str_replace(" ","_",strtolower($name)) . ".jpg\" width=\"200\"></td><td>\$$price</td><td>$quantity</td><td><input type=\"number\" id=\"" . strtolower($name) . "\" name=\"" . strtolower($name) . "\"min=\"0\" max=\"10\" value=\"0\"></td>" .
                            "<td><form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method=\"POST\"><button name=\"refill\">Refill</button><input style=\"display:none;\" type=\"text\" name=\"product_category\" value=\"$product_category\">" ./*<input style=\"display:none;\" type=\"text\" name=\"added_to_cart\" value=\"false\">*/
                            "<input style=\"display:none;\" type=\"text\" name=\"product\" value=\"" . strtolower($name) . "\">" .
                            "</form></td></tr>";
                            
                        } else if($_SESSION["type"] == "manager") {
                            
                            echo "<tr><td>$name</td><td><img src=\"img/" . str_replace(" ","_",strtolower($name)) . ".jpg\" width=\"200\"></td><td>\$$price</td><td>$quantity</td>" .
                            "<td><form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method=\"POST\"><button name=\"refill\">Refill</button><input style=\"display:none;\" type=\"text\" name=\"product_category\" value=\"$product_category\">" ./*<input style=\"display:none;\" type=\"text\" name=\"added_to_cart\" value=\"false\">*/
                            "<input style=\"display:none;\" type=\"text\" name=\"product\" value=\"" . strtolower($name) . "\">" .
                            "</form></td></tr>";
                            
                        } else if($_SESSION["type"] == "associate") {
                            
                            echo "<tr><td>$name</td><td><img src=\"img/" . str_replace(" ","_",strtolower($name)) . ".jpg\" width=\"200\"></td><td>\$$price</td><td>$quantity</td></tr>";
                            
                        } else {
                        
                            echo "<tr><td>$name</td><td><img src=\"img/" . str_replace(" ","_",strtolower($name)) . ".jpg\" width=\"200\"></td><td>\$$price</td><td><input type=\"number\" id=\"" . strtolower($name) . "\" name=\"" . strtolower($name) . "\"min=\"0\" max=\"10\" value=\"0\"></td></tr>";
                    
                        }
                        
                    }
                    
                    echo "</table>";
                    
                    if(!($_SESSION["type"] == "associate") && !($_SESSION["type"] == "manager")) {

                        echo "<input style=\"display:none;\" type=\"text\" name=\"selected\" value=\"true\">";
                        echo "<input style=\"display:none;\" type=\"text\" name=\"product_category\" value=\"$product_category\"><br><br>";
    				    echo "<input class=\"center\" type=\"submit\" name=\"submit\" value=\"Submit\">";
                        
                    }
    			?>
    		</form>   
    		<?php
    		    function refill() { 

    		        $connection = new Connection();
                    $connection = $connection->connect();
                    
                    $stmt = $connection->prepare("SELECT product_id FROM products WHERE name = \"" . $_POST["product"] . "\"");
            	    $stmt->execute();
            	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
            	    
            	    $stmt = $connection->prepare("INSERT INTO inventory" .
                    	"(inventory_id, product_id, cart_id, order_id)" .
                    "VALUES" .
                    	"(NULL, " . $row["product_id"] . ", 0, 0)," .
                    	"(NULL, " . $row["product_id"] . ", 0, 0)," .
                    	"(NULL, " . $row["product_id"] . ", 0, 0)," .
                    	"(NULL, " . $row["product_id"] . ", 0, 0)," .
                    	"(NULL, " . $row["product_id"] . ", 0, 0)," .
                    	"(NULL, " . $row["product_id"] . ", 0, 0)," .
                    	"(NULL, " . $row["product_id"] . ", 0, 0)," .
                    	"(NULL, " . $row["product_id"] . ", 0, 0)," .
                    	"(NULL, " . $row["product_id"] . ", 0, 0)," .
                    	"(NULL, " . $row["product_id"] . ", 0, 0);");
            	    $stmt->execute();
                    
                    $connection = null;
                    
    		    }
                if (isset($_REQUEST['refill'])) {
                    refill();
                    $_REQUEST['refill'] = false;
                    
                }
    			$connection = null;
    		?>
    	</div>
    </body>
</html>