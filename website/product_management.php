<?php
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

include 'db.php';
include 'navbar.php';

// double check that user id admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
} else {
    try {
        // Fetch product information from the database
        $stmt = $conn->query("SELECT * FROM products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_stmt("Database Error: " . $e->getMessage());
        $products = [];
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style nonce="<?= $nonce; ?>">
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
	.scrollable-table {
		max-height: calc(12 * 1.5em);
		overflow-y: auto;
    form {
        margin: 0;
    }
    input[type="text"], input[type="number"] {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
    }
	}
    </style>
</head>
<body>
    <h1>Product Management</h1>

    <h2>Manage Products</h2>
    <div class="scrollable-table">
    <table>
        <tr>
            <th>Product id</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
	        <th>Image url</th>
            <th>Alt text</th>
            <th>Action</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr> 
            <td>
                <form action="update_product.php" method="POST">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <input type="text" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
            </td>
            <td><input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"></td>
            <td><input type="text" name="description" value="<?php echo htmlspecialchars($product['description']); ?>"></td>
            <td><input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>"></td>
            <td><input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>"></td>
            <td><input type="text" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>"></td>
            <td><input type="text" name="alt_text" value="<?php echo htmlspecialchars($product['alt_text']); ?>"></td>
            <td>
                <button type="submit">Update</button>
                </form>
                <form action="update_product.php" method="POST" onsubmit="return confirm('Confirm Deletion?');">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']);?>">                    
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
    <h2>Add a Product</h2>
	<form action="update_product.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']);?>"> 
        <input type="hidden" name="action" value="insert">
		<input type="text" name="product_id" placeholder="product_id" required>
		<input type="text" name="name" placeholder="name" required>
		<input type="text" name="description" placeholder="description" required>
		<input type="number" name="price" placeholder="Price" required>
		<input type="number" name="stock" placeholder="Stock" required>
		<input type="text" name="image_url" placeholder="image_url" required>
		<input type="text" name="alt_text" placeholder="alt_text" required>
		<button type="submit">Insert</button>
    	</form>
</body>
</html>
