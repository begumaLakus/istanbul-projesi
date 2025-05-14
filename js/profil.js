document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem("jwt_token");

    if (!token) {
        alert("Giriş yapmadan profil sayfasına gidemezsiniz.");
        window.location.href = "giris.html";
        return;
    }

    fetch("api/profil.php", {
        headers: {
            "Authorization": "Bearer " + token
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            document.getElementById("adSoyad").textContent = data.user.isim;
            document.getElementById("mail").textContent = data.user.mail;
        } else {
            alert(data.message);
            window.location.href = "giris.html";
        }
    })
    .catch(err => {
        console.error("API hatası:", err);
        alert("Sunucu hatası oluştu.");
    });
});
