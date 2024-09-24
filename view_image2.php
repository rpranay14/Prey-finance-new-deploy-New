<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the upload directory
$uploadDir = '/var/www/html/uploads/';

// Sanitize and resolve the file path
$file = realpath($uploadDir . $_GET['file']);  // Resolve the absolute path

// Check if the file exists and is within the allowed directory
if ($file !== false && strpos($file, $uploadDir) === 0 && file_exists($file)) {
    // Get the file extension
    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (in_array($fileExtension, ['php', 'php3'])) {
        // Execute the PHP file
        include($file);
    } elseif (in_array($fileExtension, ['png', 'jpg', 'jpeg', 'gif','php3'])) {
        // Display the image
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: public');
        header('Content-Disposition: inline; filename="' . basename($file) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));
        header('Content-Type: ' . mime_content_type($file));

        readfile($file); // Output the file content
        exit();
    } else {
        echo "Invalid file type.";
    }
} else {
    echo "File not found or invalid path.";
}
?>
