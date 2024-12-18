<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Include the database connection file
include 'dbconnect.php';

// Initialize variables for search filters
$typeFilter = '';
$minPrice = 0;
$maxPrice = '';

// Pagination variables
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startFrom = ($currentPage - 1) * $itemsPerPage;

// Check if the form was submitted with filter values
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['type'])) {
        $typeFilter = $_GET['type'];
    }
    if (isset($_GET['min_price'])) {
        $minPrice = $_GET['min_price'];
    }
    if (isset($_GET['max_price'])) {
        $maxPrice = $_GET['max_price'];
    }
}

try {
    // Fetch filtered products from the database with pagination
    $query = "SELECT * FROM products WHERE price >= :minPrice";
    
    // Add type filter if specified
    if ($typeFilter) {
        $query .= " AND type = :type";
    }
    
    // Add max price filter if specified
    if ($maxPrice) {
        $query .= " AND price <= :maxPrice";
    }

    // Add pagination limit
    $query .= " LIMIT :startFrom, :itemsPerPage";

    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
    $stmt->bindParam(':startFrom', $startFrom, PDO::PARAM_INT);
    $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
    
    if ($typeFilter) {
        $stmt->bindParam(':type', $typeFilter, PDO::PARAM_STR);
    }

    if ($maxPrice) {
        $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total number of products for pagination
    $countQuery = "SELECT COUNT(*) FROM products WHERE price >= :minPrice";
    
    if ($typeFilter) {
        $countQuery .= " AND type = :type";
    }
    
    if ($maxPrice) {
        $countQuery .= " AND price <= :maxPrice";
    }

    $countStmt = $pdo->prepare($countQuery);
    $countStmt->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
    
    if ($typeFilter) {
        $countStmt->bindParam(':type', $typeFilter, PDO::PARAM_STR);
    }

    if ($maxPrice) {
        $countStmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
    }

    $countStmt->execute();
    $totalProducts = $countStmt->fetchColumn();
    $totalPages = ceil($totalProducts / $itemsPerPage);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Kimio Gadgets Store</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        .product-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-bottom: 10px;
        }
        .product-image {
            width: 100%;
            max-width: 200px; /* Adjust size */
            height: 150px; /* Adjust size */
            object-fit: cover; /* Maintains aspect ratio */
            border-radius: 8px; /* Rounded corners */
            margin-bottom: 10px; /* Space between image and text */
        }
        .action-buttons {
            margin-top: 15px;
        }
        .action-buttons a {
            margin-right: 10px;
        }
        .search-btn {
            background-color: #4CAF50; /* Green background */
            color: white;
            border: none;
            padding: 12px 20px;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-btn:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        .search-form input {
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="w3-bar w3-light-gray w3-card-2">
        <a href="index.php" class="w3-bar-item w3-button w3-large w3-text-dark">Kimio Gadgets Store</a>
        <div class="w3-right">
            <a href="index.php" class="w3-bar-item w3-button">Home</a>
            <a href="about.php" class="w3-bar-item w3-button">About Us</a>
            <a href="contact.php" class="w3-bar-item w3-button">Contact</a>
            <a href="logout.php" class="w3-bar-item w3-button"><?php echo $_SESSION['fullname']; ?></a>
        </div>
    </div>

    <!-- Search Form -->
<div class="w3-container w3-padding-32 w3-card-4 w3-light-grey">
    <h2 class="w3-center">Search Products</h2>
    <form action="products.php" method="GET" class="w3-padding-large">
        <div class="w3-row-padding">
            <div class="w3-third">
                <label for="type" class="w3-text-dark-grey"><strong>Product Type</strong></label>
                <input type="text" id="type" name="type" class="w3-input w3-border w3-round" placeholder="Enter product type" value="<?php echo htmlspecialchars($typeFilter); ?>">
            </div>
            <div class="w3-third">
                <label for="min_price" class="w3-text-dark-grey"><strong>Min Price (RM)</strong></label>
                <input type="number" id="min_price" name="min_price" class="w3-input w3-border w3-round" placeholder="0.00" value="<?php echo htmlspecialchars($minPrice); ?>" min="0">
            </div>
            <div class="w3-third">
                <label for="max_price" class="w3-text-dark-grey"><strong>Max Price (RM)</strong></label>
                <input type="number" id="max_price" name="max_price" class="w3-input w3-border w3-round" placeholder="0.00" value="<?php echo htmlspecialchars($maxPrice); ?>" min="0">
            </div>
        </div>
        <div class="w3-center w3-margin-top">
            <button type="submit" class="w3-button w3-green w3-large w3-round w3-padding-large">
                <i class="fa fa-search"></i> Search
            </button>
            <button type="reset" class="w3-button w3-red w3-large w3-round w3-padding-large" onclick="window.location.href='products.php'">
                <i class="fa fa-refresh"></i> Reset
            </button>
        </div>
    </form>
</div>


    <!-- Products List -->
    <div class="w3-container w3-padding-32">
        <h2 class="w3-center">Products List</h2>
        <a href="add_product.php" class="w3-button w3-blue w3-margin-bottom">Add New Product</a>
        <ul class="w3-ul w3-card-2">
            <?php if (!empty($products)) : ?>
                <?php foreach ($products as $product) : ?>
                    <li class="w3-margin-bottom">
                        <div class="product-card">
                            <img src="uploads/<?php echo isset($product['image']) ? $product['image'] : 'default.jpg'; ?>" alt="Product Image" class="product-image">
                            <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                            <p><strong>Price:</strong> RM <?php echo number_format($product['price'], 2); ?></p>
                            <p><strong>Type:</strong> <?php echo htmlspecialchars($product['type']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($product['quantity']); ?></p>
                            <div class="action-buttons">
                                <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="w3-button w3-green">View</a>
                                <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" class="w3-button w3-red">Delete</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No products found.</li>
            <?php endif; ?>
        </ul>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($page = 1; $page <= $totalPages; $page++) : ?>
                <a href="products.php?page=<?php echo $page; ?>&type=<?php echo urlencode($typeFilter); ?>&min_price=<?php echo $minPrice; ?>&max_price=<?php echo urlencode($maxPrice); ?>"><?php echo $page; ?></a>
            <?php endfor; ?>
        </div>
    </div>

     <!-- Footer Section -->
     <div class="w3-container w3-padding-16 w3-light-grey w3-center">
        <p>&copy; 2024 Kimio Gadgets Store. All rights reserved.</p>
    </div>
</body>
</html>
