<?php

namespace App\Classes;


class ThumbnailClass {

    public function __construct(
        public int $height,
        public int $width,
        public string $type,
        public string $urls,

    ) {
    }


}
