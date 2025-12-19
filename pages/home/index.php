<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=landing");
}

// Lấy ra ID và Tên người dùng
$userId = getSession('user_id');
$fullname = getSession('fullname');

if(empty($userId)) {
    redirect('?page=landing');
}

// Lấy tổng số tài liệu đã upload của user 
$totalDocs = getOne("SELECT COUNT(*)
                     AS total 
                     FROM documents 
                     WHERE user_id = $userId"
                    )['total'];

// Tổng lượt xem 
$totalViews = getOne("SELECT 
                     IFNULL(SUM(view_count), 0) AS total 
                     FROM documents 
                     WHERE user_id = $userId"
                    )['total'];

// Tổng lượt tải
$totalDownloads = getOne("SELECT IFNULL(SUM(download_count), 0) AS total 
                          FROM documents 
                          WHERE user_id = $userId"
                        )['total'];

// Lấy ra 6 tài liệu đã xem gần đây của user
$latestViewDocs = getAll("SELECT d.id, d.title, dv.viewed_at, d.view_count, d.download_count, u.fullname as author
                          FROM document_views dv 
                          JOIN documents d ON dv.doc_id = d.id 
                          JOIN users u ON d.user_id = u.id
                          WHERE dv.user_id = $userId 
                          ORDER BY dv.viewed_at DESC LIMIT 6");

// Lấy ra 6 tài liệu upload mới nhất của user 
$latestUploadDocs = getAll("SELECT id, title, view_count, download_count, created_at, status 
                            FROM documents 
                            WHERE user_id = $userId 
                            ORDER BY created_at DESC LIMIT 6");
// echo "<pre>";
// print_r($latestViewDocs);
// echo "</pre>";
?>

<!-- Hero -->
<div class="hero">
    <!-- Content -->
    <div class="hero__content">
        <h2 class="hero__heading">Hello, <span
                class="hero__name"><?php echo $fullname ?></span>
            <span class="wave">👋🏻</span>!
        </h2>
        <h2 class="hero__heading">Ready to share your
            knowledge?</h2>
        <p class="hero__desc">
            Empower your peers by contributing your study materials
            today.
            Join the community of sharing.
        </p>
    </div>
    <!-- Call to action -->
    <div class="hero__cta">
        <a href="?page=upload" class="btn btn--primary">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            Upload Document
        </a>

        <a href="?page=documents" class="btn btn--secondary">
            <i class="fa-solid fa-magnifying-glass"></i>
            Explore Library
        </a>
    </div>

</div>

<!-- Statistic -->
<div class="statistic mt-40">
    <h2 class="statistic__heading heading-2">Statistic</h2>
    <div class="statistic__list">
        <div class="statistic__card">
            <i class="fa-solid fa-file-lines"></i>
            <h3 class="statistic__title">My Contributions</h3>
            <p class="statistic__desc">Total number of documents you
                have shared</p>
            <strong><?= $totalDocs ?></strong>
        </div>

        <div class="statistic__card">
            <i class="fa-solid fa-eye"></i>
            <h3 class="statistic__title">Total Reach</h3>
            <p class="statistic__desc">Total views across all your
                uploads</p>
            <strong><?= $totalViews ?></strong>
        </div>

        <div class="statistic__card">
            <i class="fa-solid fa-cloud-arrow-down"></i>
            <h3 class="statistic__title">Knowledge Support</h3>
            <p class="statistic__desc">Total downloads by other
                students</p>
            <strong><?= $totalDownloads ?></strong>
        </div>
    </div>
</div>

<!-- Recent viewed documents -->
<div class="recent mt-40">
    <h3 class="heading-2">Recently viewed documents</h3>
    <div class="recent__list">
        <?php if (empty($latestViewDocs)): ?>
        <p class="recent__label">You haven't viewed any documents
            yet.</p>
        <?php else: ?>
        <div class="document__list">
            <?php foreach($latestViewDocs as $doc): ?>
            <?php include "./layouts/partials/documentCard.php"; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recently uploaded documents -->
<div class="recent mt-40">
    <h3 class="heading-2">Recently uploaded documents</h3>
    <div class="recent__list">
        <?php if (empty($latestUploadDocs)): ?>
        <p class="recent__label">You haven't uploaded any documents
            yet.</p>
        <?php else: ?>
        <div class="document__list">
            <?php foreach($latestUploadDocs as $doc): ?>
            <?php include "./layouts/partials/documentCard.php"; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>