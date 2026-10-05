<?php

namespace App\Support;

/**
 * Page-size options shared by every admin listing (spec §28.4).
 */
final class AdminPagination
{
    /**
     * @var list<int>
     */
    public const PAGE_SIZES = [10, 20, 50, 100];

    public const DEFAULT_PAGE_SIZE = 20;
}
