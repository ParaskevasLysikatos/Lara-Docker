<?php

namespace App\Classes;


class AdultActorClass {

    public function __construct(
        public AttributeClass $attributes,
        public int $id,

        public string $name,
        public string $license,

        public int $wlStatus,
        public array $aliases,

        public string $link,
        public array $thumbnail
    ) {
    }


}
