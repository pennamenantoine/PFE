<?php
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_FILES['files']) {
    $uploadedFiles = [];
    foreach ($_FILES['files']['name'] as $key => $name) {
        $tempName = $_FILES['files']['tmp_name'][$key];
        $uploadFile = $uploadDir . basename($name);

        // Simple validation (improve this for security!)
        $allowedExtensions = ['jpg', 'png', 'gif'];
        $fileExtension = pathinfo($name, PATHINFO_EXTENSION);

        if (!in_array($fileExtension, $allowedExtensions)) {
            echo json_encode(['error' => 'Invalid file type.']);
            exit();
        }

        if (move_uploaded_file($tempName, $uploadFile)) {
            $uploadedFiles[] = [
                'name' => $name,
                'size' => $_FILES['files']['size'][$key],
                'url' => $uploadDir . $name,
            ];
        }
    }

    // Return JSON response
    echo json_encode(['files' => $uploadedFiles]);
} else {
    echo json_encode(['error' => 'No files uploaded.']);
}
?>
