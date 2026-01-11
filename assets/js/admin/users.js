// Dropdown Toggle
function toggleDropdown(e, userId) {
    e.stopPropagation();
    const dropdown = document.querySelector(`#dropdown-${userId}`);

    // Close all dropdown
    document.querySelectorAll(".dropdown-menu").forEach((menu) => {
        if (menu.id !== `dropdown-${userId}`) {
            menu.classList.remove("show");
        }
    });

    dropdown.classList.toggle("show");
}

// Close dropdowns when clicking outside
document.addEventListener("click", function (e) {
    e.stopPropagation();
    document.querySelectorAll(".dropdown-menu").forEach((menu) => {
        menu.classList.remove("show");
    });
});

// View User Modal
// const userModal = new Togbox("$");
const viewUser = async (userId) => {
    const userModal = new Togbox({
        templateId: "viewUserModal",
    });

    userModal.open();

    try {
        const res = await fetch(`?page=ajax&task=get_user&id=${userId}`);

        if (!res.ok) throw new Error(`HTTP code: ${res.status}`);

        const { success, user } = await res.json();

        if (!success) throw new Error("Unable to load user details");

        console.log(user);

        const modalBody = document.querySelector("#modalBody");
        modalBody.innerHTML = renderUserTemplate(user);
    } catch (error) {
        console.error("Error: ", error);
        modalBody.innerHTML = `<div class="error">Error loading user detail</div>`;
    }
};

function renderUserTemplate(user) {
    const avatarUrl = `http://localhost/studyshare/uploads/avatars/${
        user.avatar || "default.jpg"
    }`;
    return `
        <div class="user-detail">
            <div class="user-detail__header">
                <img src="${avatarUrl}" alt="avatar" class="user-avatar" />
                <div class="user-detail__info">
                    <h2 class="user-detail__name">${user.fullname}</h2>
                    <p class="user-detail__email">${user.email}</p>
                    <div class="user-badges">
                        <span class="status-badge status-badge--${user.role}">
                            ${user.role}
                        </span>
                        
                        <span class="status-badge status-badge--${user.status}">
                            ${user.status}
                        </span>
                    </div>
                </div>
            </div>

            <div class="user-detail__stats">
                ${renderStatItem("fa-file-lines", user.doc_count, "Documents")}
                ${renderStatItem(
                    "fa-eye",
                    user.total_views.toLocaleString(),
                    "Total Views"
                )}
                ${renderStatItem(
                    "fa-download",
                    user.total_downloads.toLocaleString(),
                    "Downloads"
                )}
            </div>

            <div class="user-detail__login">
                <div class="user-detail__row">
                    <label>Joined Date</label>
                    <p>${user.created_at}</p>
                </div>
                ${
                    user.last_login
                        ? `
                    <div class="user-detail__row">
                        <label>Last Login</label>
                        <p>${user.last_login}</p>
                    </div>
                `
                        : ""
                }
                </div>
            </div>
        </div>
    `;
}

function renderStatItem(icon, value, label) {
    return `
        <div class="stat-item">
            <i class="fa-solid ${icon}"></i>
            <div>
                <h4>${value}</h4>
                <p>${label}</p>
            </div>
        </div>
    `;
}
