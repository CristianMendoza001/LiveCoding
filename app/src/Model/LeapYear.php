<?php

namespace Crimsoncircle\Model;

class LeapYear
{
    public function isLeapYear(?int $year): bool
    {
        if($year == NULL)
            $year = date('Y');

        if ($year % 400 == 0)
            return true;
        else if ($year % 100 == 0)
            return false;
        else if ($year % 4 == 0)
            return true;
        else
            return false;
    }
}