<?php
include('./server/connection.php');

if (!isset($_GET['query']) || strlen(trim($_GET['query'])) < 1) {
    exit;
}

$query = trim($_GET['query']);
$query = strtolower($query);

$stmt = $conn->prepare("SELECT id, name, image_url, price, stock, type FROM products WHERE LOWER(name) LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param("s", $query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $addUrl = 'addtocart_search.php?add=' . ($row["id"]) . '&redirect=' . urlencode($_SERVER['REQUEST_URI']);
        echo '
        <div class="block p-2 hover:bg-pink-100 border-b border-gray-100 flex items-center gap-2 relative">
          <a href="product_detail.php?id=' . $row["id"] . '" class="flex items-center gap-2 flex-grow">
            <img src="' . htmlspecialchars($row["image_url"]) . '" class="w-10 h-10 object-cover rounded" alt="' . htmlspecialchars($row["name"]) . '">
            <div>
              <div class="font-semibold text-sm text-gray-800">' . htmlspecialchars($row["name"]) . '</div>
              <div class="text-xs text-pink-500">$' . number_format($row["price"], 2) . '</div>
              <div class="text-xs text-gray-500">Stock: ' . intval($row["stock"]) . ' | Type: ' . htmlspecialchars($row["type"]) . '</div>
            </div>
          </a>
          <a href="' . $addUrl . '" 
             class="ml-2 flex-shrink-0 bg-pink-500 hover:bg-pink-600 text-white text-xs px-2 py-1 rounded inline-flex items-center justify-center"
             title="Add to cart">
            Add
          </a>
        </div>';
    }
} else {
    echo '<div class="p-2 text-sm text-gray-500">No products found</div>';
}

$stmt->close();
?>
