<?php

namespace Common\Diagnostics;

use ZendDiagnostics\Check\AbstractCheck;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

class CheckRQLite extends AbstractCheck implements CheckInterface
{

    public function check()
    {
        try {
            $this->generateCurl('SELECT * FROM sqlite_master');
        } catch (\Exception $err) {
            return new Failure($err->getMessage());
        }

        return new Success('working');
    }

    protected function generateCurl(string $query): ?array
    {
        /* set RQLite host */
        $host = env('RQLITE_HOST').':'.env('RQLITE_PORT');
        $curl = curl_init();
        $url = 'http://' . $host . '/db/query?q=' . urlencode($query);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);
        $json = json_decode($response, true);

        if (isset($json['results'][0]['error'])) {
            throw new \Exception($json['results'][0]['error']);
        }
        if (!empty($error)) {
            throw new \Exception($error);
        }
        if (!is_array($json)) {
            return null;
        }
        return $json;
    }
}