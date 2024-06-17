<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        button[type="submit"] {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        p a {
            color: #007bff;
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro de empleados</h2>
        <form action="registro_user.php" method="POST">
            <div class="mb-3">
                <label for="companyid" class="form-label">Nombre de Compañía:</label>
                <select id="companyid" name="companyid" class="form-select" required>
                    <?php
                    // Incluir archivo de conexión
                    include 'conexion.php';

                    // Consultar las compañías disponibles
                    $sql = "SELECT CompanyID, CompanyName FROM Companies";
                    $stmt = sqlsrv_query($conn, $sql);

                    if ($stmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    // Generar opciones para el select
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value='" . $row['CompanyID'] . "'>" . $row['CompanyName'] . "</option>";
                    }

                    // Liberar recursos
                    sqlsrv_free_stmt($stmt);
                    sqlsrv_close($conn);
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Nombre del empleado:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol:</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>

        <p>¿Ya tienes una cuenta? <a href="login_users.php">Ingresa aquí</a></p>
    </div>

    <!-- Bootstrap JS (opcional, si necesitas funcionalidades como el despliegue del menú hamburguesa) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
