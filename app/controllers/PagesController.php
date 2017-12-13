<?php

namespace FileManager\Controllers;

class PagesController {

	private $path;

	public function home() {

		$this->path = isset($_GET['a']) ? urldecode($_GET['a']) : '';
		$currentPath = ($this->path !== '' ? '/' : '') . $this->path;

		return view('index', ['currentPath' => $currentPath]
			+ $this->prepareArrays($_SERVER['DOCUMENT_ROOT'] . $currentPath)
		);

	}

	public function createFolders() {

		unset($_COOKIE["foldersAreCreated"]);
		createFolders($_SERVER['DOCUMENT_ROOT']);
		redirect('');

	}

	////

	// prepare folders and files arrays for view
	private function prepareArrays($fullPath) {

		$result = scandir($fullPath);
		if ($fullPath === $_SERVER['DOCUMENT_ROOT'])
			$result = array_slice($result, 2);

		$folders = [];
		$files = [];
		foreach ($result as $folderOrFile) {
			$fullname = $fullPath . ($fullPath !== '/' ? '/' : '') . $folderOrFile;
			if (is_dir($fullname)) {
				$this->prepareFolder($folderOrFile);
				$folders[] = $folderOrFile;
			} else {
				$files[] = $folderOrFile;
			}
		}

		return ['folders' => $folders, 'files' => $files];
	}

	// prepare folder
	private function prepareFolder(&$folder) {
		// only if folder name doesn't consist any numbers
		if (preg_match('~[0-9]~', $folder) !== 1) {
			// "." to return to the folder 2 levels higher
			if ($folder === ".") {
				$href = urlencode(
					getSubPath(getSubPath($this->path))
				);
			// ".." to return to the folder 1 level higher
			} elseif ($folder === "..") {
				$href = urlencode(
					getSubPath($this->path)
				);
			} else {
				$href = urlencode(	
					$this->path . ($this->path === '' ? '' : '/') . $folder
				);
			}
		
			$folder = "<a href=\"?a={$href}\">{$folder}</a>";
		}
	}
}