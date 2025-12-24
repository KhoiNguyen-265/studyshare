<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=landing");
}

$filter = filterData();

// echo "<pre>";
// print_r($result);
// echo "</pre>";
$docId = isset($filter['id']) ? (int)$filter['id'] : 0;

if($docId <= 0) {
    redirect("?page=documents");
}

// Lấy thông tin tài liệu
$document = getOne("SELECT d.*, u.fullname as author, s.name as subject_name
                    FROM documents d
                    JOIN users u ON d.user_id = u.id
                    JOIN subjects s ON d.subject_id = s.id
                    WHERE d.id = $docId AND d.status = 'approved'");

// echo "<pre>";
// print_r(getSession());
// echo "</pre>";
// echo "<pre>";
// print_r($document);
// echo "</pre>";
if (empty($document)) {
    redirect("?page=documents");
}

// Tăng View 
$userId = getSession('user_id');

$checkViewed = getOne("SELECT * FROM document_views WHERE user_id = $userId AND doc_id = $docId");

if(empty($checkViewed)) {
    // Thêm log view
    insert('document_views', [
        'user_id' => $userId,
        'doc_id' => $docId
    ]);

    // Tăng view count 
    update('documents', ['view_count' => $document['view_count'] + 1], "id = $docId");

    // Cập nhật lại document 
    $document['view_count']++;
}

// Xác định loại file 
$fileExtension = pathinfo($document['file_path'], PATHINFO_EXTENSION);
$fileType = strtolower($fileExtension);

// Lấy URL của file 
$fileURL = _HOST_URL . "uploads/documents/" . $document['file_path'];

?>


<!-- Main Content Container -->
<div class="detail-container">
    <!-- LEFT COLUMN: Document Viewer -->
    <div class="detail-main">
        <!-- Document Viewer Section -->
        <div class="doc-viewer-section">
            <div class="viewer-header">
                <!-- Breadcrumb Navigation -->
                <div class="breadcrumb">
                    <a href="?page=home">Home</a>
                    <span>/</span>
                    <a href="?page=documents">Documents</a>
                    <span>/</span>
                    <a
                        href="?page=documents&subject_id=<?php echo $document['subject_id']; ?>">
                        <?php echo htmlspecialchars($document['subject_name']); ?>
                    </a>
                    <span>/</span>
                    <span
                        class="current"><?php echo htmlspecialchars($document['title']); ?></span>
                </div>
                <div class="viewer-controls">
                    <button onclick="toggleFullscreen()"
                        class="icon-btn" title="Fullscreen">
                        <i class="fa-solid fa-expand"></i>
                    </button>
                </div>
            </div>

            <!-- Document Viewer -->
            <div class="doc-viewer" id="documentViewer">

                <?php if ($fileType === 'pdf'): ?>
                <!-- ========== PDF VIEWER ========== -->
                <div class="pdf-viewer-container">
                    <!-- PDF Controls -->
                    <div class="pdf-controls">
                        <button onclick="previousPage()"
                            class="control-btn">
                            <i class="fa-solid fa-chevron-left"></i>
                            Previous
                        </button>
                        <span class="page-info">
                            Page <span id="currentPage">1</span> /
                            <span id="totalPages">-</span>
                        </span>
                        <button onclick="nextPage()"
                            class="control-btn">
                            Next
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                        <div class="zoom-controls">
                            <button onclick="zoomOut()"
                                class="control-btn">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <span id="zoomLevel">100%</span>
                            <button onclick="zoomIn()"
                                class="control-btn">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Canvas để render PDF -->
                    <div class="pdf-canvas-wrapper">
                        <canvas id="pdfCanvas"></canvas>
                    </div>

                    <!-- Loading indicator -->
                    <div class="pdf-loading" id="pdfLoading">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <p>Loading PDF...</p>
                    </div>
                </div>

                <?php elseif (in_array($fileType, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'])): ?>
                <!-- ========== OFFICE FILES VIEWER ========== -->
                <div class="office-viewer">
                    <iframe
                        src="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo urlencode($fileUrl); ?>"
                        frameborder="0">
                    </iframe>
                </div>
                <div class="viewer-fallback">
                    <i class="fa-regular fa-file-lines"></i>
                    <p>Can't preview this file?</p>
                    <a href="?page=document-detail&id=<?php echo $docId; ?>&action=download"
                        class="btn btn--primary">
                        <i class="fa-solid fa-download"></i>
                        Download to view
                    </a>
                </div>

                <?php elseif (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                <!-- ========== IMAGE VIEWER ========== -->
                <div class="image-viewer">
                    <img src="<?php echo $fileUrl; ?>"
                        alt="<?php echo htmlspecialchars($document['title']); ?>">
                </div>

                <?php else: ?>
                <!-- ========== UNSUPPORTED FILE TYPE ========== -->
                <div class="viewer-fallback">
                    <i class="fa-regular fa-file"></i>
                    <h3>Preview not available</h3>
                    <p>This file type
                        (<?php echo strtoupper($fileType); ?>)
                        cannot be previewed in browser.</p>
                    <a href="?page=document-detail&id=<?php echo $docId; ?>&action=download"
                        class="btn btn--primary">
                        <i class="fa-solid fa-download"></i>
                        Download to view
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </div>



    </div>
    <!-- Document Info Card -->
    <div class="sidebar-card">
        <h3 class="sidebar-title">Document Info</h3>
        <div class="info-list">
            <div class="info-item">
                <i class="fa-solid fa-file"></i>
                <div>
                    <span class="info-label">Format</span>
                    <span
                        class="info-value"><?php echo strtoupper($fileType); ?></span>
                </div>
            </div>
            <div class="info-item">
                <i class="fa-solid fa-calendar"></i>
                <div>
                    <span class="info-label">Uploaded</span>
                    <span
                        class="info-value"><?php echo date('M d, Y', strtotime($document['created_at'])); ?></span>
                </div>
            </div>
            <div class="info-item">
                <i class="fa-solid fa-book"></i>
                <div>
                    <span class="info-label">Subject</span>
                    <span
                        class="info-value"><?php echo htmlspecialchars($document['subject_name']); ?></span>
                </div>
            </div>
            <div class="info-item">
                <i class="fa-solid fa-user"></i>
                <div>
                    <span class="info-label">Author</span>
                    <span
                        class="info-value"><?php echo htmlspecialchars($document['author']); ?></span>
                </div>
            </div>
        </div>

        <!-- Related Documents -->
        <?php if (!empty($relatedDocs)): ?>
        <div class="sidebar-card">
            <h3 class="sidebar-title">Related Documents</h3>
            <div class="related-docs">
                <?php foreach($relatedDocs as $doc): ?>
                <a href="?page=document-detail&id=<?php echo $doc['id']; ?>"
                    class="related-item">
                    <div class="related-icon">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>
                    <div class="related-info">
                        <p class="related-title line-clamp-2">
                            <?php echo htmlspecialchars($doc['title']); ?>
                        </p>
                        <p class="related-meta">
                            <span
                                class="related-author"><?php echo htmlspecialchars($doc['author']); ?></span>
                            <span class="related-stats">
                                <i class="fa-regular fa-eye"></i>
                                <?php echo number_format($doc['view_count']); ?>
                            </span>
                        </p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<!--  PDF.JS LIBRARY   -->
<?php if ($fileType === 'pdf'): ?>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js">
</script>
<script>
// PDF.js Configuration
pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const pdfUrl = '<?php echo $fileURL; ?>';
let pdfDoc = null;
let currentPage = 1;
let scale = 1;
const canvas = document.getElementById('pdfCanvas');
const ctx = canvas.getContext('2d');

// Load PDF Document
pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
    console.log('PDF loaded successfully');
    pdfDoc = pdf;
    document.getElementById('totalPages').textContent = pdf
        .numPages;
    document.getElementById('pdfLoading').style.display =
        'none';
    renderPage(currentPage);
}).catch(error => {
    console.error('Error loading PDF:', error);
    document.getElementById('pdfLoading').innerHTML =
        '<p>Error loading PDF. Please download to view.</p>';
});

// Render Page Function
function renderPage(pageNum) {
    pdfDoc.getPage(pageNum).then(page => {
        const viewport = page.getViewport({
            scale: scale
        });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };

        page.render(renderContext);
        document.getElementById('currentPage').textContent =
            pageNum;
    });
}

// Scroll to top
function scrollToTop() {
    // Theo CSS của bạn, thanh cuộn nằm ở .detail-main
    const scrollContainer = document.querySelector('.detail-main');

    if (scrollContainer) {
        scrollContainer.scrollTop = 0;
    }
}

// Navigation Functions
function previousPage() {
    if (currentPage <= 1) return;
    currentPage--;
    renderPage(currentPage);
    scrollToTop();
}

function nextPage() {
    if (currentPage >= pdfDoc.numPages) return;
    currentPage++;
    renderPage(currentPage);
    scrollToTop();
}

// Zoom Functions
function zoomIn() {
    scale += 0.25;
    if (scale > 3) scale = 3;
    document.getElementById('zoomLevel').textContent = Math.round(
        scale * 100) + '%';
    renderPage(currentPage);
}

function zoomOut() {
    scale -= 0.25;
    if (scale < 0.5) scale = 0.5;
    document.getElementById('zoomLevel').textContent = Math.round(
        scale * 100) + '%';
    renderPage(currentPage);
}

// Fullscreen Toggle
function toggleFullscreen() {
    const viewer = document.getElementById('documentViewer');
    if (!document.fullscreenElement) {
        viewer.requestFullscreen().catch(err => {
            console.error('Error enabling fullscreen:', err);
        });
    } else {
        document.exitFullscreen();
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') previousPage();
    if (e.key === 'ArrowRight') nextPage();
    if (e.key === '+') zoomIn();
    if (e.key === '-') zoomOut();
});
</script>
<?php endif; ?>

</html>