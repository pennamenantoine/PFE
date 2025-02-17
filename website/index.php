<!DOCTYPE html>
<html lang="en">
<head>
<title>GreenWash Homepage</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="main.css">
<style>
* {
  box-sizing: border-box;
}

body {
  font-family: Arial, Helvetica, sans-serif;
  margin: 0;
}

p {
  padding: 10px 20px; /* Add padding to individual paragraphs */
  line-height: 1.6; /* Improve readability */
}

/* Responsive layout - when the screen is less than 700px wide, make the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 700px) {
  .row {   
    flex-direction: column;
  }
}

/* Responsive layout - when the screen is less than 400px wide, make the navigation links stack on top of each other instead of next to each other */
@media screen and (max-width: 400px) {
  .navbar a {
    float: none;
    width:100%;
  }
}
/* style="background-image: url('images/green-forest.jpeg'); background-size: cover; background-position: center; text-align: center; color: white; padding: 50px 0;"> */
 
</style>
</head>
<body>
 
  <div class="header"> 
    <h1>GreenWash</h1>
    <p>Innovate and create the future!</p>
  </div>

<div class="navbar">
  <a href="reviews.php">Reviews</a>
  <a href="about.html">About us</a>
  <a href="contact.html">Contact</a>


<?php
require_once "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//check if user is authentified
if (isset($_SESSION['username']))
      echo '<a href="dashboard.php" class="right">' . htmlspecialchars($_SESSION['username']) . '</a>';

else
      echo '<a href="login.html" class="right">Login</a>';

$img_dir = "images/";

$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
</div>

<div class="main">
  <h2>Choose your future solution ...</h2>
  <h5>Solar panels or wind turbines</h5>
  <div class="product-container">
    <?php foreach ($products as $product): ?>
        <div class="product">
            <img src="<?php echo htmlspecialchars($img_dir .$product['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($product['alt_text']); ?>" 
                 title="<?php echo htmlspecialchars($product['name']); ?>">
            <p><strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="price">$<?php echo htmlspecialchars($product['price']); ?>, Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
        </div>
    <?php endforeach; ?>
</div>
</div>
  <p>We are here with you to build your solution step by step</p>
  <p>Empowering a Sustainable Future with Green Energy</p>
  <p>In today’s world, sustainability is no longer a choice—it’s a responsibility. By embracing green energy solutions, you’re not just powering your operations; you’re fueling a brighter, cleaner future for generations to come.

    At GreenWash, we are committed to helping you harness the boundless potential of renewable energy. Whether it’s solar, wind, geothermal, or hybrid systems, we offer tailored solutions that align with your goals and values.

  Why choose green energy?
<br>
	  1.	Cost Efficiency: Reduce your energy bills with renewable sources that deliver long-term savings.
<br>
	  2.	Environmental Impact: Minimize your carbon footprint and contribute to the fight against climate change.
  <br>
	  3.	Energy Independence: Secure your energy needs with reliable, self-sustaining solutions.
  <br>
	  4.	Future-Ready Technology: Stay ahead with cutting-edge innovations designed to evolve with your needs.

  Our promise to you
  We provide end-to-end support, from initial consultation to system implementation and maintenance. Together, we will design a solution that integrates seamlessly into your operations while maximizing energy efficiency.

  Join us in leading the transition toward a sustainable energy future. Let’s build smarter, greener solutions that benefit not just your business but the world around us.
<br>
<br>
  Contact us today to explore how green energy can revolutionize your journey to success. Together, we can make a difference!
</p>
  </div>
</div>

</body>
</html>
