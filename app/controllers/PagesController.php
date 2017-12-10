<?php

namespace FileManager\Controllers;

class PagesController {

	public function home() {

		$root = $_SERVER['DOCUMENT_ROOT'];
		$requestUri = explode('?', $_SERVER['REQUEST_URI']);

		// ?a=
		$path = isset($requestUri[1])
					? explode('=', $requestUri[1])[1] : '';
		$path = urldecode($path);
		$fullpath = $root . ($path !== '' ? '/' : '') . $path;

		return view('index', [
			'path'			=> $path,
			'fullpath' 		=> $fullpath
			]
			+ $this->prepareArrays($fullpath)
		);

	}

	public function createFolders() {

		unset($_COOKIE["foldersAreCreated"]);
		createFolders($_SERVER['DOCUMENT_ROOT']);
		redirect('');

	}

	////

	private function prepareArrays($folder) {

		if ($folder === $_SERVER['DOCUMENT_ROOT']) {
			$result = array_slice(scandir($folder), 2);
		} else {
			$result = scandir($folder);
		}

		// filter for hidden folders and files
		//$result = array_filter($result, function($value) {
		//	return mb_substr($value, 0, 1) != '.';
		//});

		$folders = [];
		$files = [];
		foreach ($result as $value) {
			$fullname = $folder . ($folder === '/' ? '' : '/') . $value;
			if (is_dir($fullname)) {
				$folders[] = $value;
			} else {
				$files[] = $value;
			}
		}

		return ['folders' => $folders, 'files' => $files];
	}
}