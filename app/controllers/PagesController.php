<?php

namespace FileManager\Controllers;

class PagesController {

	private $leftPath;
	private $rightPath;

	public function home() {

		$root = $_SERVER['DOCUMENT_ROOT'];

		$this->leftPath = isset($_GET['a']) ? urldecode($_GET['a']) : '';
		$currentLeftPath = ($this->leftPath !== '' ? '/' : '') . $this->leftPath;
		//$leftFullpath = $root . ($this->leftPath !== '' ? '/' : '') . $this->leftPath;

		$this->rightPath = isset($_GET['b']) ? urldecode($_GET['b']) : '';
		$currentRightPath = ($this->rightPath !== '' ? '/' : '') . $this->rightPath;
		//$rightFullpath = $root . ($this->rightPath !== '' ? '/' : '') . $this->rightPath;

		if (isset($_GET['adel'])) {
			$this->deleteResource($leftFullpath . '/' . $_GET['adel']);

			// TODO:
			// b= DIR1/XXX/.. adel=XXX
			// b= XXX/... adel=XXX
			$bpath = $this->rightPath;
			$pos = strpos($bpath, $_GET['adel']);
			if ($pos !== false) {
				if ($pos === 0) { // b= XXX/...
					$bpath = '';
				} else {
					$bpath = substr($bpath, 0, $pos - 1);
				}
			}

			redirect('?a=' . urlencode($this->leftPath) . '&b=' . urlencode($bpath));
		}

		if (isset($_GET['bdel'])) {
			$this->deleteResource($rightFullpath . '/' . $_GET['bdel']);

			// TODO:
			// a= DIR1/XXX/.. bdel=XXX
			// a= XXX/... bdel=XXX
			$apath = $this->leftPath;
			$pos = strpos($apath, $_GET['bdel']);
			if ($pos !== false) {
				if ($pos === 0) { // a= XXX/...
					$apath = '';
				} else {
					$apath = substr($apath, 0, $pos - 1);
				}
			}

			redirect('?a=' . urlencode($apath) . '&b=' . urlencode($this->rightPath));
		}

		return view('index', [
			'currentLeftPath'	=> $currentLeftPath,
			'leftPath'			=> $this->leftPath,
			'currentRightPath'	=> $currentRightPath,
			'rightPath'			=> $this->rightPath
			]
			+ $this->prepareArrays('left', $_SERVER['DOCUMENT_ROOT'] . $currentLeftPath)
			+ $this->prepareArrays('right', $_SERVER['DOCUMENT_ROOT'] . $currentRightPath)
		);

	}

	public function createFolders() {

		unset($_COOKIE["foldersAreCreated"]);
		createFolders($_SERVER['DOCUMENT_ROOT']);
		redirect('');

	}

	////

	private function prepareArrays($panel, $fullPath) {

		$result = scandir($fullPath);
		if ($fullPath === $_SERVER['DOCUMENT_ROOT'])
			$result = array_slice($result, 2);

		$folders = [];
		$files = [];
		foreach ($result as $folderOrFile) {
			$fullname = $fullPath . ($fullPath === '/' ? '' : '/') . $folderOrFile;
			if (is_dir($fullname)) {
				$preparedFolder = $this->prepareFolder($panel, $folderOrFile);
				$folders[$folderOrFile] = $preparedFolder;
			} else {
				$files[] = $folderOrFile;
			}
		}

		return [$panel.'Folders' => $folders, $panel.'Files' => $files];
	}

	// prepare folder
	private function prepareFolder($panel, $folder) {
		// only if folder name doesn't consist any numbers
		$path = $panel === 'left' ? $this->leftPath : $this->rightPath;
		if (preg_match('~[0-9]~', $folder) !== 1) {
			// "." to return to the folder 2 levels higher
			if ($folder === ".") {
				$href = urlencode(
					getSubPath(getSubPath($path))
				);
			// ".." to return to the folder 1 level higher
			} elseif ($folder === "..") {
				$href = urlencode(
					getSubPath($path)
				);
			} else {
				$href = urlencode(	
					$path . ($path === '' ? '' : '/') . $folder
				);
			}
		
			if ($panel === 'left')
				return "<a href=\"?a={$href}&b={$this->rightPath}\">{$folder}</a>";
			elseif ($panel === 'right')
				return "<a href=\"?a={$this->leftPath}&b={$href}\">{$folder}</a>";
		}

		return $folder;
	}

	private function deleteResource($path) {
		if (is_link($path)) {
			return unlink($path);
		} elseif (is_dir($path)) {
			$objects = scandir($path);
			$ok = true;
			if (is_array($objects)) {
				foreach ($objects as $file) {
					if ($file != '.' && $file != '..') {
						if (!$this->deleteResource($path . '/' . $file)) {
							$ok = false;
						}
					}
				}
			}

			return ($ok) ? rmdir($path) : false;
		} elseif (is_file($path)) {
			return unlink($path);
		}

		return false;
	}
}