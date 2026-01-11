<?php
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

ob_clean(); 
header('Content-Type: application/json');

$task = $_GET['task'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; 

if ($task === 'get_document' && $id > 0) {
    //  GET DOCUMENT 
    $doc = getOne("SELECT d.*, u.fullname as author, u.email as author_email, s.name as subject_name
                   FROM documents d 
                   JOIN users u ON d.user_id = u.id 
                   JOIN subjects s ON d.subject_id = s.id 
                   WHERE d.id = $id");
    
    if($doc) {
        $fileExtension = pathinfo($doc['file_path'], PATHINFO_EXTENSION);
        $doc['file_type'] = strtolower($fileExtension);
        $doc['file_url'] = _HOST_URL . 'uploads/documents/' . $doc['file_path'];
        $doc['created_at'] = date('F, d, Y', strtotime($doc['created_at']));

        $filePath = 'uploads/documents/' . $doc['file_path'];
        if(file_exists($filePath)) {
            $doc['file_size'] = filesize($filePath);
            $doc['file_size_formatted'] = formatFileSize($doc['file_size']);
        } else {
            $doc['file_size'] = 0;
            $doc['file_size_formatted'] = 'Unknown';
        }

        echo json_encode(['success' => true, 'document' => $doc]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Document not found']);
    }
    exit; 

} elseif ($task === 'get_user' && $id > 0) {
    //  GET USER 
    $user = getOne("SELECT * FROM users WHERE id = $id");

    if($user) {
        
        $user['doc_count'] = getOne("SELECT COUNT(*) as total FROM documents WHERE user_id = {$user['id']}")['total'];

        $user['total_views'] = getOne("SELECT IFNULL(SUM(view_count), 0) as total 
                                     FROM documents WHERE user_id = {$user['id']}")['total'];

        $user['total_downloads'] = getOne("SELECT IFNULL(SUM(download_count), 0) as total 
                                         FROM documents WHERE user_id = {$user['id']}")['total'];
        
        $user['created_at'] = date('F d, Y', strtotime($user['created_at']));
        if(!empty($user['last_login'])) {
            $user['last_login'] = date('F d, Y H:i', strtotime($user['last_login']));
        }

        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
    exit;

} else {
    //  INVALID REQUEST 
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}
?>