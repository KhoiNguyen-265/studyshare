<?php
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

header('Content-Type: application/json');

$task = $_GET['task'] ?? '';
$docId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($task === 'get_document' && $docId > 0) {
    $doc = getOne("SELECT d.*, u.fullname as author, u.email as author_email, s.name as subject_name
                   FROM documents d 
                   JOIN users u ON d.user_id = u.id 
                   JOIN subjects s ON d.subject_id = s.id 
                   WHERE d.id = $docId");
    
    if($doc) {
        // Get file info 
        $fileExtension = pathinfo($doc['file_path'], PATHINFO_EXTENSION);
        $doc['file_type'] = strtolower($fileExtension);
        $doc['file_url'] = _HOST_URL . 'uploads/documents/' . $doc['file_path'];
        $doc['created_at'] = date('F, d, Y', strtotime($doc['created_at']));

        // Get file size 
        $filePath = 'uploads/documents/' . $doc['file_path'];
        if(file_exists($filePath)) {
            $doc['file_size'] = filesize($filePath);
            $doc['file_size_formatted'] = formatFileSize($doc['file_size']);
        } else {
            $doc['file_size'] = 0;
            $doc['file_size_formatted'] = 'Unknown';
        }

        echo json_encode([
            'success' => true,
            'document' => $doc
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Document not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}

?>