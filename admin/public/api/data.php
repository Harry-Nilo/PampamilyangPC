<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

include __DIR__ . '/connect.php';

if (!$connect) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

function getTopCustomersByPurchases($limit = 5) {
    global $connect;
    $sql = "SELECT CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
                   COUNT(o.order_id) AS purchases
            FROM customer c
            INNER JOIN `order` o ON c.customer_id = o.customer_id
            GROUP BY c.customer_id, c.first_name, c.last_name
            ORDER BY purchases DESC
            LIMIT ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getMonthlySales() {
    global $connect;
    $sql = "SELECT 
                DATE_FORMAT(MIN(order_date), '%b %Y') AS month,
                SUM(total_amount) AS total
            FROM `order`
            GROUP BY YEAR(order_date), MONTH(order_date)
            ORDER BY YEAR(order_date), MONTH(order_date)";
    $result = $connect->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getProductsByCategory() {
    global $connect;
    $sql = "SELECT 
                c.category_name,
                SUM(CASE WHEN p.product_condition = 'New' THEN 1 ELSE 0 END) AS new,
                SUM(CASE WHEN p.product_condition = 'Used' THEN 1 ELSE 0 END) AS used
            FROM products p
            INNER JOIN category c ON p.category_id = c.category_id
            GROUP BY c.category_id, c.category_name";
    
    $result = $connect->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}



function getDashboardStats() {
    global $connect;
    $stats = [];

    $stats['total_revenue']   = $connect->query("SELECT COALESCE(SUM(total_amount),0) FROM `order`")->fetch_row()[0];
    $stats['total_orders']    = $connect->query("SELECT COUNT(*) FROM `order`")->fetch_row()[0];
    $stats['total_customers'] = $connect->query("SELECT COUNT(*) FROM customer")->fetch_row()[0];
    $stats['total_products']  = $connect->query("SELECT COUNT(*) FROM products")->fetch_row()[0];

    return $stats;
}

function getAllProducts() {
    global $connect;
    $sql = "SELECT 
                p.product_id,
                p.product_name,
                p.brand,
                p.model_number,
                p.price,
                p.stock_quantity,
                p.product_condition,
                c.category_name
            FROM products p
            INNER JOIN category c ON p.category_id = c.category_id";
    
    $result = $connect->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getCategories() {
    global $connect;
    $sql = "SELECT category_id, category_name, description FROM category";
    $result = $connect->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Handle actions
$action = $_GET['action'] ?? 'all';

switch ($action) {
    case 'top_customers':
        echo json_encode(getTopCustomersByPurchases(5));
        break;
    case 'monthly_sales':
        echo json_encode(getMonthlySales());
        break;
    case 'products_by_category':
        echo json_encode(getProductsByCategory());
        break;
    case 'stats':
        echo json_encode(getDashboardStats());
        break;
    case 'products':
        echo json_encode(getAllProducts());
        break;
    case 'categories':
        echo json_encode(getCategories());
        break;
    case 'all':
    default:
        echo json_encode([
            'top_customers'        => getTopCustomersByPurchases(5),
            'monthly_sales'        => getMonthlySales(),
            'products_by_category' => getProductsByCategory(),
            'stats'                => getDashboardStats(),
            'products'             => getAllProducts(),
            'categories'           => getCategories()
        ]);
        break;
}
?>


