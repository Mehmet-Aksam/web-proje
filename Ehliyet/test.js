const testBilgileri = {
 ingilizce_test_1: {
  baslik: "Temel Eşyalar Testi",
  aciklama: "Temel kelime bilgisi testi",
  sureDakika: 10
},
ingilizce_test_2: {
  baslik: "Cümle Çeviri Testi",
  aciklama: "Çeviri üzerine test",
  sureDakika: 10
},
ingilizce_test_3: {
  baslik: "Karışık Kelime Testi",
  aciklama: "Zor seviye kelimeler",
  sureDakika: 15
},
  levhalar_test_1: {
    baslik: "Levhalar ve Trafik İşaretleri",
    aciklama: "Temel trafik levhaları ve uyarı işaretleri üzerine test.",
    sureDakika: 25
  },
  levhalar_test_2: {
    baslik: "Genel Trafik Kuralları",
    aciklama: "Trafikte uyulması gereken temel kurallar ve çevre bilgisi.",
    sureDakika: 25
  },
  levhalar_test_3: {
    baslik: "Karışık Trafik Testi",
    aciklama: "Levhalar, kurallar ve genel trafik bilgisini karışık çöz.",
    sureDakika: 25
  },
  ilkyardim_test_1: {
    baslik: "Temel İlk Yardım Bilgisi",
    aciklama: "Yaralanma ve acil durumlarda temel müdahale bilgileri.",
    sureDakika: 20
  },
  ilkyardim_test_2: {
    baslik: "Kaza Anında Müdahale",
    aciklama: "Trafik kazalarında doğru ilk yardım adımlarını öğren.",
    sureDakika: 20
  },
  ilkyardim_test_3: {
    baslik: "Karışık İlk Yardım",
    aciklama: "İlk yardım konularını karışık sorularla tekrar et.",
    sureDakika: 20
  },
  arac_test_1: {
    baslik: "Motor ve Parçalar",
    aciklama: "Araç motoru ve temel parçalar hakkında test soruları.",
    sureDakika: 20
  },
  arac_test_2: {
    baslik: "Bakım ve Kontroller",
    aciklama: "Günlük araç kontrolleri ve bakım süreçleri üzerine test.",
    sureDakika: 20
  },
  arac_test_3: {
    baslik: "Karışık Araç Tekniği",
    aciklama: "Motor, bakım ve teknik bilgileri bir arada çöz.",
    sureDakika: 20
  },
  adab_test_1: {
    baslik: "Empati ve Saygı",
    aciklama: "Trafikte diğer sürücülere ve yayalara karşı doğru davranışlar.",
    sureDakika: 15
  },
  adab_test_2: {
    baslik: "Doğru Sürüş Davranışı",
    aciklama: "Sürücü sorumluluğu ve trafik adabına uygun hareket etme.",
    sureDakika: 15
  },
  adab_test_3: {
    baslik: "Karışık Trafik Adabı",
    aciklama: "Trafik adabı konularını karışık sorularla pekiştir.",
    sureDakika: 15
  },
  genelkultur_test_1: {
    baslik: "Antik Çağ ve Mitoloji",
    aciklama: "Antik uygarlıklar ve mitolojik efsaneler üzerine test.",
    sureDakika: 15
  },
  genelkultur_test_2: {
    baslik: "Yakın Çağ Tarihi",
    aciklama: "Dünya tarihinin yakın geçmişine dair önemli olaylar.",
    sureDakika: 20
  },
  genelkultur_test_3: {
    baslik: "Osmanlı İmparatorluğu",
    aciklama: "Osmanlı tarihi, padişahlar ve önemli savaşlar.",
    sureDakika: 25
  },
  genelkultur_test_4: {
    baslik: "Başkentler ve Bayraklar",
    aciklama: "Ülkeler, başkentleri ve bayrakları hakkında coğrafya testi.",
    sureDakika: 10
  },
  genelkultur_test_5: {
    baslik: "Dünya Klasikleri",
    aciklama: "Edebiyat dünyasının ölümsüz eserleri ve yazarları.",
    sureDakika: 20
  }
};

const urlParams = new URLSearchParams(window.location.search);
const testAdi = urlParams.get("test");

const testBaslik = document.getElementById("test-baslik");
const testAciklama = document.getElementById("test-aciklama");
const sureEl = document.getElementById("sure");
const soruSayisiEl = document.getElementById("soru-sayisi");
const dogruSayisiEl = document.getElementById("dogru-sayisi");
const yanlisSayisiEl = document.getElementById("yanlis-sayisi");
const sorularAlani = document.getElementById("sorular-alani");
const bitirBtn = document.getElementById("bitir-btn");
const sonucAlani = document.getElementById("sonuc-alani");
const yenidenBtn = document.getElementById("yeniden-btn");
const geriSoruBtn = document.getElementById("geri-soru-btn");
const ileriSoruBtn = document.getElementById("ileri-soru-btn");

