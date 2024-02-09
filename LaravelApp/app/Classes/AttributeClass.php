<?php

namespace App\Classes;


class AttributeClass {

    public function __construct(
        public string|null $hairColor,
        public string|null $ethnicity,

        public bool $tattoos,
        public bool $piercings,
        public int|null $breastSize,

        public string|null $breastType,
        public string $gender,
        public string $orientation,

        public int|null $age,
        public StatClass $stats

    ) {
    }


}
