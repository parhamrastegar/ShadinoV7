<?php
require_once __DIR__ . '/../includes/config.php';

echo "Running portfolio table installer...\n";

if (!function_exists('mysqli_connect') && !extension_loaded('mysqli')) {
    echo "mysqli extension not available in this PHP environment.\n";
    echo "Please enable the mysqli extension or run the SQL manually (see Database/SQL/portfolio.sql).\n";
    exit(1);
}

$sql = file_get_contents(__DIR__ . '/../Database/SQL/portfolio.sql');
if ($sql === false) {
    echo "Cannot read SQL file.\n";
    exit(1);
}

if (mysqli_multi_query($conn, $sql)) {
    do {
        if ($res = mysqli_store_result($conn)) {
            mysqli_free_result($res);
        }
    } while (mysqli_next_result($conn));
    echo "SQL executed. Table should be created (if not exists).\n";
} else {
    echo "MySQL error: " . mysqli_error($conn) . "\n";
    exit(1);
}

echo "Done.\n";

?>