let tumTestler = {};
let secimler = {};
let testSorulari = [];
let kalanSure = 0;
let zamanlayici = null;
let testBitti = false;
let aktifSoruIndex = 0;

function dakikaSaniyeYaz(saniye) {
  const dakika = Math.floor(saniye / 60);
  const saniyeKalan = saniye % 60;
  return `${String(dakika).padStart(2, "0")}:${String(saniyeKalan).padStart(2, "0")}`;
}

function ustBilgileriGuncelle() {
  let dogru = 0;
  let yanlis = 0;

  testSorulari.forEach((soru, index) => {
    const secilen = secimler[index];
    if (!secilen) return;

    if (secilen === soru.dogru) {
      dogru++;
    } else {
      yanlis++;
    }
  });

  dogruSayisiEl.textContent = dogru;
  yanlisSayisiEl.textContent = yanlis;
  soruSayisiEl.textContent = `${aktifSoruIndex + 1} / ${testSorulari.length}`;
}

function gecisButonlariniGuncelle() {
  geriSoruBtn.disabled = aktifSoruIndex === 0;
  ileriSoruBtn.disabled = aktifSoruIndex === testSorulari.length - 1;
}

function cevapClassiHesapla(secenekHarf, secilen, dogruCevap) {
  if (!secilen) return "";

  if (secenekHarf === secilen && secenekHarf === dogruCevap) {
    return "secildi dogru";
  }

  if (secenekHarf === secilen && secenekHarf !== dogruCevap) {
    return "secildi yanlis";
  }

  if (secenekHarf === dogruCevap) {
    return "dogru";
  }

  return "";
}

function soruKartiniGoster() {
  if (!testSorulari.length) {
    sorularAlani.innerHTML = "<p>Bu test için henüz soru eklenmemiş.</p>";
    bitirBtn.disabled = true;
    geriSoruBtn.disabled = true;
    ileriSoruBtn.disabled = true;
    return;
  }

  const soru = testSorulari[aktifSoruIndex];
  const secilen = secimler[aktifSoruIndex];

  const resimHtml = soru.resim
    ? `
      <div class="soru-gorsel-alani">
        <img src="${soru.resim}" alt="Soru görseli" class="soru-resim">
      </div>
    `
    : "";

  const aClass = cevapClassiHesapla("a", secilen, soru.dogru);
  const bClass = cevapClassiHesapla("b", secilen, soru.dogru);
  const cClass = cevapClassiHesapla("c", secilen, soru.dogru);
  const dClass = cevapClassiHesapla("d", secilen, soru.dogru);

  const disabledAttr = secilen || testBitti ? "disabled" : "";

  sorularAlani.innerHTML = `
    <article class="soru-karti tek-soru-karti" data-soru-index="${aktifSoruIndex}">
      <div class="soru-karti__ust">
        <span class="soru-no">Soru ${aktifSoruIndex + 1}</span>
      </div>

      ${resimHtml}

      <h3 class="soru-metin">${soru.soru}</h3>

      <div class="secenekler">
        <button class="secenek-btn ${aClass}" data-soru="${aktifSoruIndex}" data-secim="a" ${disabledAttr}>
          <span>A</span> ${soru.a}
        </button>

        <button class="secenek-btn ${bClass}" data-soru="${aktifSoruIndex}" data-secim="b" ${disabledAttr}>
          <span>B</span> ${soru.b}
        </button>

        <button class="secenek-btn ${cClass}" data-soru="${aktifSoruIndex}" data-secim="c" ${disabledAttr}>
          <span>C</span> ${soru.c}
        </button>

        <button class="secenek-btn ${dClass}" data-soru="${aktifSoruIndex}" data-secim="d" ${disabledAttr}>
          <span>D</span> ${soru.d}
        </button>
      </div>
    </article>
  `;

  gecisButonlariniGuncelle();
  ustBilgileriGuncelle();
}

function secimYap(soruIndex, secim) {
  if (testBitti) return;

  secimler[soruIndex] = secim;
  soruKartiniGoster();
}

