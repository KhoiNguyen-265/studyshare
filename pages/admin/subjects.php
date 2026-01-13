<?php
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

// ========= XỬ LÝ TASK (CRUD) =========
$task = $_GET['task'] ?? '';
$subjectId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$messageType = '';

// Lọc data
$filter = filterData();
$name = $filter['name'] ?? '';
$description = $filter['description'] ?? '';
$searchKeyword = $filter['q'] ?? '';

// Add new subject 
if($task === 'add' && isPost()) {

    if(empty($name)) {
        $message = 'Subject is required';
        $messageType = 'error';
    } else {
        // Check duplicate
        $exists = getOne("SELECT id FROM subjects WHERE name = '$name'");
        if($exists) {
            $message = 'Subject name already exists!';
            $messageType = 'error';
        } else {
            $result = insert('subjects', [
                'name' => $name,
                'description' => $description
            ]);

            if ($result) {
                $message = 'Subject added successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to add subject';
                $messageType = 'error';
            }
        }
    }
}

// Edit subject 
if($task === 'edit' && isPost() && $subjectId > 0) {
    
    if(empty($name)) {
        $message = 'Subject is required';
        $messageType = 'error';
    } else {
        // Check duplicate (exclude current)
        $exists = getOne("SELECT id FROM subjects WHERE name = '$name' AND id != $subjectId");

        if($exists) {
            $message = 'Subject name already exists!';
            $messageType = 'error';
        } else {
            $result = update('subjects', [
                'name' => $name,
                'description' => $description
            ], "id = $subjectId");

            if ($result) {
                $message = 'Subject updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to update subject';
                $messageType = 'error';
            }
        }
    }
}

// Delete subject 
if($task === 'delete' && $subjectId > 0) {
    // Check nếu subject có documents 
    $docCount = getOne("SELECT COUNT(*) as total FROM documents WHERE subject_id = $subjectId")['total'];

    if($docCount) {
        $message = "Cannot delete! This subject has $docCount documents. Please reassign or delete documents first.";
        $messageType = 'error';
    } else {
        $result = delete('subjects', "id = $subjectId");

        if ($result) {
            $message = 'Subject deleted successfully!';
            $messageType = 'success';
        } else {
            $message = 'Failed to delete subject';
            $messageType = 'error';
        }
    }
}

// ========== WHERE CLAUSE ==========
$whereClause = "WHERE 1=1";
if (!empty($searchKeyword)) {
    $whereClause .= " AND s.name LIKE '%$searchKeyword%'";
}

