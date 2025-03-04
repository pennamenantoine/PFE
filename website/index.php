<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "db.php";

//check if user is authentified
$user_link = isset($_SESSION['username']) 
    ? '<a href="dashboard.php" class="right">' . htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') . '</a>' 
    : '<a href="login.html" class="right">Login</a>';

$img_dir = "./images/";

try {
  // Fetch product information from the database
  $stmt = $conn->query("SELECT * FROM products");
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_stmt("Database Error: " . $e->getMessage());
  $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>GreenWash Homepage</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="main.css">
    <style nonce="<?= htmlspecialchars($nonce ?? '', ENT_QUOTES, 'UTF-8'); ?>">
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

        /* Responsive layout - make columns stack on small screens */
        @media screen and (max-width: 700px) {
            .row {
                flex-direction: column;
            }
        }

        @media screen and (max-width: 400px) {
            .navbar a {
                float: none;
                width: 100%;
            }
        }
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
        <?= $user_link; ?>
    </div>

    <div class="main">
        <h2>Choose your future solution ...</h2>
        <h5>Solar panels or wind turbines</h5>

        <div class="product-container">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <img src="<?= htmlspecialchars($img_dir . $product['image_url'], ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?= htmlspecialchars($product['alt_text'], ENT_QUOTES, 'UTF-8'); ?>" 
                             title="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
                        <p><strong><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                        <p><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="price">
                            $<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?>, 
                            Stock: <?= htmlspecialchars($product['stock'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products available at the moment.</p>
            <?php endif; ?>
        </div>

        <p>We are here with you to build your solution step by step</p>
        <p>Empowering a Sustainable Future with Green Energy</p>

        <p>
            In today’s world, sustainability is no longer a choice—it’s a responsibility. By embracing green energy solutions, you’re not just powering your operations; you’re fueling a brighter, cleaner future for generations to come.

            At GreenWash, we are committed to helping you harness the boundless potential of renewable energy. Whether it’s solar, wind, geothermal, or hybrid systems, we offer tailored solutions that align with your goals and values.

            <br><br>
            <strong>Why choose green energy?</strong><br>
            1. Cost Efficiency: Reduce your energy bills with renewable sources that deliver long-term savings.<br>
            2. Environmental Impact: Minimize your carbon footprint and contribute to the fight against climate change.<br>
            3. Energy Independence: Secure your energy needs with reliable, self-sustaining solutions.<br>
            4. Future-Ready Technology: Stay ahead with cutting-edge innovations designed to evolve with your needs.
            <br><br>

            <strong>Our promise to you:</strong><br>
            We provide end-to-end support, from initial consultation to system implementation and maintenance. Together, we will design a solution that integrates seamlessly into your operations while maximizing energy efficiency.
            <br><br>

            Join us in leading the transition toward a sustainable energy future. Let’s build smarter, greener solutions that benefit not just your business but the world around us.
            <br><br>

            <strong>Contact us today</strong> to explore how green energy can revolutionize your journey to success. Together, we can make a difference!
        </p>

    </div>

</body>

</html>
