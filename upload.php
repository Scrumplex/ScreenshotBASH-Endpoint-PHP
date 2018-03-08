<?php
/*
 *   ScreenshotBASH-Endpoint-PHP
 *   Copyright (C) 2018 Sefa Eyeoglu <contact@scrumplex.net> (https://scrumplex.net)
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

// Load config
require_once __DIR__ . "/config.inc.php";

// Create folders if not exist
foreach ($config["folders"] as $folder) {
	if (!file_exists($folder))
		mkdir($folder, 0777, true);
}

// only if authenticated
if ((isset($config["password"]) || $config["password"] == "") || (isset($_POST['password']) && $_POST['password'] == $config['password'])) {
	$file = $_FILES["upload"];

	// Check for banned extensions
	foreach ($config["banned_exts"] as $ext) {
		if (endsWith($file["name"], $ext)) {
			die("ext_disallowed");
		}
	}

	// Figure out target folder
	$folder = "files";
	if (startsWith($file["type"], "video/")) {
		$folder = "videos";
	} else if (startsWith($file["type"], "image/")) {
		$folder = "images";
	}
	$target = __DIR__ . "/" . $config['folders'][$folder] . "/" . $file['name'];

	$url = $config["output_url"] . "/" . $config["folders"][$folder] . "/" . $file['name'];

	// Append number between filename and extension if file exists
	$number = 1;
	if (file_exists($target)) {
		$extensionPosition = strrpos($target, ".");
		$extensionPositionUrl = strrpos($url, ".");

		$extension = substr($target, $extensionPosition);

		$targetWithoutExtension = substr($target, 0, $extensionPosition);
		$urlWithoutExtension = substr($url, 0, $extensionPositionUrl);

		$i = 1;
		while (file_exists($target)) {
			$target = $targetWithoutExtension . "_" . $i . $extension;
			$url = $urlWithoutExtension . "_" . $i . $extension;
			$i++;
		}
	}

	// Move file to target
	if (move_uploaded_file($file['tmp_name'], $target)) {
		die($url);
	}

	die("error");
}
die("disallowed");

function startsWith($haystack, $needle)
{
	return substr($haystack, 0, strlen($needle)) == $needle;
}

function endsWith($haystack, $needle)
{
	return substr($haystack, 0 - strlen($needle)) == $needle;
}
