<?php

namespace FileManager\Controllers;

class PagesController {

	public function home() {

		$root = $_SERVER['DOCUMENT_ROOT'];

		$leftPath = isset($_GET['a']) ? urldecode($_GET['a']) : '';
		$leftFullpath = $root . ($leftPath !== '' ? '/' : '') . $leftPath;

		$rightPath = isset($_GET['b']) ? urldecode($_GET['b']) : '';
		$rightFullpath = $root . ($rightPath !== '' ? '/' : '') . $rightPath;

		if (isset($_GET['adel'])) {
			$this->deleteResource($leftFullpath . '/' . $_GET['adel']);

			// TODO:
			// b= DIR1/XXX/.. adel=XXX
			// b= XXX/... adel=XXX
			$bpath = $rightPath;
			$pos = strpos($bpath, $_GET['adel']);
			if ($pos !== false) {
				if ($pos === 0) { // b= XXX/...
					$bpath = '';
				} else {
					$bpath = substr($bpath, 0, $pos - 1);
				}
			}

			redirect('?a=' . urlencode($leftPath) . '&b=' . urlencode($bpath));
		}

		if (isset($_GET['bdel'])) {
			$this->deleteResource($rightFullpath . '/' . $_GET['bdel']);

			// TODO:
			// a= DIR1/XXX/.. bdel=XXX
			// a= XXX/... bdel=XXX
			$apath = $leftPath;
			$pos = strpos($apath, $_GET['bdel']);
			if ($pos !== false) {
				if ($pos === 0) { // a= XXX/...
					$apath = '';
				} else {
					$apath = substr($apath, 0, $pos - 1);
				}
			}

			redirect('?a=' . urlencode($apath) . '&b=' . urlencode($rightPath));
		}

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