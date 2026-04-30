<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Soru Öner - Sorcik</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css">
  <style>
    .create-page { width: min(900px, 94%); margin: 30px auto 60px; }
    .create-header { text-align: center; margin-bottom: 40px; }
    .create-header h2 { font-size: 32px; margin-bottom: 8px; }
    .create-header p { color: #aab6e8; max-width: 500px; margin: 0 auto; }

    .create-card {
      background: rgba(10,0,21,0.85);
      border: 1px solid rgba(0,255,255,0.3);
      border-radius: 24px;
      padding: 28px;
      margin-bottom: 24px;
      backdrop-filter: blur(15px);
    }
    .create-card h3 { font-size: 20px; margin-bottom: 16px; color: #ffd86e; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
    .form-row.full { grid-template-columns: 1fr; }
    .form-field { display: flex; flex-direction: column; gap: 6px; }
    .form-field label { font-size: 13px; color: #aab6e8; font-weight: 600; }
    .form-field input, .form-field select, .form-field textarea {
      padding: 12px 16px; border-radius: 14px;
      border: 1px solid rgba(0,255,255,0.3);
      background: rgba(255,255,255,0.05);
      color: #fff; font-size: 14px; font-family: inherit;
    }
    .form-field textarea { min-height: 80px; resize: vertical; }
    .form-field input:focus, .form-field select:focus, .form-field textarea:focus {
      outline: none; border-color: #00FFFF;
      box-shadow: 0 0 0 3px rgba(0,255,255,0.15);
    }
    .form-field select option { background: #1a0033; color: #fff; }

    .create-btn {
      padding: 14px 32px; border-radius: 16px; border: none;
      font-weight: 800; font-size: 15px; cursor: pointer;
      transition: 0.2s;
      background: linear-gradient(135deg, #00FFFF, #0099FF);
      color: #fff;
    }
    .create-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,153,255,0.3); }

    .back-link {
      display: inline-flex; align-items: center; gap: 8px;
      color: #ffd86e; text-decoration: none; font-weight: 700; margin-bottom: 20px;
    }

    .info-banner {
      background: linear-gradient(135deg, rgba(0,255,255,0.1), rgba(59,130,246,0.1));
      border: 1px solid rgba(0,255,255,0.2);
      border-radius: 16px;
      padding: 16px 20px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .info-banner i { font-size: 24px; color: #00FFFF; }
    .info-banner p { color: #d9e1ff; font-size: 13px; line-height: 1.5; }

    .toast {
      position: fixed; bottom: 30px; right: 30px;
      padding: 16px 24px; border-radius: 16px;
      color: #fff; font-weight: 700; z-index: 9999;
      animation: slideUp 0.3s ease; font-size: 14px;
    }
    .toast.success { background: linear-gradient(135deg, #00c853, #00e676); }
    .toast.error { background: linear-gradient(135deg, #ff5f52, #ff3333); }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .my-questions { margin-top: 16px; }
    .mq-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .mq-item:last-child { border-bottom: none; }
    .mq-status {
      padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;
      text-transform: uppercase;
    }
    .mq-status.pending { background: rgba(255,200,0,0.2); color: #ffcc3c; }
    .mq-status.approved { background: rgba(0,200,83,0.2); color: #00e676; }
    .mq-status.rejected { background: rgba(255,95,82,0.2); color: #ff5f52; }

    @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } }
  </style>
</head>
<body>
  <div class="top-strip"></div>
  <div class="create-page">
    <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Ana Sayfaya Dön</a>

    <div class="create-header">
      <i class="fa-solid fa-lightbulb" style="font-size: 48px; color: #ffcc3c; margin-bottom: 16px;"></i>
      <h2>Soru Öner</h2>
      <p>Kendi sorularını hazırla ve topluluğa katkıda bulun! Admin onayından geçen sorular quizlerde kullanılacak.</p>
    </div>

    <div class="info-banner">
      <i class="fa-solid fa-circle-info"></i>
      <p>
        Gönderdiğin sorular <strong>admin onayı</strong> gerektirir. Onaylanan sorular ilgili testte yayına alınır.
        Durumunu aşağıda "Sorularım" bölümünden takip edebilirsin.
      </p>
    </div>

    <div class="create-card">
      <h3><i class="fa-solid fa-plus-circle"></i> Yeni Soru Gönder</h3>
      <div class="form-row">
        <div class="form-field">
          <label>Kategori / Test</label>
          <select id="q-category"></select>
        </div>
        <div class="form-field">
          <label>Resim URL (opsiyonel)</label>
          <input id="q-image" placeholder="örn: fotolar/resim.jpg">
        </div>
      </div>
      <div class="form-row full">
        <div class="form-field">
          <label>Soru Metni</label>
          <textarea id="q-text" placeholder="Sorunuzu buraya yazın..."></textarea>
        </div>
      </div>
      <div class="form-row">
        <div class="form-field"><label>A Şıkkı</label><input id="q-a" placeholder="A şıkkı"></div>
        <div class="form-field"><label>B Şıkkı</label><input id="q-b" placeholder="B şıkkı"></div>
      </div>
      <div class="form-row">
        <div class="form-field"><label>C Şıkkı</label><input id="q-c" placeholder="C şıkkı"></div>
        <div class="form-field"><label>D Şıkkı</label><input id="q-d" placeholder="D şıkkı"></div>
      </div>
      <div class="form-row">
        <div class="form-field">
          <label>Doğru Cevap</label>
          <select id="q-correct">
            <option value="a">A</option>
            <option value="b">B</option>
            <option value="c">C</option>
            <option value="d">D</option>
          </select>
        </div>
      </div>
      <button class="create-btn" onclick="submitQuestion()">
        <i class="fa-solid fa-paper-plane"></i> Soruyu Gönder
      </button>
    </div>

    <!-- Kullanıcının kendi önerdiği sorular -->
    <div class="create-card">
      <h3><i class="fa-solid fa-clock-rotate-left"></i> Sorularım</h3>
      <div id="my-questions">Yükleniyor...</div>
    </div>
  </div>

<script>
function toast(msg, type = 'success') {
  const t = document.createElement('div');
  t.className = 'toast ' + type;
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

// Kategorileri yükle
async function loadCategories() {
  try {
    const res = await fetch('api_user_question.php?action=get_categories');
    const cats = await res.json();
    const sel = document.getElementById('q-category');
    sel.innerHTML = cats.map(c => `<option value="${c.test_key}">${c.title}</option>`).join('');
  } catch(e) {
    console.error('Kategori yükleme hatası:', e);
  }
}

// Soru gönder
async function submitQuestion() {
  const body = {
    category: document.getElementById('q-category').value,
    soru: document.getElementById('q-text').value.trim(),
    resim: document.getElementById('q-image').value.trim(),
    a: document.getElementById('q-a').value.trim(),
    b: document.getElementById('q-b').value.trim(),
    c: document.getElementById('q-c').value.trim(),
    d: document.getElementById('q-d').value.trim(),
    dogru: document.getElementById('q-correct').value
  };

  if (!body.soru || !body.a || !body.b || !body.c || !body.d) {
    toast('Lütfen tüm alanları doldurun.', 'error');
    return;
  }

  try {
    const res = await fetch('api_user_question.php?action=submit', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });
    const data = await res.json();
    toast(data.message, data.status);

    if (data.status === 'success') {
      ['q-text','q-image','q-a','q-b','q-c','q-d'].forEach(id => document.getElementById(id).value = '');
      loadMyQuestions();
    }
  } catch(e) {
    toast('Sunucu hatası.', 'error');
  }
}

// Kullanıcının kendi sorularını yükle
async function loadMyQuestions() {
  try {
    const res = await fetch('api_user_question.php?action=my_questions');
    const questions = await res.json();
    const container = document.getElementById('my-questions');

    if (questions.length === 0) {
      container.innerHTML = '<p style="color:#aab6e8; text-align:center; padding:20px;">Henüz soru göndermediniz.</p>';
      return;
    }

    const statusLabels = { pending: 'Beklemede', approved: 'Onaylandı', rejected: 'Reddedildi' };

    container.innerHTML = `<div class="my-questions">${questions.map(q => `
      <div class="mq-item">
        <div>
          <strong style="color:#fff; font-size:14px;">${q.soru.substring(0,60)}${q.soru.length > 60 ? '...' : ''}</strong>
          <div style="font-size:12px; color:#aab6e8; margin-top:4px;">${q.category} · ${q.created_at}</div>
        </div>
        <span class="mq-status ${q.status}">${statusLabels[q.status] || q.status}</span>
      </div>
    `).join('')}</div>`;
  } catch(e) {
    console.error('Soru yükleme hatası:', e);
  }
}

loadCategories();
loadMyQuestions();
</script>
</body>
</html>
