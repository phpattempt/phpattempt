<?php

if (!function_exists('attempt')) {
    /**
     * @template TRet
     * @param callable(): TRet $expression
     * @return array{ 0: TRet|null, 1: Exception|null }
     */
    function attempt(callable $expression): array
    {
        try {
            return [$expression(), null];
        } catch (Exception $exception) {
            return [null, $exception];
        }
    }
}
