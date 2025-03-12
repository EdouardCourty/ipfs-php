<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Ipfs\Client\IPFSClient;

// Instantiate the IPFS client
$client = new IPFSClient(host: 'localhost', port: 5001);

// Add a file to IPFS
$fileContent = file_get_contents(__DIR__ . '/sample_file.txt');
$file = $client->add($fileContent);

echo '-- File added to IPFS --' . PHP_EOL;
echo 'File hash: ' . $file->hash . PHP_EOL;
echo 'File size: ' . $file->size . PHP_EOL;

echo PHP_EOL . '-- Retrieving the file content from IPFS --' . PHP_EOL;

// Retrieve the file content from IPFS
$ipfsFileContent = $client->cat($file->hash);
echo 'IPFS File content: ' . $ipfsFileContent . PHP_EOL;
