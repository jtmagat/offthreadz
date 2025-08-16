<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to DB
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? '';
    $desc = $_POST["description"] ?? '';
    $price = $_POST["price"] ?? '';

    $frontImageName = $_FILES['front_image']['name'] ?? '';
    $backImageName = $_FILES['back_image']['name'] ?? '';
    $frontPath = '';
    $backPath = '';

    if (!empty($name) && !empty($desc) && !empty($price)) {

        // FRONT IMAGE
        if (!empty($frontImageName)) {
            $frontImageName = basename($frontImageName); // keep extension like .jpeg
            move_uploaded_file($_FILES['front_image']['tmp_name'], "assets/" . $frontImageName);
            $frontPath = "assets/" . $frontImageName;
        }

        // BACK IMAGE
        if (!empty($backImageName)) {
            $backImageName = basename($backImageName); // keep extension like .jpeg
            move_uploaded_file($_FILES['back_image']['tmp_name'], "assets/" . $backImageName);
            $backPath = "assets/" . $backImageName;
        }

        // Save to DB
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, front_image, back_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $name, $desc, $price, $frontPath, $backPath);

        if ($stmt->execute()) {
            $product_id = $stmt->insert_id;

            // Additional images
            $totalFiles = count($_FILES['images']['name']);
            $targetDir = "uploads/";

            for ($i = 0; $i < $totalFiles; $i++) {
                $imageName = basename($_FILES["images"]["name"][$i]);
                $imagePath = "uploads/" . $imageName;

                if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $targetDir . $imageName)) {
                    $img_stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                    $img_stmt->bind_param("is", $product_id, $imagePath);
                    $img_stmt->execute();
                }
            }

            $success = "✅ Product and images uploaded successfully!";
        } else {
            $error = "❌ Failed to insert product.";
        }
    } else {
        $error = "❌ Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"],
        button {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border-radius: 8px;
            border: 1px solid #444;
            background-color: #181717ff;
            color: #ffffff;
            font-size: 16px;
        }

        input::placeholder,
        textarea::placeholder {
            color: #888;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            background-color: #00bfff;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #009acd;
        }

        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
        }

        .success {
            background-color: #14532d;
            color: #a7f3d0;
        }

        .error {
            background-color: #7f1d1d;
            color: #fecaca;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #00bfff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Product</h2>

           <a href="dashboard.php" class="logout-btn" style="position: static; background: #000000ff;">← Back to Dashboard</a>

        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <label>Stocks per Size:</label>
            <input type="number" name="stock_s" placeholder="Stock - Small (S)" min="0" required>
            <input type="number" name="stock_m" placeholder="Stock - Medium (M)" min="0" required>
            <input type="number" name="stock_l" placeholder="Stock - Large (L)" min="0" required>
            <input type="number" name="stock_xl" placeholder="Stock - X-Large (XL)" min="0" required>


            <label>Front Design Image:</label>
            <input type="file" name="front_image" accept="image/*" required>

            <label>Back Design Image:</label>
            <input type="file" name="back_image" accept="image/*" required>

            <label>Additional Images (optional):</label>
            <input type="file" name="images[]" multiple>

            <button type="submit">Upload Product</button>
        </form>

    </div>
</body>
</html>
