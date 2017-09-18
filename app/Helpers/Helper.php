<?php

namespace App\Helpers;

class Helper
{

    public static function list($items)
    {
        $total = count($items);
        if ($total > 1) {
            $items = implode(', ' , array_slice($items, 0, $total - 1)) . ' and ' . end($items);
        } else {
            $items = implode(', ' , $items);
        }

        return $items;
    }

}
