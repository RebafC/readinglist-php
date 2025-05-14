<?php

namespace App\Models;

use App\Models\AbstractModel;

class Tag extends AbstractModel
{
    public function __construct(
        public string $id,
        public string $name
    ) {}
}