// ========== GET SUBJECTS WITH STATS ==========
$subjects = getAll("SELECT s.id, s.name, s.description,
                           COUNT(DISTINCT d.id) as doc_count,
                           COUNT(DISTINCT CASE WHEN d.status = 'approved' THEN d.id END) as approved_count,
                           COUNT(DISTINCT CASE WHEN d.status = 'pending' THEN d.id END) as pending_count,
                           IFNULL(SUM(d.view_count), 0) as total_views,
                           IFNULL(SUM(d.download_count), 0) as total_downloads
                    FROM subjects s
                    LEFT JOIN documents d ON s.id = d.subject_id
                    $whereClause
                    GROUP BY s.id, s.name, s.description
                    ORDER BY s.name ASC");

// ========== STATISTICS ==========
$stats = [
    'total' => getOne("SELECT COUNT(*) as total FROM subjects")['total'],
    'total_docs' => getOne("SELECT COUNT(*) as total FROM documents")['total'],
    'empty_subjects' => 0
];

foreach($subjects as $subject) {
    if ($subject['doc_count'] == 0) {
        $stats['empty_subjects']++;
    }
}
?>

<div class="admin-subjects">
    <!-- PAGE HEADER -->
    <div class="page-header subjects-header">
        <div class="page-header__content">
            <h2 class="heading-2">Manage Subjects</h2>
            <p class="page__desc">Manage subject categories for
                documents</p>
        </div>
        <button class="btn subjects__btn btn--primary"
            onclick="openAddModal()">
            <i class="fa-solid fa-plus"></i>
            Add New Subject
        </button>
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
    <div class="subject-stats ">
        <div
            class="card  stats-card subject-stats__card card--primary">
            <div class="stats-card__icon card__icon"><i
                    class="fa-solid fa-book"></i></div>
            <div class="stats-card__info">
                <h3 class="stats-card__title">Total Subjects</h3>
                <p class="stats-card__value">
                    <?php echo $stats['total']; ?></p>
            </div>
        </div>

        <div
            class="card stats-card subject-stats__card card--success">
            <div class="stats-card__icon card__icon"><i
                    class="fa-solid fa-file-lines"></i></div>
            <div class="stats-card__info">
                <h3 class="stats-card__title">Total Documents</h3>
                <p class="stats-card__value">
                    <?php echo $stats['total_docs']; ?></p>
            </div>
        </div>

        <div class="card stats-card subject-stats__card card--error">
            <div class="stats-card__icon card__icon"><i
                    class="fa-solid fa-folder-open"></i></div>
            <div class="stats-card__info">
                <h3 class="stats-card__title">Empty Subjects</h3>
                <p class="stats-card__value">
                    <?php echo $stats['empty_subjects']; ?></p>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <?php 
        $searchPlaceholder = 'Search subjects...';
        include "./layouts/partials/admin/search.php" 
    ?>

    <!-- SUBJECTS GRID  -->
    <div class="subjects-container">
        <?php if (empty($subjects)): ?>
        <div class="empty-state">
            <h3>No subjects found</h3>
            <p>Try adjusting your filters or search query</p>
        </div>
        <?php else: ?>
        <div class="subjects-grid">
            <?php foreach($subjects as $subject): ?>
            <div id="subject-<?php echo $subject['id'] ?>"
                class="card subject-card">
                <!-- Subject card Header -->
                <div class="subject-card__header">
                    <div class="subject-card__icon">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div class="subject-card__actions">
                        <button class="icon-btn" title="Edit"
                            data-id="<?php echo $subject['id'] ?>"
                            data-name="<?php echo htmlspecialchars($subject['name']) ?>"
                            data-desc="<?php echo htmlspecialchars($subject['description']) ?>"
                            onclick="editSubject(this)">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <?php if ($subject['doc_count'] === 0): ?>
                        <button class="icon-btn icon-btn--danger"
                            title="Delete"
                            data-id="<?php echo $subject['id'] ?>"
                            data-name="<?php echo htmlspecialchars($subject['name']) ?>"
                            data-desc="<?php echo htmlspecialchars($subject['description']) ?>"
                            onclick="deleteSubject(this)">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <?php else: ?>
                        <button class="icon-btn icon-btn--disabled"
                            title="Cannot delete - has documents"
                            disabled>
                            <i class="fa-solid fa-lock"></i>
                        </button>
                        <?php endif ?>
                    </div>
                </div>

                <!-- Subject card Body -->
                <div class="subject-card__body">
                    <h3 class="subject-card__name">
                        <?php echo htmlspecialchars($subject['name']) ?>
                    </h3>
                    <?php if (!empty($subject['description'])): ?>
                    <p class="subject-card__description line-clamp-2">
                        <?php echo htmlspecialchars($subject['description']); ?>
                    </p>
                    <?php endif; ?>
                </div>

                <!-- Subject card stats -->
                <div class="subject-card__stats">
                    <div class="stat-item">
                        <i class="fa-solid fa-file-lines"></i>
                        <div>
                            <strong><?php echo $subject['doc_count']; ?></strong>
                            <span>Documents</span>
                        </div>
                    </div>

                    <div class="stat-item">
                        <i class="fa-solid fa-check-circle"></i>
                        <div>
                            <strong><?php echo $subject['approved_count']; ?></strong>
                            <span>Approved</span>
                        </div>
                    </div>

                    <div class="stat-item">
                        <i class="fa-solid fa-eye"></i>
                        <div>
                            <strong><?php echo number_format($subject['total_views']); ?></strong>
                            <span>Views</span>
                        </div>
                    </div>

                    <div class="stat-item">
                        <i class="fa-solid fa-download"></i>
                        <div>
                            <strong><?php echo number_format($subject['total_downloads']); ?></strong>
                            <span>Downloads</span>
                        </div>
                    </div>
                </div>

                <?php if ($subject['pending_count'] > 0): ?>
                <div class="subject-alert">
                    <i class="fa-solid fa-clock"></i>
                    <?php echo $subject['pending_count']; ?>
                    document(s) pending approval
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Add/Edit Subject Modal -->
<template id="subjectModal" class="modal">
    <div class="modal-header">
        <h3 class="modal__title" id="modalTitle">Document Review</h3>
    </div>
    <div class="modal-body" id="modalBody">
        <form action="" method="POST" id="subjectForm">
            <input type="hidden" name="id" id="subjectId" value="">

            <div class="form-group">
                <label for="name" class="form-label">
                    Subject Name <span class="required">*</span>
                </label>
                <input id="name" name="name" class="form-control"
                    placeholder="e.g., Mathematics, Physics, Chemistry..."
                    required autofocus>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">
                    Description <span
                        class="optional">(Optional)</span>
                </label>
                <textarea id="description" name="description" rows="4"
                    class="form-control"
                    placeholder="Brief description of this subject..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit"
                    class="btn btn--primary btn--full form-actions__btn">
                    <i class="fa-solid fa-check"></i>
                    <span id="submitBtnText">Add Subject</span>
                </button>
                <button type="button"
                    class="btn btn--secondary btn--full form-actions__btn"
                    id="cancelBtnText" <i
                    class="fa-solid fa-xmark"></i>
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script>
// Open Add New Subject Modal
function openAddModal() {
    // Show modal
    const modal = new Togbox({
        templateId: "subjectModal",
        closeMethods: ['button'],
        destroyOnClose: true,
    })
    modal.open();

    const form = document.getElementById('subjectForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitBtnText = document.getElementById('submitBtnText');
    const cancelBtnText = document.getElementById('cancelBtnText');

    // Reset form
    form.reset();
    form.action = '?page=admin&action=subjects&task=add';
    document.getElementById('subjectId').value = '';

    // Update UI
    modalTitle.textContent = 'Add New Subject';
    submitBtnText.textContent = 'Add Subject';

    // Focus input
    setTimeout(() => document.getElementById('name').focus(), 100);

    cancelBtnText.onclick = () => modal.close();
}

// Open Edit Subject Modal
function editSubject(btn) {
    // Open modal 
    const modal = new Togbox({
        templateId: "subjectModal",
        closeMethods: ['button'],
        destroyOnClose: true,
    })
    modal.open();

    const form = document.getElementById('subjectForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitBtnText = document.getElementById('submitBtnText');
    const cancelBtnText = document.getElementById('cancelBtnText');

    // Set values
    const id = btn.dataset.id;
    const name = btn.dataset.name;
    const desc = btn.dataset.desc;
    document.getElementById('subjectId').value = id;
    document.getElementById('name').value = name;
    document.getElementById('description').value = desc;
    form.action =
        `?page=admin&action=subjects&task=edit&id=${id}`;

    // Update UI
    modalTitle.textContent = 'Edit Subject';
    submitBtnText.textContent = 'Update Subject';

    // Focus input
    setTimeout(() => document.getElementById('name').focus(), 100);

    cancelBtnText.onclick = () => modal.close();
}

function deleteSubject(btn) {
    const id = btn.dataset.id;
    const name = btn.dataset.name;

    if (confirm(
            `Delete subject "${name}"?\nThis action cannot be undone.`
        )) {
        window.location.href =
            `?page=admin&action=subjects&task=delete&id=${id}`;
    }
}
</script>