const $ = (s) => document.querySelector(s);

async function loadContent() {
  const res = await fetch('/api/content.php');
  const data = await res.json();
  $('#subscribeBtn').href = data.vkSubscribeUrl;
  $('#phoneValue').textContent = data.phone;
  $('#addressText').textContent = data.address;
  $('#routeBtn').href = `https://yandex.ru/maps/?rtext=~${encodeURIComponent(data.address)}&rtt=auto`;
  renderPosts(data.vkPosts || []);
  renderReviews(data.reviews || [], data.reviewCount || 0);
  initMap(data.address);
}

function renderPosts(posts) {
  const root = $('#vkPosts');
  root.innerHTML = '';
  posts.forEach(p => {
    const words = (p.text || '').trim().split(/\s+/);
    const lead = words.slice(0,3).join(' ');
    const rest = words.slice(3).join(' ');
    const date = new Date(p.date * 1000).toLocaleDateString('ru-RU');
    const card = document.createElement('article');
    card.className = 'card';
    card.innerHTML = `<img src="${p.image}" style="width:100%;border-radius:10px"><p class="post-title">${lead}</p><p class="clamp3">${rest}</p><p>${date} • ❤ ${p.likes} • 💬 ${p.comments}</p><a class="btn" href="${p.url}" target="_blank">читать</a>`;
    root.append(card);
  });
}

function renderReviews(reviews, count) {
  $('#reviewCount').textContent = `${count} отзывов на Яндекс Картах`;
  const root = $('#reviews');
  root.innerHTML = '';
  reviews.filter(r => r.rating >= 4).forEach(r => {
    const stars = '★'.repeat(r.rating) + '☆'.repeat(5-r.rating);
    const div = document.createElement('div');
    div.className = 'card';
    div.innerHTML = `<div style="display:flex;gap:10px;align-items:center"><img src="${r.avatar}" style="border-radius:50%"><div><b>${r.name}</b><div>${r.date}</div></div></div><p>${stars}</p><p>${r.text}</p>`;
    root.append(div);
  });
}

function initMap(address) {
  if (!window.ymaps) return;
  ymaps.ready(() => {
    const map = new ymaps.Map('map', {center: [55.75396, 37.620393], zoom: 14});
    ymaps.geocode(address, {results:1}).then(res => {
      const obj = res.geoObjects.get(0);
      if (!obj) return;
      map.geoObjects.add(obj);
      map.setCenter(obj.geometry.getCoordinates(), 16);
    });
  });
}

$('#openBooking').onclick = () => $('#bookingOverlay').classList.add('active');
$('#closeBooking').onclick = () => $('#bookingOverlay').classList.remove('active');
$('#copyPhone').onclick = async () => {
  await navigator.clipboard.writeText($('#phoneValue').textContent.trim());
  alert('Номер скопирован');
};

loadContent();
