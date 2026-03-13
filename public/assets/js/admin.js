const root = document.getElementById('razdelList');

function createServiceItem(data={title:'',isFrom:true,cost:''}) {
  const div = document.createElement('div');
  div.className = 'service-item';
  div.innerHTML = `<input placeholder="Название услуги" value="${data.title}"><button type="button" class="btn secondary modeBtn">${data.isFrom ? 'от':'фикс'}</button><input placeholder="Стоимость" value="${data.cost}">`;
  div.querySelector('.modeBtn').onclick = (e) => {
    e.target.textContent = e.target.textContent === 'от' ? 'фикс' : 'от';
  };
  return div;
}

function createRazdel(data={title:'',services:[]}) {
  const block = document.createElement('div');
  block.className = 'admin-block';
  block.innerHTML = `<input class="razdel-title" placeholder="Название раздела" value="${data.title}"><div class="services"></div><p><button class="btn secondary addService">+ добавить услугу</button></p>`;
  const servicesRoot = block.querySelector('.services');
  (data.services || []).forEach(s => servicesRoot.append(createServiceItem(s)));
  block.querySelector('.addService').onclick = () => servicesRoot.append(createServiceItem());
  return block;
}

async function ensureAuth() {
  const s = await (await fetch('/api/session.php')).json();
  if (!s.authorized) location.href = '/admin-login.php';
}

async function load() {
  await ensureAuth();
  const res = await fetch('/api/prices.php');
  const data = await res.json();
  root.innerHTML = '';
  (data.razdels || []).forEach(r => root.append(createRazdel(r)));
}

document.getElementById('addCategory').onclick = () => root.append(createRazdel());

document.getElementById('saveAll').onclick = async () => {
  const razdels = [...root.querySelectorAll('.admin-block')].map(block => ({
    title: block.querySelector('.razdel-title').value.trim(),
    services: [...block.querySelectorAll('.service-item')].map(s => ({
      title: s.children[0].value.trim(),
      isFrom: s.children[1].textContent.trim() === 'от',
      cost: s.children[2].value.trim()
    }))
  }));

  const res = await fetch('/api/save-prices.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({razdels})});
  const data = await res.json();
  document.getElementById('saveStatus').textContent = data.ok ? 'Сохранено' : (data.error || 'Ошибка');
};

document.getElementById('logoutBtn').onclick = async () => {
  await fetch('/api/logout.php');
  location.href = '/admin-login.php';
};

load();
