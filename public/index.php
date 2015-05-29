<?php

/* Загрузить модули, установленные composer'ом */
require('../vendor/autoload.php');

/* Установить папку для вьюшек */
Flight::set('flight.views.path', '../views');
/* Путь до файла с БД */
Flight::set('pathToDb', '../zooshop.db');
/* Название таблицы с данными склада */
Flight::set('storeTable', 'store');

/* SQL для создания таблицы склада */
Flight::set(
	'createStore',
	"CREATE TABLE IF NOT EXISTS `". Flight::get('storeTable') ."` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
		`name`	TEXT NOT NULL UNIQUE,
		`category`	TEXT NOT NULL,
		`rodent`	INTEGER,
		`amphibian`	INTEGER,
		`reptile`	INTEGER,
		`fish`	INTEGER,
		`cat`	INTEGER,
		`dog`	INTEGER);"
);

/* Руссификация заголовков таблицы с данными склада */
Flight::set('russianTableHeaders', [
    'Номер', 'Наименование', 'Категория', 'Грызун',
    'Земноводное', 'Рептилия', 'Рыба', 'Кошка', 'Собака',
]);

/* Сообщения */
Flight::set('successInsertMsg', 'Данные добавлены');
Flight::set('failInsertMsg', 'Не удалось добавить данные в БД');
Flight::set('successSelectMsg', 'Запрос выполнен');
Flight::set('failSelectMsg', 'Не удалось выполнить запрос');
Flight::set('successDeleteMsg', 'Продано');
Flight::set('failSelectMsg', 'Удаление данных не выполнено');
Flight::set(
    'notFoundMsg',
    '<div class="not-found"><p>Неккоректный запрос. Воспользуйтесь меню для навигации.</p></div>'
);

/* Ключ безопасности */
Flight::set('secretKey', 'Sdgfjio2549i-0dsfgk2-03t23r');

/* Зарегистрировать класс для работы с БД */
Flight::register('db', 'PDO', ['sqlite:'.Flight::get('pathToDb')], function($db) {
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});

/**
 * Возвращает соединение с базой или строку с ошибкой
 * @return object|string
 */
function getDb()
{
	/* Открыть или создать файл с БД */
	try {
		$db = Flight::db();
	} catch (Exception $e) {
		return 'Не удалось подключиться к БД: '. $e->getMessage();
	}

	/* Создать таблицу, если её нет */
	if (!isset($e)) {
		try {
			$db->exec(Flight::get('createStore'));
		} catch (Exception $e) {
			return 'Не удалось создать таблицу в БД: ' . $e->getMessage();
		}
	}

	return $db;
}

/**
 * Создает sql-выражение для запроса данных из БД
 * @param array $data
 * @return string
 */
function makeSelectSql($data)
{
	$table = Flight::get('storeTable');
	$sql = "SELECT * FROM $table WHERE 1=1 ";

	if (isset($data['category']) && !empty($data['category'])) {
		$andIn = 'AND category IN (' . "'" . implode("','", $data['category']) . "'" . ') ';
		$sql .= $andIn;
	}

	if (isset($data['subcategory']) && !empty($data['subcategory'])) {
		$subcategory = [];
		foreach ($data['subcategory'] as $key => $value) {
			$subcategory[] = "$key = '{$value}'";
		}
		$sql .= 'AND ('. implode(" OR ", $subcategory) . ')';
	}

	if (isset($data['name']) && trim($data['name'] != '')) {
		$upperName = mb_strtoupper($data['name']);
		$sql .= "AND LOWER(name) LIKE '%{$upperName}%' ";
	}

	return $sql;
}

/**
 * Создает sql-выржаение для вставки данных в БД
 * @param array $data
 * @return string
 */
function makeInsertSql($data)
{
	$table = Flight::get('storeTable');

	if (isset($data['category']) && !empty($data['category'])) {
		$dataArray['category'] = $data['category'][0];
	}

	if (isset($data['subcategory']) && !empty($data['subcategory'])) {
		foreach ($data['subcategory'] as $key => $value) {
			$dataArray[$key] = $value;
		}
	}

	if (isset($data['name']) && trim($data['name'] != '')) {
		$dataArray['name'] = mb_strtoupper(trim($data['name']));
	}

	return sprintf(
		'INSERT INTO '.$table.' (%s) VALUES ("%s")',
		implode(',', array_keys($dataArray)),
		implode('","', array_values($dataArray))
	);
}

/**
 * Создает sql-выражение для удаления строки из БД
 * @param int $id
 * @return string
 */
function makeDeleteSql($id) {
    $table = Flight::get('storeTable');
    return "DELETE FROM $table WHERE id = $id";
}

/**
 * Собирает массив с данными, полученными от пользователя
 * @param array $dataObject
 * @return array
 */
function parseData($dataObject)
{
	$data = [];

	foreach ($dataObject as $key => $value) {

		if (is_array($value)) {
			$cleanArray = [];

			foreach ($value as $name => $record) {
				$cleanArray[$name] = htmlspecialchars($record);
			}

			$data[$key] = $cleanArray;
		} else {
			$data[$key] = htmlspecialchars($value);
		}

	}

	return $data;
}

