<?php

namespace FileManager\Controllers;

class PagesController {

	public function home() {

		$root = $_SERVER['DOCUMENT_ROOT'];
		$requestUri = explode('?', $_SERVER['REQUEST_URI']);

		if (isset($requestUri[1])) {
			$leftUri = explode('&', $requestUri[1])[0];
			$rightUri = explode('&', $requestUri[1])[1];
		}

		// ?a=...&b=...
		$leftPath = isset($leftUri)
					? explode('=', $leftUri)[1] : '';
		$leftPath = urldecode($leftPath);
		$leftFullpath = $root . ($leftPath !== '' ? '/' : '') . $leftPath;

		// ?a=...&b=...
		$rightPath = isset($rightUri)
					? explode('=', $rightUri)[1] : '';
		$rightPath = urldecode($rightPath);
		$rightFullpath = $root . ($rightPath !== '' ? '/' : '') . $rightPath;

		return view('index', [
			'leftPath'			=> $leftPath,
			'leftFullpath' 		=> $leftFullpath,
			'rightPath'			=> $rightPath,
			'rightFullpath' 	=> $rightFullpath
			]
			+ $this->prepareArrays('left', $leftFullpath)
			+ $this->prepareArrays('right', $rightFullpath)
		);

	}

	public function createFolders() {

		unset($_COOKIE["foldersAreCreated"]);
		createFolders($_SERVER['DOCUMENT_ROOT']);
		redirect('');

	}

	////

	private function prepareArrays($panel, $folder) {

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

		return [$panel.'Folders' => $folders, $panel.'Files' => $files];
	}
}