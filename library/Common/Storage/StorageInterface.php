<?php

namespace Common\Storage;

use Common\Entity\DataObject;

interface StorageInterface
{

    /**
     * Read all hashes from entity
     * @param DataObject $do
     * @return array
     */
    public function readHashes(DataObject $do): array;

    /**
     * Write a value to the storage if a key does not exist
     * @param $hash
     * @param $value
     * @return bool
     */
    public function writeHash($hash, $value): bool;

    /**
     * Link hash for entity.
     * Remove old hashes in entity.
     * @param DataObject $do
     * @param            $hash
     * @return bool
     */
    public function linkHash(DataObject $do, $hash): bool;
}