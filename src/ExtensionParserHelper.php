<?php
/**
 * Helper
 *
 * @copyright  Copyright (C) 2020 J!German (www.jgerman.de) All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace zero24\Helper;

/**
 * Joomla Extension Parser class
 *
 * @since  1.0
 */
class ExtensionParserHelper
{
	/**
	 * Parse the imports folder for extensions.
	 *
	 * @param   string  $extensionXmlfilePath  Full file path of the xml info file.
	 *
	 * @return  array   The data of the XML file.
	 *
	 * @since  1.0.0
	 */
	public function parseImportsFolderExtensions($importsDirectory): array
	{
		$documents = [];
		$directory = new \RecursiveDirectoryIterator($importsDirectory);
		$iterator  = new \RecursiveIteratorIterator($directory);

		$singleDocument = [
			'extensionName' => '',
			'extensionType' => '',
			'extensionElement' => '',
			'extensionFolder' => '',
			'extensionVersion' => '',
			'extensionClient' => '',
			'extensionCreationDate' => '',
			'extensionAuthor' => '',
			'extensionAuthorUrl' => '',
			'extensionLicense' => '',
			'extensionCopyright' => '',
			'fileName' => '',
			'filePath' => '',
			'fileExtension' => '',
			'filedata' => '',
		];

		// Loop over all files and to find the XML Info file
		foreach ($iterator as $fileInfo)
		{
			if (in_array($fileInfo->getFilename(), ['.', '..']))
			{
				continue;
			}

			$extensionfilePath = $fileInfo->getPath() . '/' . $fileInfo->getFilename();

			if ($fileInfo->getExtension() === 'xml' && $this->validateXMLInfoFile($extensionfilePath))
			{
				$singleDocument = $this->parseXMLInfoFile($extensionfilePath);
				break;
			}
		}

		// Loop over all files and collect all other data we have
		foreach ($iterator as $fileInfo)
		{
			if (in_array($fileInfo->getFilename(), ['.', '..']))
			{
				continue;
			}

			$extensionfilePath = $fileInfo->getPath() . '/' . $fileInfo->getFilename();

			$singleDocument['fileName'] = $fileInfo->getFilename();
			$singleDocument['filePath'] = str_replace('\\', '/', \str_replace($importsDirectory, '', $fileInfo->getPath()));
			$singleDocument['fileExtension'] = $fileInfo->getExtension();
			$singleDocument['filedata'] = file_get_contents($extensionfilePath);

			// Append the single document to the documents array
			$documents[] = $singleDocument;
		}

		return $documents;

	}

	/**
	 * Check whether this is a valid XML Info file
	 *
	 * @param   string  $extensionXmlfilePath  Full file path of the xml info file.
	 *
	 * @return  bool
	 *
	 * @since  1.0.0
	 */
	private function validateXMLInfoFile($extensionXmlfilePath): bool
	{
		if (!is_readable($extensionXmlfilePath))
		{
			return false;
		}

		$extensionXml = simplexml_load_string(file_get_contents($extensionXmlfilePath));

		// Check whether there is a name and version attribute
		if (isset($extensionXml->name) && isset($extensionXml->version))
		{
			return true;
		}

		return false;
	}

	/**
	 * Parse an XML info file.
	 *
	 * @param   string  $extensionXmlfilePath  Full file path of the xml info file.
	 *
	 * @return  array   The data of the XML file.
	 *
	 * @since  1.0.0
	 */
	private function parseXMLInfoFile($extensionXmlfilePath): array
	{
		$extensionXmlData = [
			'extensionName' => 'unknown',
			'extensionType' => 'unknown',
			'extensionElement' => 'unknown',
			'extensionFolder' => '',
			'extensionVersion' => 'unknown',
			'extensionClient' => 'site',
			'extensionCreationDate' => 'unknown',
			'extensionAuthor' => 'unknown',
			'extensionAuthorUrl' => 'unknown',
			'extensionLicense' => 'unknown',
			'extensionCopyright' => 'unknown',
		];

		$fileContent = file_get_contents($extensionXmlfilePath);
		$extensionXml = simplexml_load_string($fileContent);

		foreach ($extensionXml->attributes() as $key => $value)
		{
			if ($key === 'type')
			{
				$extensionXmlData['extensionType'] = $value->__toString();
			}

			if ($key === 'group')
			{
				$extensionXmlData['extensionFolder'] = $value->__toString();
			}

			if ($key === 'client')
			{
				$extensionXmlData['extensionClient'] = $value->__toString();
			}
		}

		$extensionXmlData['extensionName'] = (isset($extensionXml->name)) ? $extensionXml->name->__toString() : 'unknown';
		$extensionXmlData['extensionVersion'] = (isset($extensionXml->version)) ? $extensionXml->version->__toString() : 'unknown';
		$extensionXmlData['extensionCreationDate'] = (isset($extensionXml->creationDate)) ? $extensionXml->creationDate->__toString() : 'unknown';
		$extensionXmlData['extensionAuthor'] = (isset($extensionXml->author)) ? $extensionXml->author->__toString() : 'unknown';
		$extensionXmlData['extensionAuthorUrl'] = (isset($extensionXml->authorUrl)) ? $extensionXml->authorUrl->__toString() : 'unknown';
		$extensionXmlData['extensionLicense'] = (isset($extensionXml->license)) ? $extensionXml->license->__toString() : 'unknown';
		$extensionXmlData['extensionCopyright'] = (isset($extensionXml->copyright)) ? $extensionXml->copyright->__toString() : 'unknown';

		// Apply per extension type special detections
		switch ($extensionXmlData['extensionType'])
		{
			case 'plugin':
				if (preg_match('/<filename plugin="(.*)">/', $fileContent, $matches))
				{
					$extensionXmlData['extensionElement'] = $matches[1];
				}
				break;
			case 'module':
				if (preg_match('/<filename module="(.*)">/', $fileContent, $matches))
				{
					$extensionXmlData['extensionElement'] = $matches[1];
				}
				break;
			case 'file':
				$extensionXmlData['extensionElement'] = 'none';
			case 'library':
				$extensionXmlData['extensionElement'] = 'none';
			case 'package':
				$extensionXmlData['extensionElement'] = 'none';
		}

		return $extensionXmlData;
	}
}
