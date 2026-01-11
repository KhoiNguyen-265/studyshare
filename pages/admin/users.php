<?php
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

// ========== XỬ LÝ TASKS ==========
$task = $_GET['task'] ?? '';
$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$messageType = '';

$currentAdminId = getSession('user_id');

// Change Status (Activated/Blocked)
if($task === 'change_status' && $userId > 0) {
    if($userId === $currentAdminId) {
        $message = 'You cannot change your own status!';
        $messageType = 'error';
    } else {
        $newStatus = $_GET['new_status'] ?? '';
        if(in_array($newStatus, ['activated', 'blocked', 'pending'])) {
            $result = update('users', ['status' => $newStatus], "id = $userId");
            
            if($result) {
                $message = 'User status updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to update user status';
                $messageType = 'error';
            }
        }
    }    
}

// Change Role (admin/user)
if($task === 'change_role' && $userId > 0) {
    if($userId === $currentAdminId) {
        $message = 'You cannot change your own role!';
        $messageType = 'error';
    } else {
        $newRole = $_GET['new_role'] ?? '';
        if(in_array($newRole, ['user', 'admin'])) {
            $result = update('users', ['role' => $newRole], "id = $userId");
            
            if($result) {
                $message = 'User role updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to update user role';
                $messageType = 'error';
            }
        }
    }  
}

// Delete User 
if($task === 'delete' && $userId > 0) {
    if($userId === $currentAdminId) {
        $message = 'You cannot delete yourself!';
        $messageType = 'error';
    } else {
        // Delete user's documents files 
        $userDocs = getAll("SELECT * FROM documents WHERE user_id = $userId");
        foreach($userDocs as $doc) {
            $filePath = 'uploads/documents/' . $doc['file_path'];
            if(file_exists($doc[$filePath])) {
                unlink($filePath);
            }
        }

        // Delete user 
        $result = delete('users', "id = $userId");
        if($result) {
            $message = 'User deleted successfully!';
            $messageType = 'success';
        } else {
            $message = 'Failed to delete user';
            $messageType = 'error';
        }
    }
}

// ========== FILTERS & SEARCH ==========
$filter = filterData();
$searchKeyword = $filter['q'] ?? '';
$statusFilter = $filter['status'] ?? '';
$roleFilter = $filter['role'] ?? '';

// ========== PAGINATION ==========
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
    $whereClause .= "AND u.fullname LIKE '%$searchKeyword%' OR u.email LIKE '%$searchKeyword%'";
}

// Status filter 
if(!empty($statusFilter)) {
    $whereClause .= "AND u.status = '$statusFilter'";
}

// Role filter 
if(!empty($roleFilter)) {
    $whereClause .= "AND u.role = '$roleFilter'";
}

// ========== GET USERS ==========
$totalUsers = getOne("SELECT COUNT(*) as total FROM users u $whereClause")['total'];
$totalPages = ceil($totalUsers / $limit);

