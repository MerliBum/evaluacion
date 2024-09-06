<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Médica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 600px;
        }
        label {
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button[type="reset"] {
            background-color: #f44336;
        }
        button[type="button"] {
            background-color: #2196F3;
        }
        button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <h2>Ficha Médica</h2>
    <form action="guardar.php" method="post">
        <label for="rut">RUT:</label>
        <input type="text" id="rut" name="rut" required pattern="\d{7,8}-[\dKk]" title="Ingrese un RUT válido con guión (Ej: 12345678-9)">

        <label for="nombres">Nombres:</label>
        <input type="text" id="nombres" name="nombres" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>

        <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad" required>

        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" required pattern="\+?\d{9,12}" title="Ingrese un número de teléfono válido (Ej: +56912345678 o 0912345678)">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required title="Ingrese un email válido (Ej: ejemplo@dominio.com)">

        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fechaNacimiento" name="fechaNacimiento" required min="1900-01-01" max="<?= date('Y-m-d'); ?>" title="Ingrese una fecha de nacimiento válida">

        <label for="estadoCivil">Estado Civil:</label>
        <select id="estadoCivil" name="estadoCivil" required>
            <option value="soltero">Soltero</option>
            <option value="casado">Casado</option>
            <option value="divorciado">Divorciado</option>
            <option value="viudo">Viudo</option>
        </select>

        <label for="comentarios">Comentarios:</label>
        <textarea id="comentarios" name="comentarios"></textarea>

        <button type="submit">Guardar</button>
        <button type="reset">Limpiar</button>
        <button type="button" onclick="window.location.href='gracias.php';">Cerrar</button>
    </form>

    <h3>Buscar por Apellido</h3>
    <form action="buscar.php" method="post">
        <label for="buscarApellido">Apellido:</label>
        <input type="text" id="buscarApellido" name="buscarApellido" required>
        <button type="submit">Buscar</button>
    </form>
</body>
</html>
