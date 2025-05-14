// AOS scroll animasyon başlatma
AOS.init({
    duration: 1000,
    once: true,
});

document.addEventListener('DOMContentLoaded', function () {
    const scrollText = document.querySelector('.scroll-text');

    function checkScroll() {
        if (!scrollText) return;

        const rect = scrollText.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;

        if (rect.top <= windowHeight * 0.9) {
            scrollText.classList.add('active');
            window.removeEventListener('scroll', checkScroll);
        }
    }

    if (scrollText) {
        window.addEventListener('scroll', checkScroll);
        checkScroll();
    }

    // ✅ İletişim Formu fetch ile gönderme (AJAX)
    const iletisimForm = document.getElementById('iletisimForm');
    if (iletisimForm) {
        iletisimForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = {
                ad: document.getElementById('ad').value,
                email: document.getElementById('email').value,
                mesaj: document.getElementById('mesaj').value
            };

            fetch('iletisim.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            })
            .then(async response => {
                console.log("HTTP Durumu:", response.status);
                const text = await response.text();
                try {
                    const data = JSON.parse(text);
                    console.log("JSON:", data);

                    const mesajKutusu = document.getElementById('mesajKutusu');
                    if (data.status === "success") {
                        mesajKutusu.innerHTML = `<p style="color:green;">${data.message}</p>`;
                    } else {
                        mesajKutusu.innerHTML = `<p style="color:red;">${data.message}</p>`;
                    }
                } catch (error) {
                    console.error("Geçersiz JSON:", text);
                    const mesajKutusu = document.getElementById('mesajKutusu');
                    mesajKutusu.innerHTML = `<p style="color:red;">Sunucudan beklenmeyen cevap alındı.</p>`;
                }
            })
            .catch(error => {
                console.error("Hata Detayı:", error);
                const mesajKutusu = document.getElementById('mesajKutusu');
                mesajKutusu.innerHTML = `<p style="color:red;">Bir hata oluştu. Lütfen tekrar deneyiniz.</p>`;
            });
        });
    }
});
