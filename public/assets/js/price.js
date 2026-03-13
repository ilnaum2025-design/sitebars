async function loadPrices() {
  const res = await fetch('/api/prices.php');
  const data = await res.json();
  const root = document.getElementById('priceContainer');
  root.innerHTML = '';
  (data.razdels || []).forEach(r => {
    const card = document.createElement('section');
    card.className = 'card';
    const rows = r.services.map(s => `<div class="price-row"><div>${s.title}</div><div>${s.isFrom ? 'от' : 'фикс'}</div><div>${s.cost} ₽</div></div>`).join('');
    card.innerHTML = `<h3>${r.title}</h3><div class="price-grid">${rows}</div>`;
    root.append(card);
  });
}
loadPrices();
