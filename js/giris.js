// Form geçişlerini kontrol et
function toggleForm(target) {
    if (target === 'giris') {
        document.getElementById("girisForm").style.display = "block";
        document.getElementById("kayitForm").style.display = "none";
        document.getElementById("formBaslik").innerText = "Giriş Yap";
    } else {
        document.getElementById("girisForm").style.display = "none";
        document.getElementById("kayitForm").style.display = "block";
        document.getElementById("formBaslik").innerText = "Kayıt Ol";
    }
}

// Kayıt işlemi
function kayitOl() {
    const adSoyad = document.getElementById("adSoyad").value.trim();
    const email = document.getElementById("kayitMail").value.trim();
    const sifre = document.getElementById("kayitSifre").value.trim();

    if (!adSoyad || !email || !sifre) {
        alert("Lütfen tüm alanları eksiksiz doldurun.");
        return;
    }

    // Basit mail kontrolü
    if (!email.includes('@') || !email.includes('.')) {
        alert("Geçerli bir e-posta adresi giriniz.");
        return;
    }

    localStorage.setItem("kullanici_ad", adSoyad);
    localStorage.setItem("kullanici_mail", email);
    localStorage.setItem("kullanici_sifre", sifre);

    alert("Kayıt başarılı! Şimdi giriş yapabilirsiniz.");
    toggleForm('giris');
}

// Giriş işlemi
function girisYap() {
    const email = document.getElementById("girisMail").value.trim();
    const sifre = document.getElementById("girisSifre").value.trim();

    const kayitliMail = localStorage.getItem("kullanici_mail");
    const kayitliSifre = localStorage.getItem("kullanici_sifre");

    if (!email || !sifre) {
        alert("Lütfen e-posta ve şifre alanlarını doldurun.");
        return;
    }

    if (email === kayitliMail && sifre === kayitliSifre) {
        alert("Giriş başarılı! Anasayfaya yönlendiriliyorsunuz...");
        window.location.href = "index.html";
    } else {
        alert("Hatalı e-posta veya şifre girdiniz.");
    }
}
