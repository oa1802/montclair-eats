<?php require_once("login.php");?>
    <form action="checkout.php" method="POST">
            <?php
                require_once("modules/Connection.php");
                $connection = new Connection();
                $connection = $connection->connect();
                
                if(isset($_SESSION["cart_id"])) {
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
                        echo "<tr><td>$name</td><td><img src=\"img/" . str_replace(" ","_",strtolower($name)) . ".jpg\" width=\"200\"></td><td>\$$price</td><td>$quantity</td><td>\$" . number_format($cost, 2) . "</td></tr>";
                    }
                    
                    echo "<tr><td></td><td></td><td></td><td><strong>Total</strong></td><td>\$" . number_format($total, 2) . "</td></tr>";
                    
                    echo "</table>";
                    echo "<br><div class=\"center\">" . 
                        "<label for=\"delivery\">Delivery date:</label>" .
                        "<input type=\"date\" id=\"delivery\" name=\"delivery\"><br><br>" .
    				    "<input type=\"submit\" name=\"submit\" value=\"Submit\">" .
    				"</div>";
                    
                } else {
                    
                    echo "<div class=\"center\"><h1>Please select products</h1></div>";
                    
                }
               
            ?>
            <script>
                var today = new Date();
                today.setDate(today.getDate() + 2);
                today = today.toISOString().split('T')[0];
                var max = new Date();
                max.setDate(max.getDate() + 21);
                max = max.toISOString().split('T')[0];
                console.log(max);
                document.getElementsByName("delivery")[0].setAttribute('min', today);
                document.getElementsByName("delivery")[0].setAttribute('max', max);
            </script>
        </body>
    </form>
</html>