<?php

namespace Dynamicus\Image\Search;

use Common\Entity\ImageFile;

/**
 * Class SearchAdapterInterface
 * @package Dynamicus\Image\Search\Adapter
 */
interface SearchAdapterInterface
{

    /**
     * @param $searchText
     * @return \SplObjectStorage|ImageFile[]
     */
    public function search($searchText): \SplObjectStorage;
}
