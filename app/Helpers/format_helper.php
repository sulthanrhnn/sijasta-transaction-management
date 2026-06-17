<?php

if (!function_exists('formatRupiah')) {
    /**
     * Format angka ke dalam format Rupiah
     *
     * @param int|float $number
     * @return string
     */
    function formatRupiah($number)
    {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
}
