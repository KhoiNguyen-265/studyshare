// Toggle password
const passwordWrappers = document.querySelectorAll(".password-wrapper");
passwordWrappers.forEach((wrapper) => {
    const input = wrapper.querySelector("input");
    const icon = wrapper.querySelector("i");
    console.log(input);

    if (input && icon) {
        icon.onclick = () => {
            if (input.getAttribute("type") === "password") {
                input.setAttribute("type", "text");

                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                input.setAttribute("type", "password");

                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        };
    }
});
