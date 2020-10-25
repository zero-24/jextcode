<?php
/**
 * JExtCode unpackExtensions Command
 *
 * @copyright  Copyright (C) 2020 Tobias Zulauf All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

if (PHP_SAPI != 'cli')
{
	echo 'This script needs to be called via CLI!' . PHP_EOL;
	exit;
}

// Set error reporting for development
error_reporting(-1);

// Load the contstants
require dirname(__DIR__) . '/includes/constants.php';

// Ensure we've initialized Composer
if (!file_exists(ROOT_PATH . '/vendor/autoload.php'))
{
	exit(1);
}

// Load the autoloader
require ROOT_PATH . '/vendor/autoload.php';

use Joomla\Archive\Archive;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\File;

// This is required that the archive / filesystem class works.
define('JPATH_ROOT', ROOT_PATH);

$archive = new Archive(['tmp_path' => IMPORTER_TMP_DIRECTORY]);

// Cleanup the target directory.
Folder::delete(IMPORTER_EXTENSION_DIRECTORY);
Folder::create(IMPORTER_EXTENSION_DIRECTORY);

$directory = new \RecursiveDirectoryIterator(IMPORTER_DOWNLOADS_DIRECTORY);
$iterator  = new \RecursiveIteratorIterator($directory);

// Loop over all files and to find zip files
foreach ($iterator as $fileInfo)
{
	if (in_array($fileInfo->getFilename(), ['.', '..']))
	{
		continue;
	}

	$downloadfilePath = $fileInfo->getPath() . '/' . $fileInfo->getFilename();

	if ($fileInfo->getExtension() === 'zip')
	{
		$archive->extract(
			$downloadfilePath,
			IMPORTER_EXTENSION_DIRECTORY . '/' . File::stripExt($fileInfo->getFilename())
		);

		File::delete($downloadfilePath);
	}
}



