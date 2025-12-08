<?php 
    // Cấu hình Database
    $host = "localhost";
    $db = "studyshare";
    $user = "root";
    $pass = "";
    $charset = "utf8mb4";

    // Data Source name
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    // Options PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false // Tránh SQL injection
    ];

    try {
        $conn = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        echo "Kết nối thất bại: " . $e->getMessage();
        exit;
    }   