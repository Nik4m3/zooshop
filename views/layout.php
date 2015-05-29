<?php ?>
<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/justify-nav.css"/>
	<link rel="stylesheet" href="css/zooshop.css"/>
    <script src="js/jquery-min.js"></script>
    <script src="js/zooshop.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h1 class="text-muted">Zooshop</h1>
    <nav>
        <ul class="nav nav-justified">
            <li><a href="/">Главная</a></li>
            <li><a href="/store">Склад</a></li>
            <li><a href="/about">О проекте</a></li>
            <li><a href="/contacts">Контакты</a></li>
        </ul>
    </nav>
	<h2><?php echo $header; ?></h2>
	<br>
    <?php echo $content; ?>
    <br>
</div>
</body>
</html>
