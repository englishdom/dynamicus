<?php

namespace Dynamicus\Image\Search;

use Common\Entity\File;

/**
 * Class SearchAdapterInterface
 * @package Dynamicus\Image\Search\Adapter
 */
interface SearchAdapterInterface
{

    /**
     * @param $searchText
     * @return \SplObjectStorage|File[]
     */
    public function search($searchText): \SplObjectStorage;
}
