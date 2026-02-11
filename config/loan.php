<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fine Per Day
    |--------------------------------------------------------------------------
    |
    | Tarif denda keterlambatan pengembalian alat per hari.
    |
    */
    'fine_per_day' => (int) env('LOAN_FINE_PER_DAY', 5000),

    /*
    |--------------------------------------------------------------------------
    | Fine For Damaged Tool
    |--------------------------------------------------------------------------
    |
    | Denda tambahan jika alat dikembalikan dalam kondisi rusak.
    |
    */
    'damage_fine' => (int) env('LOAN_DAMAGE_FINE', 50000),
];
