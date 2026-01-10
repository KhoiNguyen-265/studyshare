// Handle File Upload
const fileInput = document.querySelector("#documentFile");
const fileUploadArea = document.querySelector("#fileUploadArea");
const filePreview = document.querySelector("#filePreview");
const removeFileBtn = document.querySelector("#removeFile");

fileInput.onchange = (e) => {
    const file = e.target.files[0];

    console.log(file);
    if (file) {
        showFilePreview(file);
    }
};

function showFilePreview(file) {
    const fileName = document.querySelector("#fileName");
    const fileSize = document.querySelector("#fileSize");

    fileName.innerText = file.name;
    fileSize.innerText = formatFileSize(file.size);

    fileUploadArea.style.display = "none";
    filePreview.style.display = "block";
}

// Remove File
removeFileBtn.onclick = (e) => {
    e.preventDefault();

    // fileInput.value = '';
    fileUploadArea.style.display = "flex";
    filePreview.style.display = "none";
};

// Format size
function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i];
}

// Drag and Drop
fileUploadArea.ondragover = function (e) {
    e.preventDefault();
    this.classList.add("drag-over");
};

fileUploadArea.ondragleave = function (e) {
    e.preventDefault();
    this.classList.remove("drag-over");
};

fileUploadArea.ondrop = function (e) {
    e.preventDefault();
    this.classList.remove("drag-over");

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        showFilePreview(files[0]);
    }
};

// Char Counter
const descriptionFile = document.querySelector("#description");
const charCount = document.querySelector("#charCount");
console.log(charCount);
descriptionFile.oninput = function () {
    charCount.textContent = this.value.length;

    if (this.value.length > 20) {
        charCount.style.color = "var(--color-text-secondary)";
    }
};
