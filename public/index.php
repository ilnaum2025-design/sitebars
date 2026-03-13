<?php $config = require __DIR__.'/api/config.php'; ?>
<!doctype html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Главная</title><link rel="stylesheet" href="/assets/css/style.css"></head><body>
<header><nav><a href="/index.php">Главная</a><a href="/about.php">О нас</a><a href="/price.php">Прайс лист</a><a href="/admin-login.php">Админ</a></nav></header>
<div class="container">
  <section class="hero"><h1>Барс — мужские стрижки и уход</h1><p>Профессиональные мастера и уютная атмосфера.</p><button class="btn" id="openBooking">Записаться на прием</button> <a class="btn secondary" href="/about.php">Узнать больше</a></section>
  <h2 class="section-title">Новости VK</h2><div class="cards" id="vkPosts"></div><p><a class="btn" id="subscribeBtn" target="_blank">Подписаться</a></p>
  <h2 class="section-title">Как нас найти</h2><div class="card"><p id="addressText"></p><div id="map" style="width:100%;height:340px;border-radius:10px;overflow:hidden"></div><p><a class="btn" id="routeBtn" target="_blank">Проложить маршрут</a></p></div>
  <h2 class="section-title">Отзывы</h2><p id="reviewCount">0 отзывов на Яндекс Картах</p><div class="cards" id="reviews"></div>
</div>
<footer><nav><a href="/index.php">Главная</a><a href="/about.php">О нас</a><a href="/price.php">Прайс лист</a></nav></footer>
<div class="overlay" id="bookingOverlay"><div class="modal"><h3>Записаться на прием</h3><p>Телефон: <span id="phoneValue"><?=htmlspecialchars($config['phone'])?></span> <button class="btn secondary" id="copyPhone">copy_svgrepo.com</button></p><button class="btn secondary" id="closeBooking">Закрыть</button></div></div>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="module" src="/assets/js/main.js"></script>
</body></html>
