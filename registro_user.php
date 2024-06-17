<?php

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyID = $_POST["companyid"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Encriptar contraseña
    $email = $_POST["email"];
    $role = $_POST["role"];

    $sql = "INSERT INTO Users (CompanyID, UserName, PasswordHash, Email, status, Role) VALUES (?, ?, ?, ?, ?)";
    $params = array($companyID, $username, $password, $email, $role, $status);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    $row = sqlsrv_fetch_array($check_stmt, SQLSRV_FETCH_ASSOC);
    if ($row && $row['status'] == 0) {
        // Update the product status to 1
        $update_status_sql = "UPDATE Companies SET status = 1 WHERE companyID = ?";
        $update_status_stmt = sqlsrv_query($conn, $update_status_sql, $check_params);
        
        if ($update_status_stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        
        sqlsrv_free_stmt($update_status_stmt);
    }

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Registro exitoso. <a href=login_users.php>Ingresa aquí</a>";
    }

    sqlsrv_free_stmt($stmt);
}

sqlsrv_close($conn);
?>
