<?php

namespace App\Repositories;

class TestingRulesRepository
{

    public function check($value, $type) : bool {

        $getType = gettype($value);

        return $getType === $type && $this->$getType($value);
    }

    private function string($value): bool
    {
        return is_string($value);
    }

    private function array($value): bool
    {
        return is_array($value);
    }

    private function integer($value): bool
    {
        return is_integer($value);
    }

    private function float($value): bool
    {
        return is_float($value);
    }

    private function null($value): bool
    {
        return is_null($value);
    }

    private function url($value): bool {
        return filter_var($value, FILTER_VALIDATE_URL);
    }
}
