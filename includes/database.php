<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

// Truy vấn nhiều dòng dữ liệu
function getAll($sql) {
    global $conn;

    $stm = $conn -> prepare($sql);
    $stm -> execute();
    $result = $stm -> fetchAll();
    
    return $result;
}

// Đếm số dòng 
function getRows($sql) {
    global $conn;

    $stm = $conn -> prepare($sql);
    $stm -> execute();

    $result = $stm -> rowCount();
    return $result;
}

// Truy vấn 1 dòng dữ liệu
function getOne($sql) {
    global $conn;
    $stm = $conn -> prepare($sql);
    $stm -> execute();
    $result = $stm -> fetch();
    return $result;
}

// Insert dữ liệu
function insert($table, $data) {
    global $conn;

    $key = array_keys($data);
    $column = implode(',', $key);
    $place = ':' . implode(',:', $key);

    $sql = "INSERT INTO $table ($column) VALUES($place)";

    $stm = $conn -> prepare($sql);

    $result = $stm -> execute($data);
    // DEBUG: Nếu thất bại thì in lỗi ra ngay
        if (!$result) {
            echo "<div style='background: #ffcccc; padding: 20px; border: 1px solid red; color: red;'>";
            echo "<h3>LỖI SQL!</h3>";
            echo "<p><strong>Câu lệnh:</strong> $sql</p>";
            echo "<pre>"; 
            print_r($data); // Xem dữ liệu truyền vào
            echo "</pre>";
            echo "<h4>Chi tiết lỗi từ MySQL:</h4>";
            echo "<pre>";
            print_r($stm->errorInfo()); // <--- CHÌA KHÓA Ở ĐÂY
            echo "</pre>";
            echo "</div>";
            die(); // Dừng chương trình để em đọc lỗi
        }
    return $result;
}

// Update dữ liệu
function update($table, $data, $condition = "") {
    global $conn;

    $update = '';
    foreach($data as $key => $value) {
        $update .= $key ."=:" . $key . ',';
    }
    $update = trim($update, ',');

    if(!empty($condition)) {
        $sql = "UPDATE $table SET $update WHERE $condition";
    } else {
        $sql = "UPDATE $table SET $update";
    }

    $stm = $conn -> prepare($sql);
    return $stm -> execute($data);
}

// Delete dữ liệu
function delete($table, $condition = "") {
    global $conn;

    if(!empty($condition)) {
        $sql = "DELETE FROM $table WHERE $condition";
    } else {
        $sql = "DELETE FROM $table";
    }

    $stm = $conn -> prepare($sql);
    return $stm -> execute();
}

// Lấy ID vừa insert
function lastID() {
    global $conn;

    return $conn -> lastInsertId();
}