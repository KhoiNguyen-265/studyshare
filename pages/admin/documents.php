<?php
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

// ========= XỬ LÝ TASK (CRUD) =========
$task = $_GET['task'] ?? '';
$docId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$messageType = '';

// Approve Document 
if ($task === 'approve' && $docId) {
    $result = update('documents', ['status' => 'approved'], "id = $docId");
    if($result) {
        $message = 'Document approved successfully!';
        $messageType = 'success';
    } else {
        $message = 'Failed to approve document';
        $messageType = 'error';
    }
} 

// Reject Document
if ($task === 'reject' && $docId) {
    $result = update('documents', ['status' => 'rejected'], "id = $docId");
    if($result) {
        $message = 'Document rejected successfully!';
        $messageType = 'success';
    } else {
        $message = 'Failed to reject document';
        $messageType = 'error';
    }
} 

// Delete Document 
if ($task === 'delete' && $docId) {
    $doc = getOne("SELECT file_path FROM documents WHERE id = $docId");

    if($doc) {
        // Delete file from server
        $filePath = 'uploads/documents/' . $doc['file_path'];
        if(file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete file from database
        $result = delete('documents', "id = $docId");

        if($result) {
            $message = 'Document deleted successfully!';
            $messageType = 'success';
        } else {
            $message = 'Failed to delete document!';
            $messageType = 'error';
        }
    }
}

// ========= FILTER & SEARCH =========
$filter = filterData();
$searchKeyword = $filter['q'] ?? '';
$statusFilter = $filter['status'] ?? '';

// PAGINATION 
$limit = 15;
$currentPage = isset($_GET['page_number']) && is_numeric($_GET['page_number']) ? (int)$_GET['page_number'] : 1;

if($currentPage < 1) {
    $currentPage = 1;
}

$offset = ($currentPage - 1) * $limit;

// ========= BUILD WHERE CLAUSE =========
$whereClause = "WHERE 1=1 ";

// Search 
if(!empty($searchKeyword)) {
    $whereClause .= "AND d.title LIKE '%$searchKeyword%'";
}

// Status filter 
if(!empty($statusFilter)) {
    $whereClause .= "AND d.status LIKE '%$statusFilter%'";
}

// ========= GET DOCUMENTS =========
$totalDocs = getOne("SELECT COUNT(*) as total FROM documents d $whereClause")['total'];
$totalPages = ceil($totalDocs / $limit);

$documents = getAll("SELECT d.*, u.fullname as author, u.email as author_email, s.name as subject_name
                     FROM documents d
                     JOIN users u ON d.user_id = u.id 
                     JOIN subjects s ON d.subject_id = s.id
                     $whereClause
                     ORDER BY d.created_at DESC 
                     LIMIT $limit OFFSET $offset");

// ========= GET STATISTICS =========
$stats = [
    'total' => getOne("SELECT COUNT(*) as total FROM documents")['total'],
    'pending' => getOne("SELECT COUNT(*) as total FROM documents WHERE status = 'pending'")['total'],
    'approved' => getOne("SELECT COUNT(*) as total FROM documents WHERE status = 'approved'")['total'],
    'rejected' => getOne("SELECT COUNT(*) as total FROM documents WHERE status = 'rejected'")['total'],
]
?>

<div class="admin-documents">
    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header__content">
            <h2 class="heading-2">Manage Documents</h2>
            <p class="page__desc">Review, approve, or reject uploaded
                documents</p>
        </div>
    </div>

    <!-- ALERT -->
    <?php if(!empty($message)): ?>
    <div class="alert alert--<?php echo $messageType ?>">
        <i
            class="fa-solid fa-<?php echo $messageType === 'success' ? 'circle-check' : 'circle-exclamation'; ?>"></i>
        <?php echo $message ?>
    </div>
    <?php endif ?>

    <!-- STATISTICS CARD -->
    <div class="doc-stats">
        <!-- Total Documents Card -->
        <div
            class="card doc-stats-card card--primary <?php echo empty($statusFilter) ? 'active' : '' ?>">
            <a href="?page=admin&action=documents">
                <div class="doc-stats-card__icon card__icon"><i
                        class="fa-solid fa-file-lines"></i></div>
                <div class="doc-stats-card__info">
                    <h3 class="doc-stats-card__title">Total Documents
                    </h3>
                    <p class="doc-stats-card__value">
                        <strong><?php echo $stats['total'] ?></strong>
                    </p>
                </div>
            </a>
        </div>

        <!-- Pending Documents Card -->
        <div
            class="card doc-stats-card card--warning <?php echo $statusFilter === 'pending' ? 'active' : '' ?>">
            <a href="?page=admin&action=documents&status=pending">
                <div class="doc-stats-card__icon card__icon"><i
                        class="fa-solid fa-clock"></i></div>
                <div class="doc-stats-card__info">
                    <h3 class="doc-stats-card__title">Pending
                        Documents
                    </h3>
                    <p class="doc-stats-card__value">
                        <strong><?php echo $stats['pending'] ?></strong>
                    </p>
                </div>
            </a>
        </div>

        <!-- Approved Documents Card -->
        <div
            class="card doc-stats-card card--success <?php echo $statusFilter === 'approved' ? 'active' : '' ?>">
            <a href="?page=admin&action=documents&status=approved">
                <div class="doc-stats-card__icon card__icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="doc-stats-card__info">
                    <h3 class="doc-stats-card__title">Approved</h3>
                    <p class="doc-stats-card__value">
                        <strong><?php echo $stats['approved'] ?></strong>
                    </p>
                </div>
            </a>
        </div>

        <!-- Rejected Documents Card -->
        <div
            class="card doc-stats-card card--error <?php echo $statusFilter === 'rejected' ? 'active' : '' ?>">
            <a href="?page=admin&action=documents&status=rejected">
                <div class="doc-stats-card__icon card__icon">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div class="doc-stats-card__info">
                    <h3 class="doc-stats-card__title">Rejected</h3>
                    <p class="doc-stats-card__value">
                        <strong><?php echo $stats['rejected'] ?></strong>
                    </p>
                </div>
            </a>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="documents-toolbar card">
        <form action="" method="GET" class="search-form">
            <input type="text" name="page" value="admin" hidden>
            <input type="text" name="action" value="documents" hidden>
            <?php if(!empty($statusFilter)): ?>
            <input type="text" name="status"
                value="<?php echo $statusFilter ?>" hidden>
            <?php endif ?>

            <div class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="q"
                    placeholder="Search documents by title..."
                    value="<?php echo htmlspecialchars($searchKeyword) ?>"
                    class="search-input">
            </div>

            <button class="btn search-btn">Search</button>
        </form>
    </div>

    <!-- DOCUMENTS TABLE -->
    <div class="documents-card card">
        <?php if(empty($documents)): ?>
        <div class="empty-state">
            <h3>No documents found</h3>
            <p>Try adjusting your filters or search query</p>
        </div>
        <?php else: ?>
        <table class="documents-table">
            <thead>
                <th>ID</th>
                <th>Document</th>
                <th>Author</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Stats</th>
                <th>Date</th>
                <th>Actions</th>
            </thead>
            <tbody>
                <?php foreach($documents as $doc): ?>
                <tr>
                    <td>#<?php echo $doc['id'] ?></td>
                    <td>
                        <div class="doc-cell">
                            <h4>
                                <?php echo htmlspecialchars($doc['title']) ?>
                            </h4>
                            <p>
                                <?php echo strtoupper(pathinfo($doc['file_path'], PATHINFO_EXTENSION)) ?>
                            </p>
                        </div>
                    </td>
                    <td>
                        <div class="author-cell">
                            <p>
                                <strong class="author-cell__name">
                                    <?php echo htmlspecialchars($doc['author']) ?>
                                </strong>
                            </p>
                            <p class="author-cell__email">
                                <?php echo htmlspecialchars($doc['author_email']) ?>
                            </p>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($doc['subject_name']) ?>
                    </td>
                    <td>
                        <span
                            class="status-badge status-badge--<?php echo $doc['status'] ?>">
                            <?php echo ucfirst($doc['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="stats-cell">
                            <span>
                                <i class="fa-regular fa-eye"></i>
                                <?php echo number_format($doc['view_count']); ?>
                            </span>
                            <span>
                                <i class="fa-solid fa-download"></i>
                                <?php echo number_format($doc['download_count']); ?>
                            </span>
                        </div>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($doc['created_at'])) ?>
                    </td>
                    <td>
                        <div class="actions-btn">
                            <!-- View Button -->
                            <button
                                class="action-btn action-btn--view"
                                onclick="viewDocument(<?php echo $doc['id']; ?>)"
                                title="View Details">
                                <i class="fa-regular fa-eye"></i>
                            </button>

                            <?php if ($doc['status'] === 'pending'): ?>
                            <!-- Approve Button -->
                            <a href="?page=admin&action=documents&task=approve&id=<?php echo $doc['id']; ?>"
                                class="action-btn action-btn--approve"
                                onclick="return confirm('Approve this document?')"
                                title="Approve">
                                <i class="fa-solid fa-check"></i>
                            </a>

                            <!-- Reject Button -->
                            <a href="?page=admin&action=documents&task=reject&id=<?php echo $doc['id']; ?>"
                                class="action-btn action-btn--reject"
                                onclick="return confirm('Reject this document?')"
                                title="Reject">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                            <?php endif; ?>

                            <!-- Delete Button -->
                            <a href="?page=admin&action=documents&task=delete&id=<?php echo $doc['id']; ?>"
                                class="action-btn action-btn--delete"
                                onclick="return confirm('Delete this document permanently?')"
                                title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <!-- PAGINATION -->
        <?php include "./layouts/partials/pagination.php" ?>
        <?php endif ?>
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

<script src="<?php echo _HOST_URL_ASSETS ?>js/admin/documents.js">
</script>