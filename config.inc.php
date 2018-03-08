<?php
$config = array(
	'password' => "your_password", // A password to prevent other uploads.
	'folders' => array(
		'images' => "img", // folder for mime image/*
		'videos' => "video", // folder for mime video/*
		'files' => "file" // folder for any other mime
	),
	'banned_exts' => array(
		".php",
		".bat",
		".exe"
	),
	'output_url' => "https://example.com" // The url of the parent folder of this script
);