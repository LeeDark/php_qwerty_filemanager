<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>File Manager</title>
	<link rel="stylesheet" type="text/css" href="/public/css/style.css">
</head>
<body>
	<h1>Simple File Manager</h1>

	<h2><a href="folders">Recreate folders</a></h2>

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
	?>

	<div id="left">
	<h2>Current folder: 
		<?php
			$current = $leftPath === '' ? "ROOT" : "ROOT/$leftPath";
			echo $current;
		?>
	</h2>
	<table>
		<tr>
			<td style="width:3%"><label><input type="checkbox" title="Invert selection"></label></td>
			<td><strong>Name</strong></td>
			<td style="width:10%"><strong>Size</strong></td>
		</tr>
		<?php

			foreach ($leftFolders as $folder) {
				if ($folder === ".") {
					$href = urlencode(
						getSubPath(getSubPath($leftPath))
					);
				} elseif ($folder === "..") {
					$href = urlencode(
						getSubPath($leftPath)
					);
				} else {
					$href = urlencode(	
						$leftPath . ($leftPath === '' ? '' : '/') . $folder
					);
				}

				echo "<tr>";
				echo "<td><label><input type=\"checkbox\" name=\"file[]\" value=\"$folder\"></label></td>";

				if (preg_match('~[0-9]~', $folder) === 1) {
					echo "<td>{$folder}</td>";
				} else {
					echo "<td><a href=\"?a={$href}&b={$rightPath}\">{$folder}</a></td>";
				}
				
				echo "<td>FOLDER</td>";
				echo "</tr>";
			}

			foreach ($leftFiles as $file) {
				echo "<tr>";
				echo "<td><label><input type=\"checkbox\" name=\"file[]\" value=\"$file\"></label></td>";
				echo "<td>$file</a></td>";
				echo "<td>".human_filesize(filesize($leftFullpath . "/" . $file))."</td>";
				echo "</tr>";
			}

		?>
	</table>
	</div>

	<div id="right">
	<h2>Current folder: 
		<?php
			$current = $rightPath === '' ? "ROOT" : "ROOT/$rightPath";
			echo $current;
		?>
	</h2>
	<table>
		<tr>
			<td style="width:3%"><label><input type="checkbox" title="Invert selection"></label></td>
			<td><strong>Name</strong></td>
			<td style="width:10%"><strong>Size</strong></td>
		</tr>
		<?php

			foreach ($rightFolders as $folder) {
				if ($folder === ".") {
					$href = urlencode(
						getSubPath(getSubPath($rightPath))
					);
				} elseif ($folder === "..") {
					$href = urlencode(
						getSubPath($rightPath)
					);
				} else {
					$href = urlencode(	
						$rightPath . ($rightPath === '' ? '' : '/') . $folder
					);
				}

				echo "<tr>";
				echo "<td><label><input type=\"checkbox\" name=\"file[]\" value=\"$folder\"></label></td>";

				if (preg_match('~[0-9]~', $folder) === 1) {
					echo "<td>{$folder}</td>";
				} else {
					echo "<td><a href=\"?a={$leftPath}&b={$href}\">{$folder}</a></td>";
				}
				
				echo "<td>FOLDER</td>";
				echo "</tr>";
			}

			foreach ($rightFiles as $file) {
				echo "<tr>";
				echo "<td><label><input type=\"checkbox\" name=\"file[]\" value=\"$file\"></label></td>";
				echo "<td>$file</a></td>";
				echo "<td>".human_filesize(filesize($rightFullpath . "/" . $file))."</td>";
				echo "</tr>";
			}

		?>
	</table>
	</div>

</body>
</html>