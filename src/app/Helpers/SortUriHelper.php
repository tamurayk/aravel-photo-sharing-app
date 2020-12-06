<?php
declare(strict_types=1);

use Illuminate\Http\Request;

if (!function_exists('sort_uri')) {
    /**
     * ソート用のURLを生成
     *
     * @param Request $request
     * @param string $orderColumn
     * @param string|null $orderDirection must be "asc" or "desc"
     * @param string $columnKey
     * @param string $directionKey
     * @return string
     */
    function sort_uri(
        Request $request,
        string $orderColumn,
        ?string $orderDirection = null,
        string $columnKey = 'column',
        string $directionKey = 'direction'
    ):string {
        $currentUri = $request->fullUrlWithQuery($request->query());
        $currentColumn = $request->query($columnKey);
        $currentDirection = $request->query($directionKey);

        $orderDirection = $orderDirection ?? sort_direction($orderColumn, $currentColumn, $currentDirection);;

        return  sort_uri_from_current_uri($currentUri, $orderColumn, $orderDirection, $columnKey, $directionKey);
    }
}