$users = getAll("SELECT 
                    u.*,
                    COUNT(DISTINCT d.id) as doc_count,
                    IFNULL(SUM(d.view_count), 0) as total_views,
                    IFNULL(SUM(d.download_count), 0) as total_downloads
                 FROM users u
                 LEFT JOIN documents d ON u.id = d.user_id
                 $whereClause
                 GROUP BY u.id
                 ORDER BY u.created_at DESC
                 LIMIT $limit OFFSET $offset");

// GET STATISTICS 
$stats = [
    'total' => getOne("SELECT COUNT(*) as total FROM users")['total'],
    'activated' => getOne("SELECT COUNT(*) as total FROM users WHERE status = 'activated'")['total'],
    'pending' => getOne("SELECT COUNT(*) as total FROM users WHERE status = 'pending'")['total'],
    'blocked' => getOne("SELECT COUNT(*) as total FROM users WHERE status = 'blocked'")['total'],
    'admins' => getOne("SELECT COUNT(*) as total FROM users WHERE role = 'admin'")['total']
]
?>

<div class="admin-users">
    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header__content">
            <h2 class="heading-2">Manage Users</h2>
            <p class="page__desc">Manage user accounts and permissions
            </p>
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
    <div class="user-stats">
        <!-- Total Users Card -->
        <div
            class="card stats-card card--primary <?php echo empty($statusFilter) && empty($roleFilter) ? 'active' : '' ?>">
            <a href="?page=admin&action=users">
                <div class="stats-card__icon card__icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stats-card__info">
                    <h3 class="stats-card__title">Total Users
                    </h3>
                    <p class="stats-card__value">
                        <strong><?php echo $stats['total'] ?></strong>
                    </p>
                </div>
            </a>
        </div>

        <!-- Active User Card -->
        <div
            class="card stats-card card--success <?php echo $statusFilter === 'activated' ? 'active' : '' ?>">
            <a href="?page=admin&action=users&status=activated">
                <div class="stats-card__icon card__icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="stats-card__info">
                    <h3 class="stats-card__title">Activated</h3>
                    <p class="stats-card__value">
                        <strong><?php echo $stats['activated'] ?></strong>
                    </p>
                </div>
            </a>
        </div>

        <!-- Pending Users Card -->
        <div
            class="card stats-card card--warning <?php echo $statusFilter === 'pending' ? 'active' : '' ?>">
            <a href="?page=admin&action=users&status=pending">
                <div class="stats-card__icon card__icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="stats-card__info">
                    <h3 class="stats-card__title">Pending
                    </h3>
                    <p class="stats-card__value">
                        <strong><?php echo $stats['pending'] ?></strong>
                    </p>
                </div>
            </a>
        </div>

        <!-- Blocked Users Card -->
        <div
            class="card stats-card card--error <?php echo $statusFilter === 'blocked' ? 'active' : '' ?>">
            <a href="?page=admin&action=users&status=blocked">
                <div class="stats-card__icon card__icon">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div class="stats-card__info">
                    <h3 class="stats-card__title">Blocked</h3>
                    <p class="stats-card__value">
                        <strong><?php echo $stats['blocked'] ?></strong>
                    </p>
                </div>
            </a>
        </div>

        <!-- Admin Users Card -->
        <div
            class="card stats-card card--info <?php echo $roleFilter === 'admin' ? 'active' : '' ?>">
            <a href="?page=admin&action=users&role=admin">
                <div class="stats-card__icon card__icon">
                    <i class="fa-solid fa-shield"></i>
                </div>
                <div class="stats-card__info">
                    <h3 class="stats-card__title">Admin</h3>
                    <p class="stats-card__value">
                        <strong><?php echo $stats['admins'] ?></strong>
                    </p>
                </div>
            </a>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <?php include "./layouts/partials/admin/search.php" ?>

    <!-- Users Table -->
    <div class="users-card card">
        <?php if(empty($users)): ?>
        <div class="empty-state">
            <h3>No users found</h3>
            <p>Try adjusting your filters or search query</p>
        </div>
        <?php else: ?>
        <table class="users-table">
            <thead>
                <th>ID</th>
                <th>User</th>
                <th>Role</th>
                <th>Status</th>
                <th>Statistics</th>
                <th>Joined</th>
                <th>Actions</th>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td>#<?php echo $user['id'] ?></td>
                    <td>
                        <div class="user-cell">
                            <img class="admin-sidebar__avatar"
                                src="<?php echo _HOST_URL ?>/uploads/avatars/<?php echo $user['avatar'] ?? 'default.jpg' ?>"
                                alt="avatar">
                            <div class="user-info">
                                <h4>
                                    <?php echo htmlspecialchars($user['fullname']) ?>
                                </h4>
                                <p>
                                    <?php echo htmlspecialchars($user['email']) ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span
                            class="status-badge status-badge--<?php echo $user['role'] ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td>
                        <span
                            class="status-badge status-badge--<?php echo $user['status'] ?>">
                            <?php echo ucfirst($user['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="stats-cell">
                            <span title="Documents">
                                <i class="fa-solid fa-file"></i>
                                <?php echo $user['doc_count']; ?>
                            </span>
                            <span title="Views">
                                <i class="fa-solid fa-eye"></i>
                                <?php echo number_format($user['total_views']); ?>
                            </span>
                            <span title="Downloads">
                                <i class="fa-solid fa-download"></i>
                                <?php echo number_format($user['total_downloads']); ?>
                            </span>
                        </div>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                    </td>
                    <td>
                        <div class="actions-btn">
                            <!-- View Button -->
                            <button
                                class="action-btn action-btn--view"
                                onclick="viewUser(<?php echo $user['id']; ?>)"
                                title="View Details">
                                <i class="fa-regular fa-eye"></i>
                            </button>

                            <?php if($user['id'] !== $currentAdminId): ?>
                            <!-- Dropdown Menu -->
                            <div class="dropdown">
                                <button
                                    class="action-btn action-btn--more"
                                    onclick="toggleDropdown(event, <?php echo $user['id']; ?>)"
                                    title="More Actions">
                                    <i
                                        class="fa-solid fa-ellipsis-vertical"></i>
                                </button>

                                <!-- Content -->
                                <div class="dropdown-menu"
                                    id="dropdown-<?php echo $user['id']; ?>">
                                    <!-- Change Status -->
                                    <?php if($user['status'] !== 'activated'): ?>
                                    <a href="?page=admin&action=users&task=change_status&id=<?php echo $user['id']; ?>&new_status=activated<?php echo $currentPage > 1 ? "&page_number=$currentPage" : '' ?>"
                                        class="dropdown-item">
                                        Set Active
                                    </a>
                                    <?php endif ?>

                                    <?php if($user['status'] !== 'blocked'): ?>
                                    <a href="?page=admin&action=users&task=change_status&id=<?php echo $user['id']; ?>&new_status=blocked<?php echo $currentPage > 1 ? "&page_number=$currentPage" : '' ?>"
                                        class="dropdown-item">
                                        Block User
                                    </a>
                                    <?php endif ?>

                                    <!-- Change Role -->
                                    <?php if($user['role'] !== 'admin'): ?>
                                    <a href="?page=admin&action=users&task=change_role&id=<?php echo $user['id']; ?>&new_role=admin<?php echo $currentPage > 1 ? "&page_number=$currentPage" : '' ?>"
                                        class="dropdown-item"
                                        onclick="return confirm('Make this user an admin?')">
                                        Make Admin
                                    </a>
                                    <?php else: ?>
                                    <a href="?page=admin&action=users&task=change_role&id=<?php echo $user['id']; ?>&new_role=user<?php echo $currentPage > 1 ? "&page_number=$currentPage" : '' ?>"
                                        class="dropdown-item"
                                        onclick="return confirm('Remove admin role?')">
                                        Remove Admin
                                    </a>
                                    <?php endif ?>

                                    <!-- Separate -->
                                    <div class="dropdown-divider">
                                    </div>

                                    <!-- Delete -->
                                    <a href="?page=admin&action=users&task=delete&id=<?php echo $user['id']; ?><?php echo $currentPage > 1 ? "&page_number=$currentPage" : '' ?>"
                                        class="dropdown-item dropdown-item--danger"
                                        onclick="return confirm('Delete this user and all their documents? This cannot be undone!')">
                                        Delete User
                                    </a>
                                </div>
                            </div>
                            <?php else: ?>
                            <span class="current-user-badge"
                                title="This is you">
                                <i class="fa-solid fa-user-check"></i>
                            </span>
                            <?php endif ?>
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

<!-- View User Modal -->
<!-- View Document Modal Template -->
<template class="modal" id="viewUserModal">
    <div class="modal-header">
        <h3 class="modal__title">User Details</h3>
    </div>
    <div class="modal-body" id="modalBody">
        <div class="loading">
            <i class="fa-solid fa-spinner fa-spin"></i>
            Loading user details...
        </div>
    </div>
</template>

<script src="<?php echo _HOST_URL_ASSETS ?>js/admin/users.js">
</script>