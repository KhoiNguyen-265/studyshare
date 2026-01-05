<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=landing");
}

$filter = filterData();
$searchKeyword = $filter['q'] ?? '';
$subjectId = $filter['subject_id'] ?? '';

// Phân trang 
$limit = 16;
$currentPage = (isset($_GET['page_number']) && is_numeric($_GET['page_number'])) ? (int)$_GET['page_number'] : 1;

if ($currentPage < 1) {
    $currentPage = 1;
}

// Offset 
$offset = ($currentPage - 1) * $limit;

// Mệnh đề Where 
$whereClause = "WHERE d.status = 'approved'";

// Thêm điều kiện tìm kiếm 
if (!empty($searchKeyword)) {
    $whereClause .= " AND d.title LIKE '%$searchKeyword%'";
}

// Thêm điều kiện lọc theo môn học 
if (!empty($subjectId)) {
    $whereClause .= " AND d.subject_id = $subjectId";
}

// Đếm tổng số documents 
$totalDocs = getOne("SELECT COUNT(id) as total FROM documents d $whereClause");
$totalResult = $totalDocs['total'] ?? 0;
$totalPages = ceil($totalResult / $limit);

// Lấy danh sách documents 
$sql = "SELECT d.*, u.fullname as author, s.name as subject_name 
        FROM documents d 
        JOIN users u ON d.user_id = u.id 
        JOIN subjects s ON d.subject_id = s.id 
        $whereClause
        ORDER BY d.created_at DESC
        LIMIT $limit OFFSET $offset";

$listDocuments = getAll($sql);

// Lấy danh sách môn học 
$listSubjects = getAll("SELECT * FROM subjects ORDER BY name ASC");

?>

<div class="documents">
    <!-- Header -->
    <div class="page-header">
        <h2 class="heading-2">All Documents</h2>

        <div class="filter-bar">
            <a href="?page=documents"
                class="filter-item <?php echo empty($subjectId) ? 'active' : '' ?>">All</a>
            <?php foreach($listSubjects as $subject): ?>
            <a href="?page=documents&subject_id=<?php echo $subject['id'] ?>"
                class="filter-item <?php echo ($subjectId == $subject['id']) ? 'active' : '' ?>">
                <?php echo $subject['name'] ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Content -->
    <div class="page-content">
        <div class="document__list">
            <?php if (!empty($listDocuments)): ?>
            <?php foreach($listDocuments as $doc): ?>
            <?php include "./layouts/partials/documentCard.php"; ?>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="empty-state">
                <p>No documents match your search.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pagination -->
    <?php include "./layouts/partials/pagination.php" ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBar = document.querySelector(".filter-bar");
    const activeItem = document.querySelector(
        ".filter-item.active");


    if (filterBar && activeItem) {
        const scrollLeft = activeItem.offsetLeft - (filterBar
            .clientWidth / 2) + (activeItem.clientWidth /
            2);

        filterBar.scrollLeft = scrollLeft;
    }
})
</script>