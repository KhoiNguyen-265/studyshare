// Ẩn alert
setTimeout(() => {
    const alert = document.querySelector(".alert");
    if (alert) {
        alert.style.opacity = 0;
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}, 3000);

//
