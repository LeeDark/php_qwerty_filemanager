<?php

require 'vendor/autoload.php';
require 'core/bootstrap.php';

use FileManager\Core\{Router, Request};

if (!isset($_COOKIE["foldersAreCreated"])) {
	createFolders($_SERVER['DOCUMENT_ROOT']);
}

Router::load('app/routes.php')
	->direct(Request::uri(), Request::method());
