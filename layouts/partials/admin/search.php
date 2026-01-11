<div class="documents-toolbar card">
    <form action="" method="GET" class="search-form">
        <input type="text" name="page" value="admin" hidden>
        <input type="text" name="action"
            value="<?php echo $_GET['action'] ?? '' ?>" hidden>

        <?php if(!empty($statusFilter)): ?>
        <input type="text" name="status"
            value="<?php echo $statusFilter ?>" hidden>
        <?php endif ?>

        <?php if (!empty($roleFilter)): ?>
        <input type="text" name="role"
            value="<?php echo $roleFilter; ?>" hidden>
        <?php endif; ?>

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