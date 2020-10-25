<?php
/**
 * JExtCode elasticsearch base include
 *
 * @copyright  Copyright (C) 2020 Tobias Zulauf All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

use Elasticsearch\ClientBuilder;
use zero24\Helper\ExtensionParserHelper;

$extensionParserHelper = new ExtensionParserHelper;
$elasticsearchClient   = ClientBuilder::create()->setHosts(ELASTICSEARCH_HOSTS)->build();
