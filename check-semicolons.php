<?php

// check for arg in, if none, use current working directory

// extensions to check
$extensions = array('js');

$dir = null;
$maxDepth = null;

foreach($argv as $i => $arg) {
	// skip first arg
	if ($i == 0) {
		continue;
	}

	$matches = array();
	if ($maxDepth === null && preg_match('/^--depth=(\d+)$/', $arg, $matches)) {
		$maxDepth = floor($matches[1]);
		if ($maxDepth <= 0) {
			echo "Error: depth must be at least one.\n\n";
			showHelp();
		}
	} else if ($dir === null) {
		$dir = $arg;
	} else {
		echo "Unrecognized option: $arg.\n\n";
		showHelp();		
	}
}

if ($dir === null) {
	$dir = getcwd();
}
if ($maxDepth === null) {
	$maxDepth = 1;
}

if (is_file($dir)) {
	$ext = pathinfo($dir, PATHINFO_EXTENSION);
	if (in_array($ext, $extensions)) {
		checkFile($dir);
	} else {
		echo "File $dir does not end with js ('$ext').\n\n";
		showHelp();
	}
	die();
}

loopThroughDir($dir, 1, $maxDepth);

function loopThroughDir($dirPath, $curDepth, $maxDepth) {

	global $extensions;

	//echo "Scanning $dirPath (depth: $curDepth):\n";

	$dir = new DirectoryIterator($dirPath);
	foreach ($dir as $fileInfo) {
		if ($fileInfo->isDot()) continue;
	    if ($fileInfo->isFile() && in_array($fileInfo->getExtension(), $extensions)) {
	    	checkFile($fileInfo->getPathname());	        
	    } else if ($curDepth < $maxDepth && $fileInfo->isDir()) {
	    	loopThroughDir($fileInfo->getPathname(), $curDepth + 1, $maxDepth);
	    }
	}

}

function checkFile($filePath) {

	$contents = file_get_contents($filePath);
	if ($contents === false) {
		echo "\t - Couldn't read file: $filePath\n";
		return;
	}

	$contents = trim($contents);

	$end = $contents[strlen($contents) - 1];
	if ($end != ';') {
		echo "\t - File: $filePath does not end with a semi-colon (ends with '$end')\n";
	}

}

function showHelp() {
	global $argv;
	echo "Usage: {$argv[0]} [--depth=n] [input]\n";
	echo "Arguments:\n";
	echo "\t [input] specify the directory to scan or an individual file to run this on. ";
	echo "Default: current directory.\n";
	echo "\t --depth [int] [optional] if present scan will be recursive, iterating at most this ";
	echo "many levels deep. Default: 1 (ie non-recursive).\n";
	die();
}
	

?>