# IPFS-PHP Changelog

This file contains information about every addition, update and deletion in the IPFS-PHP library.  
It is recommended to read this file before updating the library to a new version.

## v1.0.0

Initial release of the project.  

#### Additions

- Added the `IPFS\Client\IPFSClient` class to interact with IPFS nodes
  - Supports the following IPFS methods:
    - `add`
    - `cat`
    - `get`
    - `ls`
    - `pin/add`
    - `pin/rm`
    - `version`
    - `ping`
  - Added the corresponding response models and transformers
- Added unit tests

## v1.1.0

This releases brings support for the `CID` IPFS identifiers.

#### Additions

- Added the `IPFS\Service\CIDEncoder` class that allows for encoding v1 CIDs in the `bafk` format.

#### Updates

- Enhanced the `IPFSClient::ping` unit test to handle the actual response format from IPFS nodes.

## v1.2.0

This release brings support for the `swarm/peers` command.

#### Additions

- Added the `IPFSClient::getPeers` method, which returns a list of all the node's connected peers.
  - Added `Peer`, `PeerIdentity`, `PeerStream` models and corresponding transformers
  - Added corresponding tests for the new transformers and methods.

## v1.3.0

This release brings support for the `resolve` command.

#### Additions

- Added the `IPFSClient::resolve` method, which returns the path to a given IPFS name.
  - Added corresponding tests for the new method.
