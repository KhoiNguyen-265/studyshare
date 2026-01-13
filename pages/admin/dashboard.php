<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

// ============ STATISTICS ============
// Total Users
$totalUsers = getOne("SELECT COUNT(*) as total FROM users")['total'];

$activeUsers = getOne("SELECT COUNT(*) as total FROM users WHERE status = 'activated'")['total'];

$newUsersThisMonth = getOne("SELECT COUNT(*) as total 
                             FROM users 
                             WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
                             AND YEAR(created_at) = YEAR(CURRENT_DATE())")['total'];

// Total Documents
$totalDocs = getOne("SELECT COUNT(*) as total FROM documents")['total'];

$approvedDocs = getOne("SELECT COUNT(*) as total FROM documents WHERE status = 'approved'")['total'];

$pendingDocs = getOne("SELECT COUNT(*) as total FROM documents WHERE status = 'pending'")['total'];

$rejectedDocs = getOne("SELECT COUNT(*) as total FROM documents WHERE status = 'rejected'")['total'];

// Total Views & Downloads
$totalViews = getOne("SELECT IFNULL(sum(view_count), 0) as total FROM documents")['total'];

$totalDownloads = getOne("SELECT IFNULL(sum(download_count), 0) as total FROM documents")['total'];

// Total Subjects
$totalSubjects = getOne("SELECT COUNT(*) as total FROM subjects")['total'];

// ============ RECENT DATA ============
// Recent Document 
$recentDocs = getAll("SELECT d.*, u.fullname as author, s.name as subject_name 
                      FROM documents d
                      JOIN users u ON d.user_id = u.id 
                      JOIN subjects s ON d.subject_id = s.id
                      ORDER BY d.created_at DESC LIMIT 5");

// Pending Docs for Approval
$pendingDocsList = getAll("SELECT d.*, u.fullname as author, s.name as subject_name 
                      FROM documents d
                      JOIN users u ON d.user_id = u.id 
                      JOIN subjects s ON d.subject_id = s.id
                      WHERE d.status = 'pending'
                      ORDER BY d.created_at DESC LIMIT 5");

// Popular Subjects (Top 5 by document count) 
$popularSubjects = getAll("SELECT s.id, s.name,
                                  COUNT(d.id) as doc_count, 
                                  IFNULL(sum(view_count), 0) as total_views
                           FROM subjects s 
                           LEFT JOIN documents d ON s.id = d.subject_id AND d.status = 'approved'
                           GROUP BY s.id, s.name 
                           ORDER BY doc_count DESC LIMIT 5");

// Documents per Month (Last 6 months)
$docsPerMonth = getAll("SELECT DATE_FORMAT(created_at, '%Y-%m') as month,
                               DATE_FORMAT(created_at, '%b %Y') as month_label,
                               COUNT(*) as count
                        FROM documents
                        WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                        GROUP BY month, month_label 
                        ORDER BY month ASC");

// Recent Users (Last 5)
$recentUsers = getAll("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
?>

<div class="dashboard">
    <!-- Welcome Section -->
    <div class="dashboard-welcome">
        <div class="welcome__content">
            <h2 class="welcome__heading">Welcome back,
                <span
                    class="welcome__name"><?php echo getSession('fullname') ?></span>!
            </h2>
            <p class="welcome__desc">Here's what's happening with your
                platform today.</p>
        </div>
        <div class="welcome__actions">
            <a href="#!" class="btn welcome__btn admin__btn">
                <i class="fa-solid fa-file-circle-check"></i>
                <span>Review Documents</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards List -->
    <div class="stats__list">
        <!-- Total Users -->
        <div class="stat-card card--primary">
            <!-- Icon -->
            <div class="stat-card__icon card__icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <!-- Content -->
            <div class="stat-card__content">
                <h3 class="stat-card__label">Total Users</h3>
                <strong class="stat-card__value">
                    <?php echo $totalUsers; ?>
                </strong>
                <p class="stat-card__change stat-card__change--up">
                    <i class="fa-solid fa-arrow-up"></i>
                    <?php echo $newUsersThisMonth; ?> new this month
                </p>
            </div>
        </div>

        <!-- Total Documents -->
        <div class="stat-card card--success">
            <!-- Icon -->
            <div class="stat-card__icon card__icon">
                <i class="fa-solid fa-file-lines"></i>
            </div>

            <!-- Content -->
            <div class="stat-card__content">
                <h3 class="stat-card__label">Total Documents</h3>
                <strong class="stat-card__value">
                    <?php echo $totalDocs ?>
                </strong>
                <p class="stat-card__change">
                    <?php echo $approvedDocs; ?> approved
                </p>
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="stat-card card--warning">
            <!-- Icon -->
            <div class="stat-card__icon card__icon">
                <i class="fa-solid fa-clock"></i>
            </div>

            <!-- Content -->
            <div class="stat-card__content">
                <h3 class="stat-card__label">Pending Approval</h3>
                <strong class="stat-card__value">
                    <?php echo $pendingDocs ?>
                </strong>
                <p class="stat-card__change">Needs review</p>
            </div>
        </div>

        <!-- Total Views -->
        <div class="stat-card card--info">
            <!-- Icon -->
            <div class="stat-card__icon card__icon">
                <i class="fa-solid fa-eye"></i>
            </div>

            <!-- Content -->
            <div class="stat-card__content">
                <h3 class="stat-card__label">Total Views</h3>
                <strong class="stat-card__value">
                    <?php echo number_format($totalViews) ?>
                </strong>
                <p class="stat-card__change">
                    <?php echo number_format($totalDownloads) ?>
                    downloads
                </p>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="dashboard-grid">
        <!-- LEFT COLUMN -->
        <div class="dashboard-main">
            <!-- Documents Chart -->
            <div class="dashboard-card">
                <div class="card__header">
                    <div class="card__header-left">
                        <h3 class="card__title">
                            <i class="fa-solid fa-chart-line"></i>
                            Documents Overview
                        </h3>
                        <p class="card__subtitle">Last 6 months</p>
                    </div>
                </div>
                <div class="card__body">
                    <canvas height="280px" id="docsChart"></canvas>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="dashboard-card">
                <div class="card__header">
                    <div class="card__header-left">
                        <h3 class="card__title">
                            <i class="fa-solid fa-hourglass-half"></i>
                            Pending Approval
                        </h3>
                        <span class="badge badge--warning">
                            <?php echo $pendingDocs ?>
                        </span>
                    </div>
                    <div class="card__header-right">
                        <a href="?page=admin&action=documents&status=pending"
                            class="card__link">
                            View All
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card__body">
                    <?php if(empty($pendingDocsList)): ?>
                    <div class="empty-state">
                        <p>No documents pending approval</p>
                    </div>
                    <?php else: ?>
                    <div class="doc-list">
                        <?php foreach($pendingDocsList as $doc): ?>
                        <div class="doc-item">
                            <div class="doc-item__icon">
                                <i
                                    class="fa-regular fa-file-lines"></i>
                            </div>
                            <div class="doc-item__content">
                                <h4 class="doc-item__title">
                                    <?php echo $doc['title'] ?></h4>
                                <div class="doc-item__info">
                                    <span><?php echo $doc['author'] ?></span>
                                    <span>•</span>
                                    <span><?php echo $doc['subject_name'] ?></span>
                                    <span>•</span>
                                    <span><?php echo date('M d, Y', strtotime($doc['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="doc-item__action">
                                <button href=""
                                    onclick="viewDocument(<?php echo $doc['id']; ?>)"
                                    class="btn admin__btn">Review</button>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <!-- Recent Document -->
            <div class="dashboard-card">
                <div class="card__header">
                    <div class="card__header-left">
                        <h3 class="card__title">
                            <i
                                class="fa-solid fa-clock-rotate-left"></i>
                            Recent Documents
                        </h3>
                    </div>
                    <div class="card__header-right">
                        <a href="?page=admin&action=documents"
                            class="card__link">
                            View All
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card__body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentDocs as $doc): ?>
                            <tr>
                                <td>
                                    <div class="table-cell__title">
                                        <i
                                            class="fa-regular fa-file-lines"></i>
                                        <?php echo $doc['title'] ?>
                                    </div>
                                </td>
                                <td><?php echo $doc['author'] ?></td>
                                <td><?php echo $doc['subject_name'] ?>
                                </td>
                                <td>
                                    <span
                                        class="status-badge status-badge--<?php echo $doc['status'] ?>">
                                        <?php echo ucfirst($doc['status']) ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($doc['created_at'])) ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="dashboard-sidebar">
            <!-- Document status chart -->
            <div class="dashboard-card">
                <div class="card__header">
                    <h3 class="card__title">
                        <i class="fa-solid fa-chart-pie"></i>
                        Document Status
                    </h3>
                </div>
                <div class="card__body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-card">
                <div class="card__header">
                    <h3 class="card__title">
                        <i class="fa-solid fa-bolt"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card__body">
                    <div class="quick-actions">
                        <a href="?page=admin&action=documents"
                            class="quick-action__btn">
                            <i
                                class="fa-solid fa-file-circle-check"></i>
                            <span>Review Documents</span>
                        </a>
                        <a href="?page=admin&action=users"
                            class="quick-action__btn">
                            <i class="fa-solid fa-user-plus"></i>
                            <span>Manage Users</span>
                        </a>
                        <a href="?page=admin&action=subjects"
                            class="quick-action__btn">
                            <i class="fa-solid fa-book-medical"></i>
                            <span>Add Subject</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Popular Subjects -->
            <div class="dashboard-card">
                <div class="card__header">
                    <h3 class="card__title">
                        <i class="fa-solid fa-fire"></i>
                        Popular Subjects
                    </h3>
                </div>
                <div class="card__body">
                    <div class="subject-list">
                        <?php foreach($popularSubjects as $subject): ?>
                        <div class="subject-item">
                            <div class="subject-item__icon">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div class="subject-item__content">
                                <h4><?php echo $subject['name']; ?>
                                </h4>
                                <p>
                                    <?php echo $subject['doc_count']; ?>
                                    documents •
                                    <?php echo number_format($subject['total_views']); ?>
                                    views
                                </p>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <!-- Recent User -->
            <div class="dashboard-card">
                <div class="card__header">
                    <h3 class="card__title">
                        <i class="fa-solid fa-user-group"></i>
                        Recent Users
                    </h3>
                </div>
                <div class="card__body">
                    <div class="user-list">
                        <?php foreach($recentUsers as $user): ?>
                        <div class="user-item">
                            <img src="<?php echo _HOST_URL ?>/uploads/avatars/<?php echo $user['avatar'] ?? 'default.jpg'; ?>"
                                alt="User" class="user-item__avatar">
                            <div class="user-item__content">
                                <h4><?php echo $user['fullname']; ?>
                                </h4>
                                <p><?php echo $user['email']; ?>
                                </p>
                            </div>
                            <span
                                class="status-badge status-badge--<?php echo $user['status']; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Document Modal Template -->
<template class="modal" id="viewModal">
    <div class="modal-header">
        <h3 class="modal__title">Document Review</h3>
    </div>
    <div class="modal-body" id="modalBody">
    </div>
</template>
<!-- Chart.js -->
<script
    src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js">
</script>

<!-- CountUp JS -->
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.9.0/countUp.umd.min.js">
</script>

<!-- Documents JS -->
<script src="<?php echo _HOST_URL_ASSETS ?>js/admin/documents.js">
</script>

<script>
// Count Statistics
const counts = document.querySelectorAll(".stat-card__value");
console.log(counts)
counts.forEach(count => {
    const finalValue = +(count.innerText).replace(/,/g,
        '') || 0;
    const up = new countUp.CountUp(count, finalValue, {
        duration: 3,
    });
    if (!up.error) {
        up.start();
    } else {
        console.error(up.error);
    }
})

// Documents Chart 
const docsChartData = <?php echo json_encode($docsPerMonth); ?>;
const labels = docsChartData.map(item => item.month_label);
const data = docsChartData.map(item => +item.count);

const ctx = document.getElementById('docsChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Documents Uploaded',
                data: data,
                borderColor: '#5969ff',
                backgroundColor: 'rgba(89, 105, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 8,
                pointBackgroundColor: '#5969ff',
                pointBorderColor: "#5969ff",
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#1a1f36',
                    padding: 14,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    }
                },
            }
        }
    });
}

const statusChart = document.getElementById('statusChart');
if (statusChart) {
    new Chart(statusChart, {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                data: [<?php echo $approvedDocs; ?>,
                    <?php echo $pendingDocs; ?>,
                    <?php echo $rejectedDocs; ?>
                ],
                backgroundColor: ['#4caf50',
                    '#ffa000', '#f44336'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'left'
                }
            }
        }
    });
}
</script>