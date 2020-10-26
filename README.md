# JExtCode

This is an WIP application for an elastic search that holds seachable code of joomla extensions.

## Sponsoring and Donation

You want to support my work for the [development of my extensions](https://extensions.joomla.org/profile/profile/details/200189/) and my work for the [Joomla! Project](https://volunteers.joomla.org/joomlers/248-tobias-zulauf) you can give something back and sponsor me.

There are two ways to support me right now:
- This repository is part of [Github Sponsors](https://github.com/sponsors/zero-24/) by sponsoring me, you help me continue my oss work for the [Joomla! Project](https://volunteers.joomla.org/joomlers/248-tobias-zulauf), write bug fixes, improving features and maintain my extensions.
- You just want to send me an one-time donation? Great you can do this via [PayPal.me/zero24](https://www.paypal.me/zero24).

Thanks for your support!

## Setup

- Please install an [Elastic Stack](https://www.elastic.co/guide/en/elastic-stack-get-started/current/get-started-docker.html)
- `mv includes/constants.dist.php includes/constants.php`
- `nano includes/constants.php`
- `php cli/001-fetchExtensions.php` (does nothing right now; This is intended to download all extensions based on a list of public update xml URLs)
- `php cli/002-unpackExtensions.php` (Extracts the extensions downloaded to the import directory)
- `php cli/003-index.php` (Adds the extensions data to the `jextcode` index)
- `php cli/004-search.php` (Simple search example)

## Datastructure

The current datastructure for an single document:

```php
$singleDocument = [
	'extensionName'         => 'plg_system_httpheader',
	'extensionType'         => 'plugin',
	'extensionElement'      => 'httpheader',
	'extensionFolder'       => 'system',
	'extensionVersion'      => '1.0.14',
	'extensionClient'       => 'site',
	'extensionCreationDate' => '26.08.2020',
	'extensionAuthor'       => 'Tobias Zulauf',
	'extensionAuthorUrl'    => 'https://www.jah-tz.de',
	'extensionLicense'      => 'GNU/GPL Version 2 or later',
	'extensionCopyright'    => '(C) 2017 - 2020 Tobias Zulauf All rights reserved.',
	'fileName'              => 'httpheader.xml',
	'filePath'              => '/plg_system_httpheader',
	'fileExtension'         => 'xml',
	'filedata'              => '<?xml version="1.0" encoding="utf-8"?>.....',
];
```

