const viewDocument = async (docId) => {
    const modal = new Togbox({
        templateId: "viewModal",
        footer: false,
    });

    modal.open();

    const modalBody = document.querySelector(".modal-body");
    modalBody.innerHTML = `<div class="loading"><i class="fa-solid fa-spinner fa-spin"></i> Document loading...</div>`;

    try {
        // Fetch Data
        const res = await fetch(`?page=ajax&task=get_document&id=${docId}`);

        if (!res.ok) throw new Error(`HTTP code: ${res.status}`);

        const { success, document: doc } = await res.json();

        if (!success) throw new Error("Unable to load document information");

        console.log(doc);
        // Render UI
        modalBody.innerHTML = `
            <div class="doc-review">
                ${getPreviewHtml(doc)}
                ${getInfoHtml(doc)}
            </div>
        `;
    } catch (error) {
        console.error(error);
        modalBody.innerHTML = `<div class="error">Error loading document</div>`;
    }
};

// Hàm tạo HTML khung xem trước (Preview)
const getPreviewHtml = (doc) => {
    const { file_type: type, file_url: url, title } = doc;
    let content = "";

    if (type === "pdf") {
        content = `<iframe src="${url}" class="file-preview-iframe"></iframe>`;
    }
    return `
        <div class="doc-review-preview">
            <div class="file-preview-container">${content}</div>
        </div>
    `;
};

// Hàm tạo HTML thông tin chi tiết (Sidebar)
const getInfoHtml = (doc) => {
    // Xử lý logic nút bấm
    const actions =
        doc.status === "pending"
            ? `
        <div class="review-actions">
            <a href="?page=admin&action=documents&task=approve&id=${doc.id}" 
               class="btn btn--success btn--large" onclick="return confirm('Approve this document?')">
               <i class="fa-solid fa-check"></i> Approve
            </a>
            <a href="?page=admin&action=documents&task=reject&id=${doc.id}" 
               class="btn btn--danger btn--large" onclick="return confirm('Reject this document?')">
               <i class="fa-solid fa-xmark"></i> Reject
            </a>
        </div>`
            : `
        <div class="review-actions">
            <a href="${doc.file_url}" download class="btn btn--primary btn--large">
                <i class="fa-solid fa-download"></i> Download Document
            </a>
        </div>`;

    // Helper render dòng thông tin
    const item = (icon, label, val, meta = "") => `
        <div class="info-item">
            <label>
                <i class="fa-solid ${icon}"></i> 
                ${label}
            </label>
            <p>${val}</p> 
            ${meta ? `<span class="info-meta">${meta}</span>` : ""}
        </div>`;

    return `
        <div class="doc-review-info">
            <div class="review-info-header">
                <div class="doc-icon-large"><i class="fa-regular fa-file-lines"></i></div>
                <div>
                    <h3>${doc.title}</h3>
                    <span class="status-badge status-badge--${
                        doc.status
                    }">${doc.status.toUpperCase()}</span>
                </div>
            </div>
            
            <div class="review-info-grid">
                ${item("fa-user", "Author", doc.author, doc.author_email)}
                ${item("fa-book", "Subject", doc.subject_name)}
                ${item("fa-file", "File Type", doc.file_type.toUpperCase())}
                ${item("fa-weight-scale", "File Size", doc.file_size_formatted)}
                ${item("fa-calendar", "Upload Date", doc.created_at)}
                ${item(
                    "fa-chart-simple",
                    "Engagement",
                    `${doc.view_count} views • ${doc.download_count} downloads`
                )}
            </div>

            <div class="info-item info-item--full">
                <label><i class="fa-solid fa-align-left"></i> Description</label>
                <p class="description-text">${doc.description}</p>
            </div>
            ${actions}
        </div>
    `;
};
