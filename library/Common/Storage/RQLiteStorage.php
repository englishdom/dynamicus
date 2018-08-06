<?php

namespace Common\Storage;

use Common\Entity\DataObject;

class RQLiteStorage implements StorageInterface
{

    public function readHashes(DataObject $do): array
    {
        // TODO: Implement readHashes() method.
    }

    public function writeHash($hash, $value): bool
    {
        // TODO: Implement writeHash() method.
    }

    public function linkHash(DataObject $do, $hash): bool
    {
        // TODO: Implement linkHash() method.
    }
}