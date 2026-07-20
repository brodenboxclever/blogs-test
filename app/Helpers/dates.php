<?php

use Carbon\Carbon;

if (! function_exists('a_day_ago')) {
    function a_day_ago(Carbon|int|string|null $from = null): Carbon
    {
        $date = match (true) {
            $from instanceof Carbon => $from->copy(),
            is_null($from) => Carbon::now(),
            default => Carbon::parse($from),
        };

        return $date->subDay();
    }
}

if (! function_exists('a_month_ago')) {
    function a_month_ago(Carbon|int|string|null $from = null): Carbon
    {
        $date = match (true) {
            $from instanceof Carbon => $from->copy(),
            is_null($from) => Carbon::now(),
            default => Carbon::parse($from),
        };

        return $date->subMonth();
    }
}

if (! function_exists('a_year_ago')) {
    function a_year_ago(Carbon|int|string|null $from = null): Carbon
    {
        $date = match (true) {
            $from instanceof Carbon => $from->copy(),
            is_null($from) => Carbon::now(),
            default => Carbon::parse($from),
        };

        return $date->subYear();
    }
}
