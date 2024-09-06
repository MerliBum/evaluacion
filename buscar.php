<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Por defecto, XAMPP usa "root"
$password = ""; // Por defecto, no hay contraseña
$dbname = "fichamedica";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el apellido a buscar
$buscarApellido = $_POST['buscarApellido'] ?? '';

if (!empty($buscarApellido)) {
    // Consultar registros por apellido
    $sql = "SELECT * FROM registro WHERE apellidos LIKE ?";
    $stmt = $conn->prepare($sql);
    $apellidoParam = "%" . $buscarApellido . "%";
    $stmt->bind_param("s", $apellidoParam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h3>Resultados de búsqueda para: " . htmlspecialchars($buscarApellido) . "</h3>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>RUT</th><th>Nombres</th><th>Apellidos</th><th>Dirección</th><th>Ciudad</th><th>Teléfono</th><th>Email</th><th>Fecha de Nacimiento</th><th>Estado Civil</th><th>Comentarios</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['rut']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombres']) . "</td>";
            echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
            echo "<td>" . htmlspecialchars($row['direccion']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fechanacimiento']) . "</td>";
            echo "<td>" . htmlspecialchars($row['estadocivil']) . "</td>";
            echo "<td>" . htmlspecialchars($row['comentarios']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron registros para el apellido: " . htmlspecialchars($buscarApellido);
    }

    // Cerrar la conexión
    $stmt->close();
} else {
    echo "Por favor, ingrese un apellido para buscar.";
}

$conn->close();
?>
