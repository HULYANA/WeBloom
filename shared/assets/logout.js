document.addEventListener("DOMContentLoaded", () => {
    const logoutButton = document.getElementById("logoutBtn");
    const logoutOverlay = document.getElementById("logoutOverlay");
    const confirmLogout = document.getElementById("confirmLogout");
    const cancelLogout = document.getElementById("cancelLogout");

    console.log("Overlay classes on load:", logoutOverlay.classList);

    logoutButton.addEventListener("click", (event) => {
        event.preventDefault(); 
        logoutOverlay.style.display = "flex";
    });

    confirmLogout.addEventListener("click", () => {
        window.location.href = "../../shared/logout.php"; 
    });

    cancelLogout.addEventListener("click", () => {
        logoutOverlay.style.display = "none";
    });
});