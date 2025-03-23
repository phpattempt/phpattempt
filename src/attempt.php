<?php

if (!function_exists('attempt')) {
    /**
     * @param callable $expression
     * @return array{ data: mixed|null, error: Exception|null }
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