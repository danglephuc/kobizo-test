<?php

namespace App\Helpers;

use Exception;

class TestHelper
{

    /**
     * @param array $arr
     *
     * @return int
     * @throws Exception
     */
    public static function calculateSubMaxMin(array $arr): int
    {
        if (count($arr) == 0) {
            throw new Exception("The input array must have at least 1 number");
        }

        return (int)(max($arr) - min($arr));
    }

    /**
     * @param int $n
     *
     * @return int
     * @throws Exception
     */
    public static function calculateSum(int $n): int
    {
        if ($n < 0) {
            throw new Exception("The input number must be greater than 0");
        }

        return $n * ($n + 1) / 2;
    }
}
