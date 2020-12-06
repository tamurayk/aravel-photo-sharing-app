<?php
declare(strict_types=1);

if (!function_exists('sort_direction')) {
    /**
     * 現在のソートの状態から、ソートボタン押下後の direction を算出
     *
     * @param string $orderColumn
     * @param string|null $currentColumn
     * @param string|null $currentDirection
     * @return string
     */
    function sort_direction(
        string $orderColumn,
        ?string $currentColumn = null,
        ?string $currentDirection = null
    ):string {
        $orderDirection = 'asc';

        if ($currentDirection
            && $currentColumn === $orderColumn
            && $currentDirection === 'asc'
        ) {
            $orderDirection = 'desc';
        }

        return $orderDirection;
    }
}
