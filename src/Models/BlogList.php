<?php

namespace App\Models;

use App\Models\AbstractModel;

class Bloglist extends AbstractModel
{
    public function __construct(
        public string $id,
        public string $title,
        public string $url,
        public string $host,
        public string $notes,
        public timestamp $created,
        public timestamp $modifieded,
        public timestamp $deleted
    ) {}
}