<?php

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Kategori;
use App\Models\Penulis;
use App\Models\Penerbit;

class AdminChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $kategoriLabels = Kategori::pluck('nama')->toArray();
        $kategoriData = Kategori::withCount('bukus')->pluck('bukus_count')->toArray();

        $penulisLabels = Penulis::pluck('nama')->toArray();
        $penulisData = Penulis::withCount('bukus')->pluck('bukus_count')->toArray();

        $penerbitLabels = Penerbit::pluck('nama')->toArray();
        $penerbitData = Penerbit::withCount('bukus')->pluck('bukus_count')->toArray();

        return $this->chart->barChart()
            ->setTitle('Statistik Buku')
            ->setSubtitle('Jumlah Buku per Kategori, Penulis, dan Penerbit')
            ->addData('Kategori', $kategoriData)
            ->addData('Penulis', $penulisData)
            ->addData('Penerbit', $penerbitData)
            ->setXAxis($kategoriLabels); // kamu bisa ubah ini jadi penulisLabels atau penerbitLabels
    }
}
