function profilKontrol() {
    const kullanici_ad = localStorage.getItem("kullanici_ad");
    if (kullanici_ad) {
        // Giriş yapılmışsa
        window.location.href = "profil.html";
    } else {
        // Giriş yoksa
        alert("Giriş yapmadan profil sayfasına gidemezsiniz.");
        window.location.href = "giris.html";
    }
}
