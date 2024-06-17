<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT UserID, PasswordHash, Role FROM Users WHERE UserName = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user && password_verify($password, $user["PasswordHash"])) {
        session_start();
        $_SESSION["userid"] = $user["UserID"];
        $_SESSION["role"] = $user["Role"];
        $_SESSION["username"] = $username;
        
        if ($user["Role"] == "admin") {
                header('Location: admin.php');
        } else {
                header('Location: trabajador.php');
        }
    } else {
        echo "Nombre de usuario o contraseña incorrectos. <a href='login_user.html'>Inténtalo de nuevo</a>";
    }

    sqlsrv_free_stmt($stmt);
}

sqlsrv_close($conn);
?>
