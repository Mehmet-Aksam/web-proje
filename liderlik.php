<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liderlik - Sorcik</title>

  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="top-strip"></div>

  <main class="home-page">
    <div class="hero-content" style="text-align:center; margin-bottom:40px;">
      <i class="fa-solid fa-ranking-star" style="font-size:48px; color:#ffcc3c; margin-bottom:20px;"></i>
      <h2>Liderlik Tablosu</h2>
      <p>En iyi oyuncuları gör ve kendi sıralamanı takip et.</p>
      <a href="index.php" class="hero-secondary" style="margin-top:20px;">Ana Sayfaya Dön</a>
    </div>

    <section class="leaderboard">
      <div class="leaderboard-list" id="leaderboard-list">
        <p style="color:#aab6e8; text-align:center;">Yükleniyor...</p>
      </div>
    </section>
  </main>

  <script>
    async function loadLeaderboard() {
      const list = document.getElementById('leaderboard-list');

      try {
        const res = await fetch('api_leaderboard.php');

        if (!res.ok) {
          throw new Error('API çalışmadı');
        }

        const data = await res.json();

        if (!data || data.length === 0) {
          list.innerHTML = `
            <p style="color:#aab6e8; text-align:center; padding:30px;">
              Henüz skor kaydı yok. Quiz çözerek liderlik tablosunda yerini al!
            </p>
          `;
          return;
        }

        const rankClasses = ['gold', 'silver', 'bronze'];
        const badges = ['Şampiyon', 'İddialı', 'Yükselişte'];

        list.innerHTML = data.map((user, i) => `
          <div class="leader-item">
            <span class="rank ${rankClasses[i] || ''}">${i + 1}</span>
            <div class="leader-user">
              <strong>${user.username}</strong>
              <small>${user.score} puan · ${user.quizzes_solved} quiz</small>
            </div>
            <span class="leader-badge">${badges[i] || user.quizzes_solved + ' quiz'}</span>
          </div>
        `).join('');

      } catch (err) {
        list.innerHTML = `
          <p style="color:#ff5f52; text-align:center; padding:30px;">
            Liderlik verileri yüklenemedi. api_leaderboard.php dosyasını kontrol et.
          </p>
        `;
        console.error(err);
      }
    }

    loadLeaderboard();
  </script>
</body>
</html>