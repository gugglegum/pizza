<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Filters;

class Trim extends AbstractFilter
{
    public function filter($value)
    {
        $value = trim($value);
        return $value;
    }

}
