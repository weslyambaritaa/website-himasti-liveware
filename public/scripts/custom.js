// Show Preloader
function showPreloader() {
    var preloader = document.querySelector(".preloader");
    if (preloader) {
        preloader.style.display = "block";
    }
}

// Hide Preloader
function hidePreloader() {
    var preloader = document.querySelector(".preloader");
    if (preloader) {
        preloader.style.display = "none";
    }
}

function onLogout() {
    // Kosongkan authToken di localStorage
    localStorage.setItem("authToken", "");

    // redirect ke halaman login
    window.location.href = "/auth/logout";
}

// Handle Livewire modals
document.addEventListener("livewire:initialized", () => {
    Livewire.on("closeModal", (data) => {
        const modal = bootstrap.Modal.getInstance(
            document.getElementById(data.id)
        );
        if (modal) {
            modal.hide();
        }
    });

    Livewire.on("showModal", (data) => {
        const modal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById(data.id)
        );
        if (modal) {
            modal.show();
        }
    });
});
