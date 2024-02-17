<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Montclair Eats</title>
        <link rel="stylesheet" href="css/style.css"/>
    </head>
    <body>
        <div class="parallax">
			<div class="page-title">Montclair Eats</div>
        </div>
        <div class="menu" id="sticky">
			<ul class = "menu-ul">
				<a href="home.php" class="a-menu"><li>Home</li></a>
				<a href="store.php?product_category=produce" class="a-menu"><li>Produce</li></a>
				<a href="store.php?product_category=frozen_foods" class="a-menu"><li>Frozen Foods</li></a>
				<a href="store.php?product_category=snacks" class="a-menu"><li>Snacks</li></a>
				<a href="store.php?product_category=canned_foods" class="a-menu"><li>Canned Foods</li></a>
				<?php
				    if(($_SESSION["type"] !== "associate") && ($_SESSION["type"] !== "manager")) {
				        if(isset($_SESSION["cart_id"])) {
    				        echo "<a href=\"cart.php\" class=\"a-menu\"><li class=\"cart\">Cart</li></a>";
        				} else {
        				    echo "<a href=\"cart.php\" class=\"a-menu\"><li>Cart</li></a>";
        				}
				    }
				?>
				<a href="about.php" class="a-menu"><li>About Us</li></a>
				<?php
				    if(($_SESSION["type"] == "associate") || ($_SESSION["type"] == "manager") || ($_SESSION["type"] == "admin")) {
    				    echo "<a href=\"users.php\" class=\"a-menu\"><li>Users</li></a>";
				    }
				?>
			</ul>
        </div>
