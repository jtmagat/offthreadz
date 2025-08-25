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
    $type = $_POST["type"] ?? '';
    $gender = $_POST["gender"] ?? ''; // Gender: Male / Female
    $stock_s = $_POST["stock_s"] ?? 0;
    $stock_m = $_POST["stock_m"] ?? 0;
    $stock_l = $_POST["stock_l"] ?? 0;
    $stock_xl = $_POST["stock_xl"] ?? 0;

    $frontImageName = $_FILES['front_image']['name'] ?? '';
    $backImageName = $_FILES['back_image']['name'] ?? '';
    $sizeChartName = $_FILES['size_chart']['name'] ?? '';
    
    $frontPath = '';
    $backPath = '';
    $sizeChartPath = '';

    if (!empty($name) && !empty($desc) && !empty($price) && !empty($type) && !empty($gender)) {

        // FRONT IMAGE
        if (!empty($frontImageName)) {
            $frontImageName = basename($frontImageName);
            move_uploaded_file($_FILES['front_image']['tmp_name'], "assets/" . $frontImageName);
            $frontPath = "assets/" . $frontImageName;
        }

        // BACK IMAGE
        if (!empty($backImageName)) {
            $backImageName = basename($backImageName);
            move_uploaded_file($_FILES['back_image']['tmp_name'], "assets/" . $backImageName);
            $backPath = "assets/" . $backImageName;
        }

        // SIZE CHART IMAGE
        if (!empty($sizeChartName)) {
            $sizeChartName = basename($sizeChartName);
            move_uploaded_file($_FILES['size_chart']['tmp_name'], "assets/" . $sizeChartName);
            $sizeChartPath = "assets/" . $sizeChartName;
        }

        // Save to DB with gender
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, type, gender, stock_s, stock_m, stock_l, stock_xl, front_image, back_image, size_chart) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssiiissss", $name, $desc, $price, $type, $gender, $stock_s, $stock_m, $stock_l, $stock_xl, $frontPath, $backPath, $sizeChartPath);

        if ($stmt->execute()) {
            $product_id = $stmt->insert_id;

            // Save stock sizes
            $sizes_data = [
                ['S', $stock_s],
                ['M', $stock_m],
                ['L', $stock_l],
                ['XL', $stock_xl]
            ];
            foreach ($sizes_data as $size_row) {
                $size_stmt = $conn->prepare("INSERT INTO product_sizes (product_id, size, stock) VALUES (?, ?, ?)");
                $size_stmt->bind_param("isi", $product_id, $size_row[0], $size_row[1]);
                $size_stmt->execute();
            }

            // Additional images (optional)
            if (!empty($_FILES['images']['name'][0])) {
                $totalFiles = count($_FILES['images']['name']);
                $targetDir = "uploads/";

                for ($i = 0; $i < $totalFiles; $i++) {
                    $imageName = basename($_FILES["images"]["name"][$i]);
                    $imagePath = $targetDir . $imageName;

                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $imagePath)) {
                        $img_stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                        $img_stmt->bind_param("is", $product_id, $imagePath);
                        $img_stmt->execute();
                    }
                }
            }

            $success = "✅ Product uploaded successfully!";
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
            color: #fff;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #222;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"],
        select,
        button {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border-radius: 8px;
            border: 1px solid #333;
            background-color: #fff;
            color: #000;
            font-size: 16px;
        }

        textarea { resize: vertical; height: 100px; }

        button {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover { background-color: #333; }

        .message { padding: 12px; margin-bottom: 15px; border-radius: 6px; font-weight: bold; text-align: center; }
        .success { background-color: #14532d; color: #a7f3d0; }
        .error { background-color: #7f1d1d; color: #fecaca; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            background: #000;
            color: #fff;
            padding: 10px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .back-link:hover { background: #333; }
    </style>
</head>
<body>
<div class="container">
    <h2>Add New Product</h2>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

    <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="price" placeholder="Price" step="0.01" required>

        <!-- Product Type -->
        <label>Product Type:</label>
        <select name="type" required>
            <option value="">-- Select Type --</option>
            <option value="Shirt">Shirt / Tee</option>
            <option value="Hoodie">Hoodie</option>
            <option value="Sweater">Sweater</option>
            <option value="Pants">Pants</option>
            <option value="Sweatpants">Sweatpants</option>
            <option value="Shorts">Shorts</option>
            <option value="Cap">Cap</option>
            <option value="Accessories">Accessories</option>
        </select>

        <!-- Gender -->
        <label>Gender:</label>
        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <!-- Stocks -->
        <label>Stocks per Size:</label>
        <input type="number" name="stock_s" placeholder="Stock - Small (S)" min="0" required>
        <input type="number" name="stock_m" placeholder="Stock - Medium (M)" min="0" required>
        <input type="number" name="stock_l" placeholder="Stock - Large (L)" min="0" required>
        <input type="number" name="stock_xl" placeholder="Stock - X-Large (XL)" min="0" required>

        <!-- Images -->
        <label>Front Design Image:</label>
        <input type="file" name="front_image" accept="image/*" required>
        <label>Back Design Image:</label>
        <input type="file" name="back_image" accept="image/*" required>
        <label>Size Chart Image:</label>
        <input type="file" name="size_chart" accept="image/*" required>
        <label>Additional Images (optional):</label>
        <input type="file" name="images[]" multiple>

        <button type="submit">Upload Product</button>
    </form>
</div>
</body>
</html>
