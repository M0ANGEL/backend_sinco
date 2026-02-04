<?php
echo "=== DIAGNÓSTICO CONEXIÓN SQL SERVER ===\n\n";

// 1. Información de extensiones
echo "1. EXTENSIONES SQLSRV:\n";
if (extension_loaded('sqlsrv')) {
    echo "   - sqlsrv: CARGADA ✓\n";
    echo "   - Versión: " . (phpversion('sqlsrv') ?: 'Desconocida') . "\n";
} else {
    echo "   - sqlsrv: NO CARGADA ✗\n";
}

if (extension_loaded('pdo_sqlsrv')) {
    echo "   - pdo_sqlsrv: CARGADA ✓\n";
    echo "   - Versión: " . (phpversion('pdo_sqlsrv') ?: 'Desconocida') . "\n";
} else {
    echo "   - pdo_sqlsrv: NO CARGADA ✗\n";
}

// 2. Verificar funciones
echo "\n2. FUNCIONES DISPONIBLES:\n";
echo "   - sqlsrv_connect: " . (function_exists('sqlsrv_connect') ? "SÍ ✓" : "NO ✗") . "\n";
echo "   - PDO SQLSRV: " . (in_array('sqlsrv', PDO::getAvailableDrivers()) ? "SÍ ✓" : "NO ✗") . "\n";

// 3. Intentar conexión simple
echo "\n3. PRUEBA DE CONEXIÓN:\n";

// Cambia estos valores según tu servidor SQL
$serverName = "localhost"; // o "NOMBRE_SERVIDOR\INSTANCIA"
$connectionInfo = array(
    "Database" => "master",
    "UID" => "sa",
    "PWD" => "tu_password",
    "CharacterSet" => "UTF-8",
    "ReturnDatesAsStrings" => true,
    "MultipleActiveResultSets" => false,
    "ConnectionPooling" => true,
    "LoginTimeout" => 5
);

echo "   Intentando conectar a: $serverName\n";

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    echo "   ERROR DE CONEXIÓN ✗\n\n";
    $errors = sqlsrv_errors();
    echo "   Detalles del error:\n";
    foreach ($errors as $error) {
        echo "   - SQLSTATE: " . $error['SQLSTATE'] . "\n";
        echo "   - Código: " . $error['code'] . "\n";
        echo "   - Mensaje: " . $error['message'] . "\n";
        echo "   ------------------------------------\n";
    }
    
    // Información adicional
    echo "\n   INFORMACIÓN ADICIONAL:\n";
    echo "   - PHP Version: " . PHP_VERSION . "\n";
    echo "   - Thread Safe: " . (ZEND_THREAD_SAFE ? "SÍ" : "NO") . "\n";
    echo "   - Architecture: " . (PHP_INT_SIZE * 8) . "-bit\n";
} else {
    echo "   CONEXIÓN EXITOSA! ✓\n";
    
    // Probar consulta simple
    $sql = "SELECT @@VERSION as version";
    $stmt = sqlsrv_query($conn, $sql);
    
    if ($stmt === false) {
        echo "   Error en consulta:\n";
        print_r(sqlsrv_errors());
    } else {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo "   Versión SQL Server: " . $row['version'] . "\n";
        sqlsrv_free_stmt($stmt);
    }
    
    sqlsrv_close($conn);
}

// 4. Probar con PDO como alternativa
echo "\n4. PRUEBA CON PDO:\n";
try {
    $pdo = new PDO("sqlsrv:Server=$serverName;Database=master", "sa", "tu_password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   PDO Conexión exitosa! ✓\n";
    
    $version = $pdo->query("SELECT @@VERSION")->fetchColumn();
    echo "   Versión SQL Server (PDO): " . substr($version, 0, 50) . "...\n";
    
} catch (PDOException $e) {
    echo "   Error PDO: " . $e->getMessage() . "\n";
}
?>