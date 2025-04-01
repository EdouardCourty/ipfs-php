<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use IPFS\Client\IPFSClient;

// Instantiate the IPFS client
$client = new IPFSClient(host: 'localhost', port: 5001);

// Add a file to IPFS
$directory = $client->addDirectory(__DIR__ . '/../src');

echo '-- Directory added to IPFS --' . \PHP_EOL;
echo 'Directory hash: ' . $directory->hash . \PHP_EOL;
echo 'Directory size: ' . $directory->size . \PHP_EOL;
echo 'Number of files in this directory: ' . \count($directory->files) . \PHP_EOL;
