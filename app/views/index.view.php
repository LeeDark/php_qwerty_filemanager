<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>File Manager</title>
</head>
<body>
	<h1>Simple File Manager</h1>

	<h2><a href="folders">Recreate folders</a> - if you want to create folder structure from beginning...</h2>
	<h3>PS: Folder structure should be created from first start.</h3>

	<h2>Current folder: 
		<?php
			$current = $path === '' ? "ROOT" : "ROOT/$path";
			echo $current;
		?>
	</h2>
	<table>
		<tr>
			<td><strong>Name</strong></td>
			<td style="width:10%"><strong>Size</strong></td>
		</tr>
		<?php

			function human_filesize($bytes, $decimals = 2) {
				$sz = 'BKMGTP';
				$factor = floor((strlen($bytes) - 1) / 3);
				if ($factor == 0) $decimals = 0;
				return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor];
			}

			function getSubPath($path) {
				$pos = strrpos($path, '/');
				return $pos === false ? '' : substr($path, 0, $pos);
			}

			foreach ($folders as $folder) {
				if ($folder === ".") {
					$href = urlencode(
						getSubPath(getSubPath($path))
					);
				} elseif ($folder === "..") {
					$href = urlencode(
						getSubPath($path)
					);
				} else {
					$href = urlencode(	
						$path . ($path === '' ? '' : '/') . $folder
					);
				}

				echo "<tr>";

				if (preg_match('~[0-9]~', $folder) === 1) {
					echo "<td>{$folder}</td>";
				} else {
					echo "<td><a href=\"?a={$href}\">{$folder}</a></td>";
				}
				
				echo "<td>FOLDER</td>";
				echo "</tr>";
			}

			foreach ($files as $file) {
				echo "<tr>";
				echo "<td>$file</a></td>";
				echo "<td>".human_filesize(filesize($fullpath . "/" . $file))."</td>";
				echo "</tr>";
			}

		?>
	</table>

</body>
</html>