# IPFS-PHP

[![PHP CI](https://github.com/EdouardCourty/ipfs-php/actions/workflows/php_ci.yml/badge.svg)](https://github.com/EdouardCourty/ipfs-php/actions/workflows/php_ci.yml)

IPFS-PHP provides a simple way to interact with an IPFS Node using PHP.  
The changelog for this project can be found [here](./CHANGELOG.md).

## Installation

```shell
composer require ecourty/ipfs-php
```

## Usage

The following example shows how to add a file to IPFS and retrieve its content later.  

```php
<?php

use IPFS\Client\IPFSClient;

// Three different ways to instantiate the client
$client = new IPFSClient(url: 'http://localhost:5001');

// If nothing is passed, the default values are used (localhost and 5001)
// $client = new IPFSClient();
// $client = new IPFSClient(host: 'localhost', port: 5001);

// Add a file
$file = $client->addFile('file.txt');

echo 'File uploaded: ' . $file->hash;
// File uploaded: QmWGeRAEgtsHW3ec7U4qW2CyVy7eA2mFRVbk1nb24jFyks
// ...

// Get the file content
$fileContent = $client->cat($file->hash);
// ...

// Downloads the complete file
$file = $client->get($file->hash);
// ...

// Download the file as a tar archive (compression can be specified with the compression parameters)
$archive = $client->get($file->hash, archive: true);
file_put_contents('archive.tar', $archive);
// ...
```

More code examples can be found under the [examples](./examples) directory.

&copy; Edouard Courty 2025
