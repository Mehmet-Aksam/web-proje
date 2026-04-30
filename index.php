<?php
session_start();

// Oturum kontrol - Kullanıcı giriş yapmadıysa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sorcik</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="top-strip"></div>

  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h2>Menü</h2>
      <button id="closeSidebar" aria-label="Menüyü kapat">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <nav class="sidebar-menu">
      <a href="index.php" class="active"><i class="fa-solid fa-house"></i> Ana Sayfa</a>
      <a href="turnuva.html"><i class="fa-solid fa-trophy"></i> Turnuva</a>
      <a href="Genel Kültür/genelkültür.html"><i class="fa-solid fa-globe"></i> Genel Kültür</a>
      <a href="ingilizce.html"><i class="fa-solid fa-language"></i> İngilizce</a>
      <a href="Ehliyet/ehliyet.html"><i class="fa-solid fa-car-side"></i> Ehliyet</a>
      <a href="topluluk.html"><i class="fa-solid fa-user-group"></i> Topluluk</a>
      <a href="liderlik.html"><i class="fa-solid fa-ranking-star"></i> Liderlik</a>
      <a href="sorum_var.html"><i class="fa-regular fa-circle-question"></i> Sorum Var?</a>
      <a href="cikis.php" style="color: #ff5f52; border-top: 1px solid #eee; margin-top: 20px; padding-top: 20px;"><i class="fa-solid fa-sign-out-alt"></i> Çıkış Yap</a>
    </nav>
  </aside>

  <div class="overlay" id="overlay"></div>

  <div class="header-wrap">
    <header class="navbar">
      <div class="nav-inner">

        <div class="left-group">
          <button class="menu-toggle" id="openSidebar" aria-label="Menüyü aç">
            <i class="fa-solid fa-bars"></i>
          </button>

          <div class="brand">
            <div class="brand-icon">
              <div class="question-badge">?</div>
              <span class="spark spark-1"></span>
              <span class="spark spark-2"></span>
            </div>

            <div class="brand-copy">
              <h1 class="brand-text">Sorcik</h1>
              <p class="brand-subtitle">Sor, çöz, kazan</p>
            </div>
          </div>
        </div>

        <nav class="menu">
          <a href="index.php" class="menu-item active">
            <i class="fa-solid fa-house"></i>
            <span>Ana Sayfa</span>
          </a>

          <a href="turnuva.html" class="menu-item">
            <i class="fa-solid fa-trophy"></i>
            <span>Turnuva</span>
          </a>

          <a href="Genel Kültür/genelkültür.html" class="menu-item">
            <i class="fa-solid fa-globe"></i>
            <span>Genel Kültür</span>
          </a>

          <a href="ingilizce.html" class="menu-item">
            <i class="fa-solid fa-language"></i>
            <span>İngilizce</span>
          </a>

          <a href="Ehliyet/ehliyet.html" class="menu-item">
            <i class="fa-solid fa-car-side"></i>
            <span>Ehliyet</span>
          </a>
        </nav>

        <div class="actions">
          <a href="olustur.php" class="primary-btn">
            <i class="fa-solid fa-plus"></i>
            <span>Oluştur</span>
          </a>

          <a href="cikis.php" class="secondary-btn">
            <i class="fa-solid fa-sign-out-alt"></i>
            <span>Çıkış</span>
          </a>
        </div>

      </div>
    </header>
  </div>

  <main class="home-page">

    <section class="hero">
      <div class="hero-content">
        <span class="hero-badge">Yeni Nesil Quiz Platformu</span>
        <h2>Bilgini test et, seviyeni yükselt, arkadaşlarınla yarış.</h2>
        <p>
          Genel kültürden ehliyete, İngilizceden turnuvalara kadar onlarca farklı alanda quiz çöz.
          Kategori seç, seviyeni belirle ve en yüksek puanı hedefle.
        </p>

        <div class="hero-actions">
          <a href="#" class="hero-primary">Hemen Başla</a>
          <a href="#" class="hero-secondary">Turnuvaları Gör</a>
        </div>

        <div class="hero-mini-stats">
          <div>
            <strong>120+</strong>
            <span>Quiz</span>
          </div>
          <div>
            <strong>8K+</strong>
            <span>Oyuncu</span>
          </div>
          <div>
            <strong>25+</strong>
            <span>Turnuva</span>
          </div>
        </div>
      </div>

      <div class="hero-panel">
        <div class="hero-panel-card top">
          <span class="panel-label">Bugünün Öne Çıkanı</span>
          <h3>Ehliyet Levhalar Testi</h3>
          <p>20 soru · Görselli · Orta seviye</p>
          <a href="Ehliyet/ehliyet.html">Teste Git</a>
        </div>

        <div class="hero-panel-card small">
          <i class="fa-solid fa-fire"></i>
          <div>
            <strong>Seri Rekoru</strong>
            <span>12 doğru cevap üst üste</span>
          </div>
        </div>

        <div class="hero-panel-card small">
          <i class="fa-solid fa-crown"></i>
          <div>
            <strong>Bugünün Lideri</strong>
            <span><?php echo $_SESSION['username']; ?> · 980 puan</span>
          </div>
        </div>
      </div>
    </section>

    <section class="search-panel">
      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="mainSearch" placeholder="Quiz, kategori veya konu ara...">
      </div>
      <div id="searchResults" class="search-results-overlay"></div>


      <div class="filter-row">
        <button class="filter-chip active">Tümü</button>
        <button class="filter-chip">Popüler</button>
        <button class="filter-chip">Yeni</button>
        <button class="filter-chip">Kolay</button>
        <button class="filter-chip">Orta</button>
        <button class="filter-chip">Zor</button>
        <button class="filter-chip">Resimli</button>
      </div>
    </section>

    <section class="section-head">
      <div>
        <span class="section-mini">Öne Çıkanlar</span>
        <h3>Popüler Kategoriler</h3>
      </div>
      <a href="#" class="section-link">Tümünü Gör</a>
    </section>

    <section class="category-grid">
      <a href="Genel Kültür/genelkültür.html" class="category-card animate-on-scroll retro-card" style="background: linear-gradient(135deg, #FF00FF, #FF69B4); border: 6px solid #00FFFF;">
        <div class="category-image" style="font-size: 64px;">🌍</div>
        <h4>Genel Kültür</h4>
        <p>Tarih, coğrafya, bilim ve daha fazlası.</p>
      </a>

      <a href="ingilizce.html" class="category-card animate-on-scroll retro-card" style="background: linear-gradient(135deg, #00FFFF, #00FF7F); border: 6px solid #FFFF00;">
        <div class="category-image" style="font-size: 64px;">🇬🇧</div>
        <h4>İngilizce</h4>
        <p>Kelime, gramer ve hızlı tekrar testleri.</p>
      </a>

      <a href="Ehliyet/ehliyet.html" class="category-card animate-on-scroll retro-card" style="background: linear-gradient(135deg, #FFD700, #FF6347); border: 6px solid #FF00FF;">
        <div class="category-image" style="font-size: 64px;">🚗</div>
        <h4>Ehliyet</h4>
        <p>Trafik, ilk yardım, motor ve levhalar.</p>
      </a>

      <a href="turnuva.html" class="category-card animate-on-scroll retro-card" style="background: linear-gradient(135deg, #32CD32, #FF4500); border: 6px solid #00FFFF;">
        <div class="category-image" style="font-size: 64px;">🏆</div>
        <h4>Turnuva</h4>
        <p>Arkadaşlarınla yarış, skorunu yükselt.</p>
      </a>
    </section>

    <section class="section-head">
      <div>
        <span class="section-mini">Keşfet</span>
        <h3>Öne Çıkan Quizler</h3>
      </div>
      <a href="#" class="section-link">Daha Fazla</a>
    </section>

    <section class="quiz-grid">
      <article class="quiz-card animate-on-scroll">
        <img src="Genel Kültür/genel-kultur-resimleri/tarih1.jpeg" class="quiz-image" alt="Genel Kültür" />
        <div class="quiz-top">
          <span class="quiz-badge">Popüler</span>
          <i class="fa-regular fa-bookmark"></i>
        </div>
        <h4>Türkiye Genel Kültür Karışık Test</h4>
        <p>20 soru · 10 dakika · Orta seviye</p>
        <div class="quiz-tags">
          <span>Tarih</span>
          <span>Coğrafya</span>
          <span>Klasik</span>
        </div>
        <a href="#" class="quiz-btn">Teste Başla</a>
      </article>

      <article class="quiz-card animate-on-scroll">
        <img src="Ehliyet/fotolar/trafikçevre.jpg" class="quiz-image" alt="Ehliyet" />
        <div class="quiz-top">
          <span class="quiz-badge">Yeni</span>
          <i class="fa-regular fa-bookmark"></i>
        </div>
        <h4>Ehliyet Levhalar Resimli Test</h4>
        <p>20 soru · 15 dakika · Görselli</p>
        <div class="quiz-tags">
          <span>Ehliyet</span>
          <span>Levha</span>
          <span>Resimli</span>
        </div>
        <a href="Ehliyet/ehliyet.html" class="quiz-btn">Teste Başla</a>
      </article>

      <article class="quiz-card animate-on-scroll">
        <img src="imagesingilizce/Ingilizce-Kelimeler.jpg" class="quiz-image" alt="İngilizce" />
        <div class="quiz-top">
          <span class="quiz-badge">Zirve</span>
          <i class="fa-regular fa-bookmark"></i>
        </div>
        <h4>İngilizce Kelime Hızlı Tur</h4>
        <p>15 soru · 7 dakika · Hızlı mod</p>
        <div class="quiz-tags">
          <span>İngilizce</span>
          <span>Kelime</span>
          <span>Hızlı</span>
        </div>
        <a href="ingilizce.html" class="quiz-btn">Teste Başla</a>
      </article>
    </section>

    <section class="section-head">
      <div>
        <span class="section-mini">Senin İçin</span>
        <h3>Günün Görevleri</h3>
      </div>
    </section>

    <section class="task-grid">
      <div class="task-card animate-on-scroll">
        <div class="task-icon"><i class="fa-solid fa-circle-check"></i></div>
        <h4>3 Quiz Çöz</h4>
        <p>Bugün 3 farklı test çözerek ekstra puan kazan.</p>
      </div>

      <div class="task-card animate-on-scroll">
        <div class="task-icon"><i class="fa-solid fa-star"></i></div>
        <h4>90% Üstü Başarı</h4>
        <p>Bir testte %90 başarı elde ederek rozet kazan.</p>
      </div>

      <div class="task-card animate-on-scroll">
        <div class="task-icon"><i class="fa-solid fa-users"></i></div>
        <h4>Arkadaşınla Yarış</h4>
        <p>Bir arkadaşını davet et ve aynı testi birlikte çöz.</p>
      </div>
    </section>

    <section class="section-head">
      <div>
        <span class="section-mini">Topluluk</span>
        <h3>Bugünün İstatistikleri</h3>
      </div>
    </section>

    <section class="info-panels">
      <div class="info-box animate-on-scroll">
        <i class="fa-solid fa-fire"></i>
        <h4>Günün Modu</h4>
        <p>Bugün en çok çözülen kategori: Ehliyet Testleri</p>
      </div>

      <div class="info-box animate-on-scroll">
        <i class="fa-solid fa-medal"></i>
        <h4>En İyi Skor</h4>
        <p>Toplulukta bugün en yüksek doğru sayısı: 97</p>
      </div>

      <div class="info-box animate-on-scroll">
        <i class="fa-solid fa-bolt"></i>
        <h4>Hızlı Çözüm</h4>
        <p>Ortalama test tamamlama süresi: 8 dakika</p>
      </div>
    </section>

    <section class="leaderboard">
      <div class="leaderboard-head">
        <div>
          <span class="section-mini">Sıralama</span>
          <h3>Haftalık Liderlik Tablosu</h3>
        </div>
        <a href="#" class="section-link">Tam Liste</a>
      </div>

      <div class="leaderboard-list" id="leaderboard-list">
        <!-- Liderlik tablosunu API'den çek -->
      </div>
    </section>

  </main>
  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <h3>Sorcik</h3>
        <p>Bilgi yarışmalarını daha eğlenceli hale getiren quiz platformu.</p>
      </div>

      <div class="footer-links">
        <a href="hakkımızda.html">Hakkımızda</a>
        <a href="iletisim.html">İletişim</a>
        <a href="#">Kurallar</a>
        <a href="gizlilik.html">Gizlilik</a>
      </div>
    </div>
  </footer>

  <script>
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    const openSidebar = document.getElementById("openSidebar");
    const closeSidebar = document.getElementById("closeSidebar");

    openSidebar.addEventListener("click", () => {
      sidebar.classList.add("active");
      overlay.classList.add("active");
    });

    closeSidebar.addEventListener("click", () => {
      sidebar.classList.remove("active");
      overlay.classList.remove("active");
    });

    overlay.addEventListener("click", () => {
      sidebar.classList.remove("active");
      overlay.classList.remove("active");
    });

    // Animasyonlar için Intersection Observer
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
        }
      });
    });

    // Arama Fonksiyonu
    const mainSearch = document.getElementById('mainSearch');
    const searchResults = document.getElementById('searchResults');

    mainSearch.addEventListener('input', async (e) => {
        const val = e.target.value;
        if (val.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        const res = await fetch(`api_search.php?q=${val}`);
        const data = await res.json();

        if (data.length > 0) {
            searchResults.innerHTML = data.map(item => `
                <div class="search-item" onclick="location.href='Ehliyet/test.html?test=${item.test_key}'">
                    <i class="fa-solid fa-bolt"></i>
                    <div>
                        <strong>${item.title}</strong>
                        <span>${item.category}</span>
                    </div>
                </div>
            `).join('');
            searchResults.style.display = 'block';
        } else {
            searchResults.innerHTML = '<div class="search-item">Sonuç bulunamadı...</div>';
            searchResults.style.display = 'block';
        }
    });

    document.addEventListener('click', (e) => {
        if (!mainSearch.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Liderlik Tablosunu Doldur
    async function loadLeaderboard() {
        try {
            const res = await fetch('api_leaderboard.php');
            const data = await res.json();
            const list = document.getElementById('leaderboard-list');
            
            if (!data || data.length === 0) {
                list.innerHTML = '<div style="padding: 20px; text-align: center; color: #aab6e8;">Henüz skor kaydı yok. İlk sen ol!</div>';
                return;
            }

            const rankClasses = ['gold', 'silver', 'bronze'];
            const badges = ['Şampiyon', 'Efsane', 'Usta'];

            list.innerHTML = data.map((user, i) => `
                <div class="leader-item">
                    <span class="rank ${rankClasses[i] || ''}">${i + 1}</span>
                    <div class="leader-user">
                        <strong>${user.username}</strong>
                        <small>${user.score} Toplam Puan</small>
                    </div>
                    <span class="leader-badge">${badges[i] || user.quizzes_solved + ' Quiz'}</span>
                </div>
            `).join('');
        } catch (err) {
            console.error('Liderlik hatası:', err);
        }
    }

    loadLeaderboard();

  </script>
  <style>
    .search-results-overlay {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        margin-top: 10px;
        z-index: 1000;
        display: none;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }
    .search-item {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: 0.2s;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .search-item:hover { background: rgba(255, 255, 255, 0.05); }
    .search-item i { color: #3b82f6; }
    .search-item strong { display: block; color: white; font-size: 14px; }
    .search-item span { font-size: 12px; color: #94a3b8; }
    
    /* Premium Buton Efekti */
    .primary-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        border: none !important;
    }
    .primary-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }
  </style>
</body>
</html>
