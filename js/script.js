function openLogin() {
    document.getElementById("loginModal").style.display = "flex";
}

function closeLogin() {
    document.getElementById("loginModal").style.display = "none";
}

function login() {
    alert("Giriş yapıldı. Şimdilik deneme amaçlı çalışıyor.");
}

window.onclick = function(event) {
    const modal = document.getElementById("loginModal");
    if (event.target === modal) {
        closeLogin();
    }
};
.