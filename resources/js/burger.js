document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const mobileMenu = document.getElementById("mobileMenu");

    menuToggle.addEventListener("click", function () {
        if (mobileMenu.classList.contains("hidden")) {
            mobileMenu.classList.remove("hidden");
            menuToggle.innerHTML =
                '<i class="fas fa-times text-white text-xl"></i>';
        } else {
            mobileMenu.classList.add("hidden");
            menuToggle.innerHTML =
                '<i class="fas fa-bars text-white text-xl"></i>';
        }
    });
});
