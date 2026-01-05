<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=landing");
}

// Lấy user ID người dùng 
$userId = getSession('user_id');

// Lấy ra danh sách các môn học 
$subjects = getAll("SELECT * FROM subjects ORDER BY name ASC");

// echo "<pre>";
// print_r($subjects);

// Biến lưu error 
$errors = [];

// Biến lưu success 
$success = '';

// Biến lưu data cũ 
$oldData = [];

// Xử lý form khi được submit
if(isPost()) {
    $filter = filterData();

    $title = $filter['title'] ?? "";
    $description = $filter['description'] ?? "";
    $subjectId = $filter['subject_id'] ?? "";

    // Lưu dữ liệu vào oldData 
    $oldData = [
        'title' => $title,
        'description' => $description,
        'subject_id' => $subjectId
    ];

    // Validate title
    if(empty($title)) {
        $errors['title'][] = "Title is required";
    } elseif (strlen($title) < 5) {
        $errors['title'][] = 'Title must be at least 5 characters';
    } elseif (strlen($title) > 255) {
        $errors['title'][] = 'Title must not exceed 255 characters';
    } 

    // Validate Description 
    if(empty($description)) {
        $errors['description'][] = "Description is required";
    } elseif (strlen($description) < 20) {
        $errors['description'][] = "Description must be at least 20 characters";
    }

    // Validate Subject 
    if(empty($subjectId)) {
        $errors['subject_id'][] = "Please select a subject";
    } elseif (!validateInt($subjectId)) {
        $errors['subject_id'][] = "Invalid subject";
    }

    // ---------- Validate & Upload File ----------
    if(!isset($_FILES['document']) || $_FILES['document']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors['document'][] = 'Please select a file to upload';
    } else {
        $file = $_FILES['document'];
        
        // Allowed file types
        $allowedTypes = [
            'application/pdf', // PDF
            // 'application/msword', // DOC
            // 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
            // 'application/vnd.ms-powerpoint', // PPT
            // 'application/vnd.openxmlformats-officedocument.presentationml.presentation', // PPTX
            // 'application/vnd.ms-excel', // XLS
            // 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', //XLSX
        ];

        $allowedExtensions = ['pdf'];
        
        // Get file extension 
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validate file type 
        if(!in_array($file['type'], $allowedTypes) || !in_array($fileExtension, $allowedExtensions)) {
            $errors['document'][] = 'Invalid file type';
        }

        // Validate file size (Max 10MB) 
        $maxSize = 10 * 1024 * 1024;
        if($file['size'] > $maxSize) {
            $errors['document'][] = 'File size must be less than 10MB';
        }
    }

    // Nếu không có lỗi 
    if(empty($errors)) {
        $uploadDir = 'uploads/documents/';
        
        // Tạo folder nếu chưa có 
        if(!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Tạo tên file unique 
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = 'doc_' . $userId . '_' . time() . '_' . uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $fileName;

        // Di chuyển file từ thư mục tạm vào thư mục đích 
        if(move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Insert vào database 
            $data = [
                'user_id' => $userId,
                'subject_id' => $subjectId,
                'title' => $title,
                'description' => $description,
                'file_path' => $fileName,            
            ];


            $insertStatus = insert('documents', $data);
            
            if($insertStatus) {
                $success = 'Document uploaded successfully! Waiting for admin approval.';

                $oldData = []; 
            } else {
                $errors['general'][] = 'Failed to save document to database';
            }
        } else {
            $errors['general'][] = 'Failed to upload file';
        }
    }
}

?>

<div class="upload">
    <!-- Upload Header -->
    <div class="upload__header">
        <h2 class="heading-2 upload__heading">Upload Document</h2>
        <p class="upload__desc">
            Share your knowledge by uploading study materials
        </p>
    </div>

    <!-- Success Message -->
    <div style="margin-bottom: 10px">
        <?php if (!empty($success)): ?>
        <div class="alert alert--success">
            <i class="fa-solid fa-circle-check"></i>
            <?php echo $success; ?>
        </div>
        <?php elseif(!empty($errors)): ?>
        <div class="alert alert--error">
            <i class="fa-solid fa-xmark"></i>
            Document upload failed!
        </div>
        <?php endif; ?>
    </div>

    <!-- Upload Container -->
    <div class="upload__container">
        <!-- Upload form -->
        <div class="upload-form__card">
            <form action="" method="POST"
                enctype="multipart/form-data" class="upload-form">
                <!-- File Upload Section -->
                <div class="upload-form__section">
                    <h3 class="upload-form__title">Select File</h3>
                    <div class="upload-form__content-wrapper">
                        <div class="upload-form__content">
                            <input type="file" name="document"
                                id="documentFile" require
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx"
                                class="upload-form__input" hidden>
                            <label for="documentFile"
                                id="fileUploadArea"
                                class="upload-form__area <?php echo !empty($errors['document']) ? "error" : ""; ?>">
                                <i
                                    class="fa-solid fa-cloud-arrow-up"></i>
                                <h4>Click to upload or drag and drop
                                </h4>
                                <p>PDF, DOC, DOCX, PPT, PPTX, XLS,
                                    XLSX
                                    (Max 10MB)</p>
                            </label>

                            <!-- Error -->
                            <div style="margin-top: 8px;">
                                <?php echo formError('document', $errors); ?>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="upload-preview" id="filePreview">
                            <div class="upload-preview__content">
                                <div class="upload-preview__icon">
                                    <i class="fa-regular fa-file"></i>
                                </div>
                                <div class="upload-preview__info">
                                    <p class="upload-preview__name"
                                        id="fileName"></p>
                                    <p class="upload-preview__size"
                                        id="fileSize"></p>
                                </div>
                                <button class="upload-preview__remove"
                                    id="removeFile">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Information -->
                <div class="upload-form__section">
                    <h3 class="upload-form__title">Document
                        Information</h3>

                    <!-- Title -->
                    <div class="upload-form__group">
                        <label for="title"
                            class="upload-form__label">Title</label>
                        <input name="title" id="title" type="text"
                            class="upload-form__input <?php echo !empty($errors['title']) ? "error" : ""; ?>"
                            value="<?php echo oldData('title', $oldData); ?>"
                            placeholder="e.g., Introduction to Calculus - Chapter 1"
                            require>

                        <!-- Error -->
                        <?php echo formError('title', $errors); ?>
                    </div>

                    <!-- Subject -->
                    <div class="upload-form__group">
                        <label for="subject_id"
                            class="upload-form__label">Subject</label>
                        <select name="subject_id" id="subject_id"
                            class="upload-form__input <?php echo !empty($errors['subject_id']) ? "error" : ""; ?>">
                            <option value="">-- Select Subject --
                            </option>
                            <?php foreach($subjects as $subject): ?>
                            <option
                                value="<?php echo $subject['id']; ?>"
                                <?php echo (oldData('subject_id', $oldData)) == $subject['id'] ? 'selected' : ""; ?>>
                                <?php echo $subject['name']; ?>
                            </option>
                            <?php endforeach ?>
                        </select>

                        <!-- Error -->
                        <?php echo formError('subject_id', $errors); ?>
                    </div>

                    <!-- Description -->
                    <div class="upload-form__group">
                        <label for="description"
                            class="upload-form__label">Description</label>
                        <textarea name="description" id="description"
                            class="upload-form__input <?php echo !empty($errors['description']) ? "error" : ""; ?>"
                            placeholder="Describe what this document covers, key topics, and who might find it useful..."><?php echo trim(oldData('description', $oldData)); ?></textarea>
                        <div class="char-counter">
                            <span id="charCount">0</span> / 20
                            characters minimum
                        </div>

                        <!-- Error -->
                        <?php echo formError('description', $errors); ?>
                    </div>

                    <!-- Submit button -->
                    <div class="upload-form__actions">
                        <button
                            class="btn btn--primary upload__btn">Upload
                            Document</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Upload Guidelines Sidebar -->
        <div class="upload__sidebar">
            <div class="guideline-card">
                <h3 class="guideline__title">Upload Guidelines</h3>
                <ul class="guideline__list">
                    <li>
                        <i class="fa-solid fa-check"></i>
                        <span class="guideline__desc">File must be
                            educational material</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-check"></i>
                        <span class="guideline__desc">Maximum file
                            size: 10MB</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-check"></i>
                        <span class="guideline__desc">Provide clear,
                            descriptive title</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-check"></i>
                        <span class="guideline__desc">Write detailed
                            description (20+ characters)</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-check"></i>
                        <span class="guideline__desc">Select correct
                            subject category</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-check"></i>
                        <span class="guideline__desc">Document will be
                            reviewed before publishing</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo _HOST_URL_ASSETS ?>/js/main.js"></script>
<script src="<?php echo _HOST_URL_ASSETS ?>/js/upload.js"></script>