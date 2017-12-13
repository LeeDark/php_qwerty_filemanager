<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>File Manager</title>
	<link rel="stylesheet" type="text/css" href="/public/css/style.css">
</head>
<body>
	<h1>Simple File Manager</h1>

	<h2><a href="folders">Recreate folders</a> - if you want to create folder structure from beginning...</h2>
	<h3>PS: Folder structure should be created from first start.</h3>

	<div id="left">
	<h2>Current folder: <?= "ROOT{$currentLeftPath}"; ?></h2>
	<table>
		<tr>
			<td style="width:3%"><label><input type="checkbox" title="Invert selection"></label></td>
			<td><strong>Name</strong></td>
			<td style="width:10%"><strong>Size</strong></td>
			<td style="width:10%"></td>
		</tr>

		<?php foreach ($leftFolders as $folder => $folderOutput) : ?>
		<tr>
			<td>
				<label>
					<input type="checkbox" value="<?= $folder; ?>">
				</label>
			</td>
			<td><?= $folderOutput; ?></td>
			<td>FOLDER</td>
			<?php if ($folderOutput === $folder) : ?>
			<td></td>
			<?php else : ?>
			<td>
				<a 	title="Delete"
					href="?a=<?=$leftPath?>&b=<?=$rightPath?>&adel=<?=urlencode($folder)?>"
					onclick="return confirm('Delete folder?');">

					<img src="\public\images\remove.png"/>
				</a>
			</td>
			<?php endif; ?>
		</tr>
		<?php endforeach; ?>

		<?php foreach ($leftFiles as $file) : ?>
		<tr>
			<td>
				<label>
					<input type="checkbox" value="<?= $folder; ?>">
				</label>
			</td>
			<td><?= $file; ?></td>
			<td><?= human_filesize(filesize($_SERVER['DOCUMENT_ROOT'] . $currentLeftPath . "/" . $file)) ?></td>
			<td>
				<a 	title="Delete"
					href="?a=<?=$leftPath?>&b=<?=$rightPath?>&adel=<?=urlencode($file)?>"
					onclick="return confirm('Delete file?');">

					<img src="\public\images\remove.png"/>
				</a>
			</td>
		</tr>
		<?php endforeach; ?>

	</table>
	</div>

	<div id="right">
	<h2>Current folder: <?= "ROOT{$currentRightPath}"; ?></h2>
	<table>
		<tr>
			<td style="width:3%"><label><input type="checkbox" title="Invert selection"></label></td>
			<td><strong>Name</strong></td>
			<td style="width:10%"><strong>Size</strong></td>
		</tr>

		<?php foreach ($rightFolders as $folder => $folderOutput) : ?>
		<tr>
			<td>
				<label>
					<input type="checkbox" value="<?= $folder; ?>">
				</label>
			</td>
			<td><?= $folderOutput; ?></td>
			<td>FOLDER</td>
			<?php if ($folderOutput === $folder) : ?>
			<td></td>
			<?php else : ?>
			<td>
				<a 	title="Delete"
					href="?a=<?=$leftPath?>&b=<?=$rightPath?>&bdel=<?=urlencode($folder)?>"
					onclick="return confirm('Delete folder?');">

					<img src="\public\images\remove.png"/>
				</a>
			</td>
			<?php endif; ?>
		</tr>
		<?php endforeach; ?>

		<?php foreach ($rightFiles as $file) : ?>
		<tr>
			<td>
				<label>
					<input type="checkbox" value="<?= $folder; ?>">
				</label>
			</td>
			<td><?= $file; ?></td>
			<td><?= human_filesize(filesize($_SERVER['DOCUMENT_ROOT'] . $currentRightPath . "/" . $file)) ?></td>
			<td>
				<a 	title="Delete"
					href="?a=<?=$leftPath?>&b=<?=$rightPath?>&bdel=<?=urlencode($file)?>"
					onclick="return confirm('Delete file?');">

					<img src="\public\images\remove.png"/>
				</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	</div>

</body>
</html>