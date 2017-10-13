<?php

namespace Dictionary\Adapter;

use Dictionary\Entity\Dictionary;

interface AdapterInterface
{

    public function __invoke($text): Dictionary;
}
