<?php

namespace App\Actions;

class GeneratePdf
{
    public function execute(string $view, array $data = []): string
    {
        // Example: using barryvdh/laravel-dompdf
        $pdf = app('dompdf.wrapper')
            ->loadView($view, $data);

        $fileName = storage_path('app/tmp/' . uniqid() . '.pdf');

        $pdf->save($fileName);

        return $fileName;
    }
}