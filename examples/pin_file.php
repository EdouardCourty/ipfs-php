<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Ipfs\Client\IPFSClient;

// Instantiate the IPFS Client
$client = new IPFSClient('http://localhost:5001');

// Add data to IPFS
// Using 'pin' => false because pin is true by default
$file = $client->add('data', ['pin' => false]);
echo $file->name . ' added' . PHP_EOL . PHP_EOL;

// Pin the file
$pin = $client->pin($file->hash);
echo $file->name . ' pinned successfully' . PHP_EOL;

// Unpin the file
$unpin = $client->unpin($file->hash);
echo $file->name . ' unpinned successfully' . PHP_EOL;
