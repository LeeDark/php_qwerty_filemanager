<?php

function view($name, $data = []) {
	extract($data);
	return require "app/views/{$name}.view.php";
}

function redirect($path) {
	header("Location: /{$path}");
}

function createFolders($root) {
	$firstLevel = 13;
	$folders = [
		"Загрузки",
		"Новая папка",
		"Матлаб1",
		"3",
		"4",
		"Матлаб",
		"Графики",
		"Работа",
		"Юпитер",
		"Июнь",
		"А8густ",
		"Вапреш",
		"Библиотека",
		"1",
		"Книга",
		"42",
		"Носки эдвардианской эпохи",
		"Э6скизы",
		"Очки и тапочки",
		"Чеки",
		"Слоники",
		"Зефир4",
		"Сапфиры",
		"Конги",
		"Науч. работа",
		"Трансформатор",
		"Селекторы",
		"КП2П",
		"Ракетка",
		"Санит45696",
		"Отцы и дети и дети их детей",
		"Скрипты",
		"Ку77бик",
		"Октаэдр"
	];

	$firstLevelFolders = [];
	for ($i = 0; $i < $firstLevel; $i++) {
		$nextIndex = rand(0, count($folders) - 1);
		makedir($root . '/' . $folders[$nextIndex]);
		$firstLevelFolders[] = $folders[$nextIndex];
		array_splice($folders, $nextIndex, 1);
	}

	$otherFoldersCount = count($folders);
	$firstLevelIndex = 0;
	for ($i = 0; $i < $otherFoldersCount; $i++) {
		if (count($folders) === 0) {
			echo "Yahoo!<br>";
			break;
		}

		$secondIndex = rand(0, count($folders) - 1);
		$fullpath = $root . '/' . $firstLevelFolders[$firstLevelIndex] . '/' . $folders[$secondIndex];
		makedir($fullpath);
		array_splice($folders, $secondIndex, 1);

		$isThird = (boolean) rand(0, 1);
		if ($isThird) {
			if (count($folders) === 0) {
				echo "Yahoo! Yahoo! Yahoo!<br>";
				break;
			}

			$thirdIndex = rand(0, count($folders) - 1);
			makedir($fullpath . '/' . $folders[$thirdIndex]);
			array_splice($folders, $thirdIndex, 1);
		}

		$firstLevelIndex = ($firstLevelIndex >= (count($firstLevelFolders)-1)) 
			? 0 : $firstLevelIndex + 1;
	}

	setcookie("foldersAreCreated", "true", time()+30*24*60*60);
}

function makedir($fullpath) {
	if (!file_exists($fullpath)) {
		mkdir($fullpath, 0777, true);
	}
} 