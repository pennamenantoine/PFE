<?php
include 'db.php';
include 'navbar.php';

// Vérifiez si l'utilisateur a un rôle d'administrateur
if ($result === false) { 
    header("Location: dashboard.php"); // Redirige vers le tableau de bord utilisateur si ce n'est pas un admin
    exit();
} else {
    // Récupérer tous les produits
    $stmt = $conn->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
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
	}
    </style>
</head>
<body>
    <h1>Product Management</h1>

    <h2>Manage Products</h2>
    <div class="scrollable-table">
    <table>
        <tr>
            <th>id</th>
            <th>product_id</th>
            <th>name</th>
            <th>description</th>
            <th>price</th>
            <th>stock</th>
	    <th>image_url</th>
            <th>alt_text</th>
            <th>Action</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo $product['product_id']; ?></td>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo $product['description']; ?></td>
            <td><?php echo $product['price']; ?></td>
            <td><?php echo $product['stock']; ?></td>
            <td><?php echo $product['image_url']; ?></td>
            <td><?php echo $product['alt_text']; ?></td>
            <td>
		<form action="update_product.php" method="POST">
                   	<input type="hidden" name="action" value="update">
                    	<input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    	<button type="submit">Update</button>
                </form>
                <form action="update_product.php" method="POST" onsubmit="return confirm('Confirm Deletion ?');">
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
