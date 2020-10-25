<?php
/**
 * JExtCode Search Command
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

// Load the elasticsearch base configuration
require ROOT_PATH . '/includes/elasticsearch-base.php';

$params = [
    'index' => 'jextcode',
    'body'  => [
        'query' => [
            'match' => [
                'fileExtension' => 'xml'
            ]
        ]
    ]
];

$response = $elasticsearchClient->search($params);
print_r($response);
