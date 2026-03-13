document.getElementById('doLogin').onclick = async () => {
  const login = document.getElementById('login').value.trim();
  const password = document.getElementById('password').value.trim();
  const res = await fetch('/api/login.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({login,password})});
  const data = await res.json();
  if (data.ok) {
    location.href = '/admin.php';
  } else {
    document.getElementById('loginError').textContent = data.error || 'Ошибка входа';
  }
};