/**
 * Выполняет SQL
 * @param string $sql
 * @return array
 */
function execSql($sql, $type)
{
	$result = []; $success = $fail = '';

	if ($type == 'insert') {
		$success = Flight::get('successInsertMsg');
		$fail = Flight::get('failInsertMsg');
	} elseif ($type == 'select') {
		$success = Flight::get('successSelectMsg');
		$fail = Flight::get('failSelectMsg');
	} elseif ($type == 'delete') {
        $success = Flight::get('successDeleteMsg');
        $fail = Flight::get('failSelectMsg');
    }

	$db = getDb();

	if (is_object($db)) {
		try {

			if ($type == 'insert' || $type == 'delete') {
				$db->exec($sql);
			} elseif ($type == 'select') {
				$rows = $db->query($sql);
			}

		} catch (Exception $e) {
			$result['status'] = 'danger';
			$result['description'] = "$fail: " . $e->getMessage();
		}

		if (!isset($e)) {

			if ($type == 'insert' || $type == 'delete') {
				$result['description'] = $success;
			} elseif ($type == 'select') {
				$result['description'] = $rows->fetchAll();
			}

			$result['status'] = 'success';
		}

		/* Закрыть соединение с БД */
		$db = null;

	} else {
		$result['status'] = 'danger';
		$result['description'] = $db;
	}

	return $result;
}

/**
 * Отправляет данные, возвращает ответ
 * @param string $url
 * @param array $data
 * @return mixed
 */
function postData($url, $data)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, [
		'key' => Flight::get('secretKey'),
		'data' => json_encode($data),
	]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

/* Главная страница */
Flight::route('GET /', function() {
	Flight::render('main.php', [], 'content');
	Flight::render('layout.php', [
		'title' => 'Зоомагазин - Главная',
		'header' => 'Добро пожаловать в Зоомагазин',
	]);
});

/* "О проекте" */
Flight::route('GET /about', function() {
	Flight::render('about.php', [], 'content');
	Flight::render('layout.php', [
		'title' => 'Зоомагазин - О проекте',
		'header' => 'О проекте',
	]);
});

/* "Контакты" */
Flight::route('GET /contacts', function() {
	Flight::render('contacts.php', [], 'content');
	Flight::render('layout.php', [
		'title' => 'Зоомагазин - Контакты',
		'header' => 'Контакты',
	]);
});

/* "Cклад" */
Flight::route('GET /store', function() {
	Flight::render('store.php', [], 'content');
	Flight::render('layout.php', [
		'title' => 'Зоомагазин - Склад',
		'header' => 'Добро пожаловать на склад'
	]);
});

/* Добавления данных в БД */
Flight::route('POST /add', function() {
	/* Данные от пользователя */
	$data = parseData(Flight::request()->data);
	/* Создать sql и вставить данные в БД */
	$sql = makeInsertSql($data);
	$result = execSql($sql, 'insert');
	/* Вернуть статус запроса и описание статуса */
	Flight::json([
		'status' => $result['status'],
		'description' => $result['description'],
	]);
});

/* Выборка данных из БД */
Flight::route('POST /select', function() {
	$status = $description = '';
	$userInput = Flight::request()->data;
	$data = parseData($userInput);
	$sql = makeSelectSql($data);
	$result = execSql($sql, 'select');
	$url = "http://{$_SERVER['HTTP_HOST']}/make-table";
	$status = $result['status'];

	if ($result['status'] == 'success') {
		$description = postData($url, $result['description']);
	} else {
		//$description = $result['description'];
	}

	Flight::json(['status' => $status, 'description' => $description]);
});

/* Запрос таблицы с данными */
Flight::route('POST /make-table', function() {
	$userData = Flight::request()->data;
	$key = Flight::get('secretKey');
	if (isset($userData['key']) && $userData['key'] == $key && isset($userData['data'])) {
		Flight::render('table.php', [
            'data' => json_decode($userData['data'], true),
            'tableHeaders' => Flight::get('russianTableHeaders'),
        ]);
	} else {
		echo Flight::get('notFoundMsg');
	}
});

/* Продажа товара со склада */
Flight::route('POST /sell', function() {
    $status = $description = '';
    $userData = Flight::request()->data;
    if (isset($userData['id']) && is_numeric($userData['id'])) {
        $sql = makeDeleteSql($userData['id']);
        $result = execSql($sql, 'delete');
        $status = $result['status'];
        $description = $result['description'];
    } else {
        $status = 'danger';
        $description = 'Некорректные данные для продажи';
    }
    Flight::json(['status' => $status, 'description' => $description]);
});

/* Все остальные запросы */
Flight::map('notFound', function(){
	Flight::render('layout.php', [
		'title' => 'Зоомагазин - страница не найдена',
		'header' => 'Добро пожаловать в Зоомагазин',
		'content' => Flight::get('notFoundMsg'),
	]);
});

/* Запуск приложения */
Flight::start();
