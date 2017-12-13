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

	<h2>Current folder: <?= "ROOT{$currentPath}"; ?></h2>
	<table>
		<tr>
			<td><strong>Name</strong></td>
			<td style="width:10%"><strong>Size</strong></td>
		</tr>

		<?php foreach ($folders as $folder) : ?>
			<tr>
				<td><?= $folder; ?></td>
				<td>FOLDER</td>
			</tr>
		<?php endforeach; ?>

		<?php foreach ($files as $file) : ?>
			<tr>
				<td><?= $file; ?></td>
				<td><?= human_filesize(filesize($_SERVER['DOCUMENT_ROOT'] . $currentPath . "/" . $file)) ?></td>
			</tr>
		<?php endforeach; ?>
	</table>

</body>
</html>