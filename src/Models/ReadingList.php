<?php

namespace App\Models;

use App\Models\AbstractModel;

class ReadingList extends AbstractModel
{
    public function __construct(
        public string $id,
        public string $title,
        public string $url,
        public string $host,
        public timestamp $added_at,
        public timestamp $deleted_at
    ) {}
}