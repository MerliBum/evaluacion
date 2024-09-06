<?php
// Función para validar RUT Chileno
function validarRut($rut) {
    if (!preg_match('/^\d{7,8}-[\dKk]$/', $rut)) {
        return false;
    }
    list($numero, $dv) = explode('-', $rut);
    $suma = 0;
    $factor = 2;
    for ($i = strlen($numero) - 1; $i >= 0; $i--) {
        $suma += $numero[$i] * $factor;
        $factor = $factor == 7 ? 2 : $factor + 1;
    }
    $dv_calculado = 11 - ($suma % 11);
    if ($dv_calculado == 11) {
        $dv_calculado = 0;
    } elseif ($dv_calculado == 10) {
        $dv_calculado = 'K';
    }
    return strtoupper($dv) == $dv_calculado;
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fichamedica";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$rut = $_POST['rut'] ?? null;
$nombres = $_POST['nombres'] ?? null;
$apellidos = $_POST['apellidos'] ?? null;
$direccion = $_POST['direccion'] ?? null;
$ciudad = $_POST['ciudad'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$email = $_POST['email'] ?? null;
$fechanacimiento = $_POST['fechaNacimiento'] ?? null; 
$estadocivil = $_POST['estadoCivil'] ?? null; 
$comentarios = $_POST['comentarios'] ?? null;
$sobrescribir = $_POST['sobrescribir'] ?? null;

// Validar los datos antes de guardar
if (empty($rut) || !validarRut($rut)) {
    die("RUT inválido.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Correo electrónico inválido.");
}
if (!preg_match('/^\+?\d{9,12}$/', $telefono)) {
    die("Número de teléfono inválido.");
}
if (empty($fechanacimiento) || strtotime($fechanacimiento) > strtotime(date('Y-m-d'))) {
    die("Fecha de nacimiento inválida.");
}

// Verificar si ya existe un registro con ese RUT
$sql = "SELECT * FROM registro WHERE rut = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rut);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0 && !$sobrescribir) {
    echo "<h3>El RUT ya existe. ¿Deseas sobrescribir el registro?</h3>";
    echo "<form action='guardar.php' method='post'>";
    echo "<input type='hidden' name='rut' value='" . htmlspecialchars($rut) . "'>";
    echo "<input type='hidden' name='nombres' value='" . htmlspecialchars($nombres) . "'>";
    echo "<input type='hidden' name='apellidos' value='" . htmlspecialchars($apellidos) . "'>";
    echo "<input type='hidden' name='direccion' value='" . htmlspecialchars($direccion) . "'>";
    echo "<input type='hidden' name='ciudad' value='" . htmlspecialchars($ciudad) . "'>";
    echo "<input type='hidden' name='telefono' value='" . htmlspecialchars($telefono) . "'>";
    echo "<input type='hidden' name='email' value='" . htmlspecialchars($email) . "'>";
    echo "<input type='hidden' name='fechaNacimiento' value='" . htmlspecialchars($fechanacimiento) . "'>";
    echo "<input type='hidden' name='estadoCivil' value='" . htmlspecialchars($estadocivil) . "'>";
    echo "<input type='hidden' name='comentarios' value='" . htmlspecialchars($comentarios) . "'>";
    echo "<input type='hidden' name='sobrescribir' value='sí'>";
    echo "<button type='submit' name='sobrescribir' value='sí'>Sí, sobrescribir</button>";
    echo "<button type='button' onclick=\"window.location.href='index.php'\">No, volver al formulario</button>";
    echo "</form>";
    exit;
}

if ($sobrescribir === 'sí') {
    $sql = "UPDATE registro SET 
                nombres = ?, 
                apellidos = ?, 
                direccion = ?, 
                ciudad = ?, 
                telefono = ?, 
                email = ?, 
                fechanacimiento = ?, 
                estadocivil = ?, 
                comentarios = ? 
            WHERE rut = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $nombres, $apellidos, $direccion, $ciudad, $telefono, $email, $fechanacimiento, $estadocivil, $comentarios, $rut);
    
    if ($stmt->execute()) {
        echo "Registro sobrescrito exitosamente"; 
    } else {
        echo "Error al sobrescribir el registro: " . $stmt->error;
    }
} else {
    $sql = "INSERT INTO registro (rut, nombres, apellidos, direccion, ciudad, telefono, email, fechanacimiento, estadocivil, comentarios)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $rut, $nombres, $apellidos, $direccion, $ciudad, $telefono, $email, $fechanacimiento, $estadocivil, $comentarios);
    
    if ($stmt->execute()) {
        echo "Registro guardado exitosamente"; 
    } else {
        echo "Error al guardar el registro: " . $stmt->error;
    }
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
