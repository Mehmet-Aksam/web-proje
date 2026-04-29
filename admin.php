<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Panel - Sorcik</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css">
  <style>
    .admin-page { width: min(1100px, 94%); margin: 30px auto 60px; }
    .admin-header { text-align: center; margin-bottom: 30px; }
    .admin-header h2 { font-size: 32px; margin-bottom: 8px; }
    .admin-header p { color: #aab6e8; }
    .admin-tabs { display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: wrap; justify-content: center; }
    .admin-tab { padding: 12px 24px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: #fff; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 14px; }
    .admin-tab:hover, .admin-tab.active { background: linear-gradient(135deg, #ffcc3c, #ffae00); color: #18244a; }
    .admin-section { display: none; }
    .admin-section.active { display: block; }
    .admin-card { background: rgba(10,0,21,0.85); border: 1px solid rgba(0,255,255,0.3); border-radius: 24px; padding: 28px; margin-bottom: 20px; backdrop-filter: blur(15px); }
    .admin-card h3 { font-size: 22px; margin-bottom: 16px; color: #ffd86e; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
    .form-row.full { grid-template-columns: 1fr; }
    .form-field { display: flex; flex-direction: column; gap: 6px; }
    .form-field label { font-size: 13px; color: #aab6e8; font-weight: 600; }
    .form-field input, .form-field select, .form-field textarea { padding: 12px 16px; border-radius: 14px; border: 1px solid rgba(0,255,255,0.3); background: rgba(255,255,255,0.05); color: #fff; font-size: 14px; font-family: inherit; }
    .form-field textarea { min-height: 80px; resize: vertical; }
    .form-field input:focus, .form-field select:focus, .form-field textarea:focus { outline: none; border-color: #00FFFF; box-shadow: 0 0 0 3px rgba(0,255,255,0.15); }
    .form-field select option { background: #1a0033; color: #fff; }
    .admin-btn { padding: 12px 28px; border-radius: 16px; border: none; font-weight: 800; font-size: 14px; cursor: pointer; transition: 0.2s; }
    .admin-btn.primary { background: linear-gradient(135deg, #00FFFF, #0099FF); color: #fff; }
    .admin-btn.danger { background: linear-gradient(135deg, #ff5f52, #ff3333); color: #fff; }
    .admin-btn:hover { transform: translateY(-2px); }
    .admin-btn.small { padding: 8px 16px; font-size: 12px; border-radius: 10px; }
    .data-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    .data-table th, .data-table td { padding: 12px 14px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.08); font-size: 13px; }
    .data-table th { color: #ffd86e; font-weight: 700; font-size: 12px; text-transform: uppercase; }
    .data-table td { color: #d9e1ff; }
    .data-table tr:hover { background: rgba(255,255,255,0.03); }
    .toast { position: fixed; bottom: 30px; right: 30px; padding: 16px 24px; border-radius: 16px; color: #fff; font-weight: 700; z-index: 9999; animation: slideUp 0.3s ease; font-size: 14px; }
    .toast.success { background: linear-gradient(135deg, #00c853, #00e676); }
    .toast.error { background: linear-gradient(135deg, #ff5f52, #ff3333); }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .back-link { display: inline-flex; align-items: center; gap: 8px; color: #ffd86e; text-decoration: none; font-weight: 700; margin-bottom: 20px; }
    @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } .data-table { font-size: 11px; } .data-table th, .data-table td { padding: 8px 6px; } }
  </style>
</head>
<body>
  <div class="top-strip"></div>
  <div class="admin-page">
    <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Ana Sayfaya Dön</a>
    <div class="admin-header">
      <i class="fa-solid fa-shield-halved" style="font-size: 48px; color: #ffcc3c; margin-bottom: 16px;"></i>
      <h2>Admin Panel</h2>
      <p>Test ve soru yönetimi · <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    </div>

    <div class="admin-tabs">
      <button class="admin-tab active" onclick="showTab('tests')"><i class="fa-solid fa-list"></i> Testler</button>
      <button class="admin-tab" onclick="showTab('questions')"><i class="fa-solid fa-circle-question"></i> Sorular</button>
      <button class="admin-tab" onclick="showTab('users')"><i class="fa-solid fa-users"></i> Kullanıcılar</button>
    </div>

    <!-- TEST YÖNETİMİ -->
    <div class="admin-section active" id="sec-tests">
      <div class="admin-card">
        <h3><i class="fa-solid fa-plus"></i> Yeni Test Ekle</h3>
        <div class="form-row">
          <div class="form-field"><label>Test Anahtarı (benzersiz)</label><input id="t-key" placeholder="ornek_test_1"></div>
          <div class="form-field"><label>Test Başlığı</label><input id="t-title" placeholder="Örnek Test Başlığı"></div>
        </div>
        <div class="form-row">
          <div class="form-field"><label>Kategori</label>
            <select id="t-category"><option value="ehliyet">Ehliyet</option><option value="genel_kultur">Genel Kültür</option><option value="ingilizce">İngilizce</option></select>
          </div>
          <div class="form-field"><label>Süre (dakika)</label><input id="t-duration" type="number" value="20" min="1"></div>
        </div>
        <div class="form-row full">
          <div class="form-field"><label>Açıklama</label><textarea id="t-desc" placeholder="Test açıklaması..."></textarea></div>
        </div>
        <button class="admin-btn primary" onclick="addTest()"><i class="fa-solid fa-plus"></i> Test Ekle</button>
      </div>
      <div class="admin-card">
        <h3><i class="fa-solid fa-table"></i> Mevcut Testler</h3>
        <div id="tests-table">Yükleniyor...</div>
      </div>
    </div>

    <!-- SORU YÖNETİMİ -->
    <div class="admin-section" id="sec-questions">
      <div class="admin-card">
        <h3><i class="fa-solid fa-plus"></i> Yeni Soru Ekle</h3>
        <div class="form-row">
          <div class="form-field"><label>Test Kategorisi</label><select id="q-category"></select></div>
          <div class="form-field"><label>Resim URL (opsiyonel)</label><input id="q-image" placeholder="fotolar/resim.jpg"></div>
        </div>
        <div class="form-row full">
          <div class="form-field"><label>Soru Metni</label><textarea id="q-text" placeholder="Soru metnini yazın..."></textarea></div>
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
          <div class="form-field"><label>Doğru Cevap</label>
            <select id="q-correct"><option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option></select>
          </div>
        </div>
        <button class="admin-btn primary" onclick="addQuestion()"><i class="fa-solid fa-plus"></i> Soru Ekle</button>
      </div>
      <div class="admin-card">
        <h3><i class="fa-solid fa-table"></i> Mevcut Sorular</h3>
        <div class="form-row" style="margin-bottom:16px">
          <div class="form-field"><label>Filtreleme</label><select id="q-filter" onchange="loadQuestions()"><option value="">Tüm Sorular</option></select></div>
        </div>
        <div id="questions-table">Yükleniyor...</div>
      </div>
    </div>

    <!-- KULLANICI YÖNETİMİ -->
    <div class="admin-section" id="sec-users">
      <div class="admin-card">
        <h3><i class="fa-solid fa-users"></i> Kayıtlı Kullanıcılar</h3>
        <div id="users-table">Yükleniyor...</div>
      </div>
    </div>
  </div>

<script>
function showTab(tab) {
  document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
  document.getElementById('sec-' + tab).classList.add('active');
  event.target.closest('.admin-tab').classList.add('active');
  if (tab === 'tests') loadTests();
  if (tab === 'questions') loadQuestions();
  if (tab === 'users') loadUsers();
}

function toast(msg, type = 'success') {
  const t = document.createElement('div');
  t.className = 'toast ' + type;
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3000);
}

// ===== TEST =====
async function loadTests() {
  const res = await fetch('api_admin.php?action=get_tests');
  const tests = await res.json();
  // Soru kategori dropdown'ları güncelle
  const qCat = document.getElementById('q-category');
  const qFilter = document.getElementById('q-filter');
  qCat.innerHTML = tests.map(t => `<option value="${t.test_key}">${t.title} (${t.test_key})</option>`).join('');
  qFilter.innerHTML = '<option value="">Tüm Sorular</option>' + tests.map(t => `<option value="${t.test_key}">${t.title}</option>`).join('');

  if (tests.length === 0) {
    document.getElementById('tests-table').innerHTML = '<p style="color:#aab6e8">Henüz test yok.</p>';
    return;
  }
  document.getElementById('tests-table').innerHTML = `<table class="data-table"><thead><tr><th>Anahtar</th><th>Başlık</th><th>Kategori</th><th>Süre</th><th>İşlem</th></tr></thead><tbody>${tests.map(t => `<tr><td>${t.test_key}</td><td>${t.title}</td><td>${t.category}</td><td>${t.duration_minutes} dk</td><td><button class="admin-btn danger small" onclick="deleteTest(${t.id})"><i class="fa-solid fa-trash"></i></button></td></tr>`).join('')}</tbody></table>`;
}

async function addTest() {
  const body = { test_key: document.getElementById('t-key').value, title: document.getElementById('t-title').value, description: document.getElementById('t-desc').value, category: document.getElementById('t-category').value, duration_minutes: parseInt(document.getElementById('t-duration').value) };
  const res = await fetch('api_admin.php?action=add_test', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(body) });
  const data = await res.json();
  toast(data.message, data.status);
  if (data.status === 'success') { document.getElementById('t-key').value = ''; document.getElementById('t-title').value = ''; document.getElementById('t-desc').value = ''; loadTests(); }
}

async function deleteTest(id) {
  if (!confirm('Bu testi ve tüm sorularını silmek istediğinize emin misiniz?')) return;
  const res = await fetch('api_admin.php?action=delete_test', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({id}) });
  const data = await res.json();
  toast(data.message, data.status);
  loadTests();
}

// ===== SORULAR =====
async function loadQuestions() {
  const cat = document.getElementById('q-filter').value;
  const url = cat ? `api_admin.php?action=get_questions&category=${cat}` : 'api_admin.php?action=get_questions';
  const res = await fetch(url);
  const questions = await res.json();
  if (questions.length === 0) {
    document.getElementById('questions-table').innerHTML = '<p style="color:#aab6e8">Bu kategoride soru yok.</p>';
    return;
  }
  document.getElementById('questions-table').innerHTML = `<table class="data-table"><thead><tr><th>#</th><th>Kategori</th><th>Soru</th><th>Doğru</th><th>İşlem</th></tr></thead><tbody>${questions.map(q => `<tr><td>${q.id}</td><td>${q.category}</td><td>${q.soru.substring(0,60)}${q.soru.length > 60 ? '...' : ''}</td><td style="text-transform:uppercase;font-weight:800;color:#00FFFF">${q.dogru}</td><td><button class="admin-btn danger small" onclick="deleteQuestion(${q.id})"><i class="fa-solid fa-trash"></i></button></td></tr>`).join('')}</tbody></table>`;
}

async function addQuestion() {
  const body = { category: document.getElementById('q-category').value, soru: document.getElementById('q-text').value, resim: document.getElementById('q-image').value, a: document.getElementById('q-a').value, b: document.getElementById('q-b').value, c: document.getElementById('q-c').value, d: document.getElementById('q-d').value, dogru: document.getElementById('q-correct').value };
  const res = await fetch('api_admin.php?action=add_question', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(body) });
  const data = await res.json();
  toast(data.message, data.status);
  if (data.status === 'success') { ['q-text','q-image','q-a','q-b','q-c','q-d'].forEach(id => document.getElementById(id).value = ''); loadQuestions(); }
}

async function deleteQuestion(id) {
  if (!confirm('Bu soruyu silmek istediğinize emin misiniz?')) return;
  const res = await fetch('api_admin.php?action=delete_question', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({id}) });
  const data = await res.json();
  toast(data.message, data.status);
  loadQuestions();
}

// ===== KULLANICILAR =====
async function loadUsers() {
  const res = await fetch('api_admin.php?action=get_users');
  const users = await res.json();
  document.getElementById('users-table').innerHTML = `<table class="data-table"><thead><tr><th>#</th><th>Kullanıcı</th><th>E-posta</th><th>Rol</th><th>Skor</th><th>Quiz</th></tr></thead><tbody>${users.map(u => `<tr><td>${u.id}</td><td>${u.username}</td><td>${u.email}</td><td style="color:${u.role==='admin'?'#ffcc3c':'#00FFFF'};font-weight:700">${u.role}</td><td>${u.score}</td><td>${u.quizzes_solved}</td></tr>`).join('')}</tbody></table>`;
}

// Sayfa yüklenince testleri yükle
loadTests();
</script>
</body>
</html>
