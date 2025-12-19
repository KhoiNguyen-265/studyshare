<?php
if(isset($doc)):
?>
<div class="document-item">
    <div class="document-item__thumb">
        <i class="fa-regular fa-file-lines"></i>
    </div>
    <div class="document-item__content">
        <a href="#!" class="document-item__title line-clamp-2">
            <?php echo $doc['title']; ?>
        </a>
        <div class="document-item__desc">
            <div class="document-item__row">
                <p class="document-item__author">
                    <?php echo $doc['author'] ?>
                </p>
                <p class="document-item__day">
                    <?php echo date('M d', strtotime($doc['created_at'])); ?>
                </p>
            </div>
            <div class="document-item__row document-item__amount">
                <p class="document-item__view">
                    <i class="fa-regular fa-eye"></i>
                    <span><?php echo $doc['view_count'] ?? 0; ?></span>
                </p>
                <p class="document-item__download">
                    <i class="fa-solid fa-download"></i>
                    <span><?php echo $doc['download_count'] ?? 0; ?></span>
                </p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>