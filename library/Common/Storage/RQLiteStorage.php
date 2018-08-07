<?php

namespace Common\Storage;

use Common\Entity\DataObject;

class RQLiteStorage implements StorageInterface
{
    const TYPE_GET = 'GET';
    const TYPE_POST = 'POST';

    private $headers = [
        'Content-Type: application/json'
    ];

    private $host;

    /**
     * RQLiteStorage constructor.
     * @param $host
     */
    public function __construct($host)
    {
        $this->host = $host;
    }

    /**
     * @param DataObject $do
     * @return array
     * @throws RQLiteStorageException
     */
    public function readHashes(DataObject $do): array
    {
        $hashes = [];
        $select = 'SELECT hashes.hash FROM entities INNER JOIN hashes ON hashes.id = entities.hash_id WHERE entities.entity_name="'.$do->getEntityName().'" AND entities.entity_id='.$do->getEntityId().'';
        $json = $this->generateCurl(self::TYPE_GET, $select);

        foreach ($json['results'][0]['values'] as $values) {
            $hashes[] = $values[0];
        }
        return $hashes;
    }

    /**
     * Write hash to RQLite
     * @param $hash
     * @param $value
     * @return bool
     * @throws RQLiteStorageException
     */
    public function writeHash($hash, $value): bool
    {
        $select = 'SELECT id FROM hashes WHERE hash="'.$hash.'"';
        $json = $this->generateCurl(self::TYPE_GET, $select);

        if (!isset($json['results'][0]['values'][0][0])) {
            $insert = '["INSERT INTO hashes(hash, message) VALUES(\"'.$hash.'\",\"'.base64_encode($value).'\")"]';
            $json = $this->generateCurl(self::TYPE_POST, $insert);
            $lastInsertId = $json['results'][0]['last_insert_id'];
        } else {
            $lastInsertId = $json['results'][0]['values'][0][0];
        }

        return $lastInsertId;
    }

    /**
     * @param DataObject $do
     * @param            $hash
     * @return bool
     * @throws RQLiteStorageException
     */
    public function linkHash(DataObject $do, $hash): bool
    {
        $select = 'SELECT id FROM hashes WHERE hash="'.$hash.'"';
        $json = $this->generateCurl(self::TYPE_GET, $select);

        if (isset($json['results'][0]['values'][0][0])) {
            /* Save hash id */
            $hashId = $json['results'][0]['values'][0][0];

            try {
                /* Start transaction */
                $query = '["BEGIN"]';
                $this->generateCurl(self::TYPE_POST, $query);

                /* Remove all hashes for entity */
                $delete = '["DELETE FROM entities WHERE entity_name=\"' . $do->getEntityName() . '\" AND entity_id=' . $do->getEntityId() . '"]';
                $this->generateCurl(self::TYPE_POST, $delete);

                /* Insert new hash for entity */
                $insert = '["INSERT INTO entities(hash_id, entity_name, entity_id) VALUES(' . $hashId . ',\"' . $do->getEntityName() . '\",\"' . $do->getEntityId() . '\")"]';
                $json = $this->generateCurl(self::TYPE_POST, $insert);

                /* Commit transaction */
                $query = '["COMMIT"]';
                $this->generateCurl(self::TYPE_POST, $query);
            } catch (RQLiteStorageException $err) {
                /* Rollback transaction */
                $query = '["ROLLBACK"]';
                $this->generateCurl(self::TYPE_POST, $query);
                throw new RQLiteStorageException($err->getMessage());
            }
        } else {
            throw new RQLiteStorageException('The hash does not exist in a storage!');
        }

        return $json['results'][0]['last_insert_id'];
    }

    /**
     * @param string $type
     * @param string $query
     * @return array
     * @throws RQLiteStorageException
     */
    protected function generateCurl(string $type, string $query): array
    {
        $curl = curl_init();
        $url = 'http://' . $this->host . '/db/query?pretty&q='.urlencode($query);
        if ($type == self::TYPE_POST) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
            $url ='http://' . $this->host . '/db/execute?pretty';
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        $json = json_decode($response, true);
        if (isset($json['results'][0]['error'])) {
            throw new RQLiteStorageException($json['results'][0]['error']);
        }
        if (!empty($error)) {
            throw new RQLiteStorageException($error);
        }
        return $json;
    }
}