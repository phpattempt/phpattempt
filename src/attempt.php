<?php

if (!function_exists('attempt')) {
    /**
     * @param callable $expression
     * @return array{ 0: mixed|null, 1: Exception|null }
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
