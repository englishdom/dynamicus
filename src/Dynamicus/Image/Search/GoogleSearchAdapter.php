<?php

namespace Dynamicus\Image\Search;

use Common\Container\ConfigInterface;
use Common\Entity\File;
use Common\Exception\NotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

/**
 * Class Google
 */
class GoogleSearchAdapter implements SearchAdapterInterface
{
    const GOOGLE_SEARCH_API_URL = 'https://www.googleapis.com/customsearch/v1';
    const GOOGLE_SEARCH_TYPE = 'image';
    const GOOGLE_SEARCH_FILE_TYPE = 'jpg';
    const GOOGLE_SEARCH_IMAGE_SIZE = 'xlarge';
    const GOOGLE_SEARCH_RESPONSE_FORMAT = 'json';
    const GOOGLE_SEARCH_REQUEST_METHOD = 'GET';
    const GOOGLE_SEARCH_FIELDS = 'items(link)';
    // "high": Enables highest level of SafeSearch filtering.
    const GOOGLE_SEARCH_PARAM_SAFE = 'high';
    const CURSOR_DEFAULT = 1;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * Google constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param $searchText
     * @return \SplObjectStorage|File[]
     * @throws NotFoundException
     */
    public function search($searchText): \SplObjectStorage
    {
        $resultArray = $this->getResult($searchText);
        $collection = $this->parseResult($resultArray);
        return $collection;
    }

    /**
     * @param array $resultArray
     * @return \SplObjectStorage
     * @throws NotFoundException
     */
    private function parseResult(array $resultArray): \SplObjectStorage
    {
        if (!isset($resultArray['items'])) {
            if (isset($resultArray['searchInformation']['totalResults'])) {
                throw new NotFoundException('Found images: '.$resultArray['searchInformation']['totalResults']);
            }
            throw new NotFoundException('Not found `items` in response');
        }

        $collection = new \SplObjectStorage();
        foreach ($resultArray['items'] as $item) {
            $imageFile = new File();
            $imageFile->setUrl($item['link']);
            $collection->attach($imageFile);
        }
        return $collection;
    }
    
    private function getResult($searchText): ?array
    {
        $url = $this->prepareUrl($searchText);
        $result = $this->getClient()->request('GET', $url);

        if ($result->getStatusCode() == '200') {
            $resultArray = \GuzzleHttp\json_decode($result->getBody()->getContents(), true);
            return $resultArray;
        }
        return null;
    }

    /*
     * @TODO: use CSE php library
    */
    private function prepareUrl($searchText): string
    {
        // полный список параметров https://developers.google.com/custom-search/v1/cse/list
        $arguments = [
            'key' => $this->config->get('google-api.key'),
            'cx' => $this->config->get('google-api.cx'),
            'q' => trim($searchText),
            'searchType' => self::GOOGLE_SEARCH_TYPE,
            'fileType' => self::GOOGLE_SEARCH_FILE_TYPE,
            'fields' => self::GOOGLE_SEARCH_FIELDS,
            'imgSize' => self::GOOGLE_SEARCH_IMAGE_SIZE,
            'alt' => self::GOOGLE_SEARCH_RESPONSE_FORMAT,
            'safe' => self::GOOGLE_SEARCH_PARAM_SAFE,
            'start' => self::CURSOR_DEFAULT,
        ];
        return sprintf("%s?%s", self::GOOGLE_SEARCH_API_URL, http_build_query($arguments));
    }

    private function getClient()
    {
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);
        return new Client(['handler' => $stack]);
    }
}
