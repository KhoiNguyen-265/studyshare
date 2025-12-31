<?php

if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if(!isLogin()) {
    redirect("?page=landing");
}

if(!isset($currentPage) || !isset($totalPages) || $totalPages <= 1) {
    return;
}

$range = 2;
?>

<div class="pagination">
    <ul class="pagination__list">
        <!-- Previous -->
        <a href="?<?php echo http_build_query(array_merge($_GET, ['page_number' => $currentPage - 1])); ?>"
            class="pagination__btn <?php echo ($currentPage > 1) ? '' : 'disable' ?>">
            <i class="fa-solid fa-angle-left"></i>
        </a>

        <!-- Number -->
        <?php
            $range = 2; // số trang hiển thị mỗi bên

            // Xử lý active
            $activePage = isset($_GET['page_number']) && is_numeric($_GET['page_number']) ? (int)$_GET['page_number'] : 1;
            
            // Trang đầu
            if($currentPage > $range + 1) {
                echo '<a href="?' . http_build_query(array_merge($_GET, ['page_number' => 1])) . '" class="pagination__item' .  '">1</a>';

                if($currentPage > $range + 2) {
                    echo '<span class="dots">...</span>';
                }
            }

            // Các trang ở giữa
            for($i = max(1, $currentPage - $range); $i <= min($totalPages, $currentPage + $range); $i++) {
                $isActive = ($i === $activePage) ? 'active' : '';

                echo '<a href="?' . http_build_query(array_merge($_GET, ['page_number' => $i])) . '" class="pagination__item ' . $isActive . '">' . $i . '</a>';
            }

            // Trang cuối 
            if($currentPage < $totalPages - $range ) {
                if($currentPage < $totalPages - $range - 1) {
                    echo '<span class="dots">...</span>';
                }
                echo '<a href="?' . http_build_query(array_merge($_GET, ['page_number' => $totalPages])) . '" class="pagination__item">' . $totalPages . '</a>';
            }
            ?>

        <!-- Next -->
        <a href="?<?php echo http_build_query(array_merge($_GET, ['page_number' => $currentPage + 1])); ?>"
            class="pagination__btn <?php echo ($currentPage < $totalPages) ? '' : 'disable' ?>">
            <i class="fa-solid fa-angle-right"></i>
        </a>
    </ul>
</div>