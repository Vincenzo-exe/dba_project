<?php

define('DB_HOST',    '127.0.0.1');   // Workbench hostname
define('DB_PORT',    3306);           // Workbench port
define('DB_USER',    'root');         // Workbench username
define('DB_PASS',    'azizaziz');      // Update password if needed
define('DB_NAME',    'resumeforge');     // Update database name if needed
define('DB_CHARSET', 'utf8mb4');

function getDB(): mysqli {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if (!$conn) {
        die('<div style="font-family:sans-serif;padding:40px;color:#c00;">
            <h2>Database Connection Failed</h2>
            <p>' . mysqli_connect_error() . '</p>
            <p>Make sure MySQL is running in XAMPP and the
            <strong>resumeforge</strong> schema exists in Workbench.</p>
        </div>');
    }

    mysqli_set_charset($conn, DB_CHARSET);
    return $conn;
}

/**
 * Safely escape a string value for a query.
 */
function esc(mysqli $conn, ?string $value): string {
    return mysqli_real_escape_string($conn, trim($value ?? ''));
}
