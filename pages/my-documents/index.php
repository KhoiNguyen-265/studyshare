<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=landing");
}

// Lấy ID của người dùng khi login
$userId = getSession('user_id');

$user = getOne("SELECT id, fullname, email FROM users WHERE id = $userId");

// Pagination 
$limit = 16;

$currentPage = (isset($_GET['page_number']) && is_numeric($_GET['page_number'])) ? (int)$_GET['page_number'] : 1;

if ($currentPage < 1) {
    $currentPage = 1;
}

$offset = ($currentPage - 1) * $limit;

$totalDocs = getOne("SELECT COUNT(id) as total FROM documents d WHERE user_id = $userId");
$totalResult = $totalDocs['total'] ?? 0;
$totalPages = ceil($totalResult / $limit);

// Lấy tất cả tài liệu của người dùng
$myDocs = getAll("SELECT d.*, u.fullname as author
                  FROM documents d
                  JOIN users u ON d.user_id = u.id 
                  WHERE d.user_id = $userId 
                  ORDER BY d.created_at ASC
                  LIMIT $limit OFFSET $offset");
?>

<div class="my-documents">
    <h2 class="heading-2">My Documents</h2>
    <div class="my-documents__content mt-40">
        <div class="document__list">
            <?php if(!empty($myDocs)): ?>
            <?php foreach($myDocs as $doc) {
                include "./layouts/partials/documentCard.php";
            }
            ?>
            <?php else: ?>
            <p
                style="margin-top: 30px; color: var(--color-text-secondary); text-align: center;">
                You haven't uploaded any documents yet.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php include "./layouts/partials/pagination.php" ?>
    </div>
</div>