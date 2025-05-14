const yer_id = 1; // Örnek yer ID

document.addEventListener("DOMContentLoaded", function() {
    yorumlariGetir();
    kontrolEtGiris(); // EN ÖNEMLİ: Sayfa yüklenince giriş kontrolü yap
});

// Giriş kontrol fonksiyonu (en önemli eksik buydu)
function kontrolEtGiris() {
    const kullanici_ad = localStorage.getItem("kullanici_ad");
    if (kullanici_ad) {
        document.getElementById("yorumForm").style.display = "block";
        document.getElementById("girisUyarisi").style.display = "none";
    } else {
        document.getElementById("yorumForm").style.display = "none";
        document.getElementById("girisUyarisi").style.display = "block";
    }
}

// Yorumları getir (herkese açık)
function yorumlariGetir() {
    fetch(`http://localhost/istanbul-projesi/api/yorum_listele.php?yer_id=${yer_id}`)
        .then(res => res.json())
        .then(data => {
            const yorumlarDiv = document.getElementById("yorumListesi");
            yorumlarDiv.innerHTML = "";
            if (!data.data || data.data.length === 0) {
                yorumlarDiv.innerHTML = "<p>Henüz yorum yok.</p>";
            } else {
                data.data.forEach(yorum => {
                    yorumlarDiv.innerHTML += `
                        <div class="yorum">
                            <strong>${yorum.kullanici_ad}</strong>:<br>
                            <p>${yorum.yorum_metni}</p>
                        </div>
                    `;
                });
            }
        })
        .catch(err => {
            console.error("Yorumlar yüklenirken hata:", err);
            document.getElementById("yorumListesi").innerHTML = "<p>Yorumlar yüklenirken hata oluştu.</p>";
        });
}

// Yorum gönderme (her zaman aktif, kontrol içeride)
async function yorumGonder() {
    const kullanici_ad = localStorage.getItem("kullanici_ad");

    if (!kullanici_ad) {
        alert("Yorum yapabilmek için önce giriş yapmalısınız.");
        window.location.href = "giris.html";
        return;
    }

    const yorum_metni = document.getElementById("yorumMetni").value.trim();
    if (yorum_metni === "") {
        alert("Yorum boş olamaz.");
        return;
    }

    try {
        const res = await fetch("http://localhost/istanbul-projesi/api/yorum_ekle.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                yer_id,
                kullanici_ad,
                yorum_metni
            })
        });

        const data = await res.json();
        if (data.status === "ok") {
            alert("Yorum başarıyla gönderildi.");
            document.getElementById("yorumMetni").value = "";
            yorumlariGetir();
        } else {
            alert("Yorum gönderilemedi.");
        }
    } catch (error) {
        console.error("Yorum gönderme hatası:", error);
        alert("Yorum gönderilirken bir hata oluştu.");
    }
}