function oncekiSoru() {
  if (aktifSoruIndex > 0) {
    aktifSoruIndex--;
    soruKartiniGoster();
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
}

function sonrakiSoru() {
  if (aktifSoruIndex < testSorulari.length - 1) {
    aktifSoruIndex++;
    soruKartiniGoster();
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
}

function sonucuGoster() {
  let dogru = 0;
  let yanlis = 0;
  let bos = 0;

  testSorulari.forEach((soru, index) => {
    const secilen = secimler[index];

    if (!secilen) {
      bos++;
    } else if (secilen === soru.dogru) {
      dogru++;
    } else {
      yanlis++;
    }
  });

  const toplam = testSorulari.length;
  const skor = toplam > 0 ? Math.round((dogru / toplam) * 100) : 0;

  document.getElementById("sonuc-toplam").textContent = toplam;
  document.getElementById("sonuc-dogru").textContent = dogru;
  document.getElementById("sonuc-yanlis").textContent = yanlis;
  document.getElementById("sonuc-bos").textContent = bos;
  document.getElementById("sonuc-skor").textContent = `${skor}%`;

  sonucAlani.classList.remove("gizli");
  sonucAlani.scrollIntoView({ behavior: "smooth" });
}

function testiBitir() {
  if (testBitti) return;

  testBitti = true;

  if (zamanlayici) {
    clearInterval(zamanlayici);
  }

  soruKartiniGoster();
  bitirBtn.disabled = true;
  sonucuGoster();

  // Skoru veritabanına kaydet
  let dogru = 0, yanlis = 0;
  testSorulari.forEach((soru, index) => {
    const secilen = secimler[index];
    if (secilen === soru.dogru) dogru++;
    else if (secilen) yanlis++;
  });
  const toplam = testSorulari.length;
  const skor = toplam > 0 ? Math.round((dogru / toplam) * 100) : 0;

  fetch('../api_score.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      test_key: testAdi,
      score: skor,
      correct: dogru,
      wrong: yanlis,
      total: toplam
    })
  }).catch(() => {});
}

function sayaciBaslat() {
  sureEl.textContent = dakikaSaniyeYaz(kalanSure);

  zamanlayici = setInterval(() => {
    kalanSure--;

    if (kalanSure <= 0) {
      sureEl.textContent = "00:00";
      clearInterval(zamanlayici);
      testiBitir();
      return;
    }

    sureEl.textContent = dakikaSaniyeYaz(kalanSure);
  }, 1000);
}

function testiHazirla(data) {
  let bilgi = testBilgileri[testAdi];

  if (!testAdi) {
    testBaslik.textContent = "Test seçilmedi";
    testAciklama.textContent = "Test linkinde eksik parametre var.";
    sorularAlani.innerHTML = "<p>Önce test seçmelisin.</p>";
    bitirBtn.disabled = true;
    geriSoruBtn.disabled = true;
    ileriSoruBtn.disabled = true;
    return;
  }

  // Eğer test bilgisi hardcoded olarak yoksa, dinamik oluştur:
  if (!bilgi && data && data.length > 0) {
    const formatliBaslik = testAdi
      .replace(/_/g, ' ')
      .replace(/\b\w/g, l => l.toUpperCase());
      
    bilgi = {
      baslik: formatliBaslik,
      aciklama: formatliBaslik + " Testi",
      sureDakika: 20
    };
  }

  if (!bilgi || !data || data.length === 0) {
    testBaslik.textContent = "Test bulunamadı";
    testAciklama.textContent = "Link yanlış olabilir veya bu test henüz veritabanında yok.";
    sorularAlani.innerHTML = "<p>Test verisi bulunamadı.</p>";
    bitirBtn.disabled = true;
    geriSoruBtn.disabled = true;
    ileriSoruBtn.disabled = true;
    return;
  }

  testSorulari = data;
  testBaslik.textContent = bilgi.baslik;
  testAciklama.textContent = bilgi.aciklama;
  kalanSure = bilgi.sureDakika * 60;

  soruKartiniGoster();
  sayaciBaslat();
}

document.addEventListener("click", (e) => {
  const buton = e.target.closest(".secenek-btn");
  if (!buton) return;

  const soruIndex = Number(buton.dataset.soru);
  const secim = buton.dataset.secim;
  secimYap(soruIndex, secim);
});

geriSoruBtn.addEventListener("click", oncekiSoru);
ileriSoruBtn.addEventListener("click", sonrakiSoru);
bitirBtn.addEventListener("click", testiBitir);

yenidenBtn.addEventListener("click", () => {
  window.location.reload();
});

if (testAdi) {
 fetch("../api_questions.php?category=" + testAdi)
    .then((res) => {
      if (!res.ok) {
        throw new Error("Veritabanından sorular yüklenemedi");
      }
      return res.json();
    })
    .then((data) => {
      testiHazirla(data);
    })
    .catch((err) => {
      console.error(err);
      testBaslik.textContent = "Veri yüklenemedi";
      testAciklama.textContent = "Veritabanına bağlanılamadı.";
      sorularAlani.innerHTML = "<p>Sorular yüklenirken bir hata oluştu.</p>";
      bitirBtn.disabled = true;
      geriSoruBtn.disabled = true;
      ileriSoruBtn.disabled = true;
    });
} else {
  testiHazirla(null);

 
}