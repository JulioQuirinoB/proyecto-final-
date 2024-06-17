<?php
include 'conexion.php';

$action = $_POST['action'];

if ($action == 'add') {
    $productname = $_POST['productname'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $company_id = $_POST['company_id'];

    $sql = "{CALL InsertProduct(?, ?, ?, ?, ?, ?)}";
    $params = array($productname, $description, $price, $stock, $company_id, 1);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Producto agregado con éxito.";
    }
    
} elseif ($action == 'modify') {
    $product_id = $_POST['product_id'];
    $productname = $_POST['productname'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $company_id = $_POST['company_id'];

    // Check if the product exists and has product_status 0
    $check_sql = "SELECT status FROM Products WHERE ProductID = ?";
    $check_params = array($product_id);
    $check_stmt = sqlsrv_query($conn, $check_sql, $check_params);
    
    if ($check_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($check_stmt, SQLSRV_FETCH_ASSOC);
    if ($row && $row['status'] == 0) {
        // Update the product status to 1
        $update_status_sql = "UPDATE Products SET status = 1 WHERE ProductID = ?";
        $update_status_stmt = sqlsrv_query($conn, $update_status_sql, $check_params);
        
        if ($update_status_stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        
        sqlsrv_free_stmt($update_status_stmt);
    }

    sqlsrv_free_stmt($check_stmt);

    // Update product details
    $sql = "UPDATE Products SET 
                ProductName = COALESCE(NULLIF(?, ''), ProductName), 
                Description = COALESCE(NULLIF(?, ''), Description), 
                Price = COALESCE(NULLIF(?, 0), Price), 
                Stock = COALESCE(NULLIF(?, 0), Stock), 
                CompanyID = COALESCE(NULLIF(?, 0), CompanyID)
            WHERE ProductID = ?";
    $params = array($productname, $description, $price, $stock, $company_id, $product_id);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Producto modificado con éxito.";
    }

} elseif ($action == 'delete') {
    $product_id = $_POST['product_id'];
    
    // Update the product status to 0 instead of deleting
    $sql = "UPDATE Products SET status = 0 WHERE ProductID = ?";
    $params = array($product_id);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Producto eliminado con éxito.";
    }
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>

<div><a href='admin.php'><button>Volver</button></a></div>
