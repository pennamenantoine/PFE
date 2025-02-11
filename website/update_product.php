<?php
include "db.php";
include "navbar.php";

// Vérifiez si l'utilisateur a un rôle d'administrateur
if ($result === false) {
    header("Location: dashboard.php"); // Redirige vers le tableau de bord utilisateur si ce n'est pas un admin
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url'];
    $alt_text = $_POST['alt_text'];

// Update product in db
if ($action == 'update') {
	$stmt = $conn->prepare("UPDATE products SET product_id = :product_id, name = :name, description = :description, price = :price, stock = :stock, image_url = :image_url, alt_text = :alt_text WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product_id', $product_id);
    	$stmt->bindParam(':name', $name);
    	$stmt->bindParam(':description', $description);
    	$stmt->bindParam(':price', $price);
    	$stmt->bindParam(':stock', $stock);
    	$stmt->bindParam(':image_url', $image_url);
    	$stmt->bindParam(':alt_text', $alt_text);
    }

if ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);
    }


if ($action == 'insert') {
	$stmt = $conn->prepare("INSERT INTO products (product_id, name, description, price, stock, image_url, alt_text) VALUES (:product_id, :name, :description, :price, :stock, :image_url, :alt_text)");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':alt_text', $alt_text);

}
    // Exécuter la mise à jour et vérifier le succès
    if ($stmt->execute()) {
        header("Location: product_management.php?success=action completed successfully");
    } else {
        header("Location: product_management.php?error=An error occurred during update");
    }
} else {
    header("Location: product_management.php?error=Invalid request");
}
?>



