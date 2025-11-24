<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Maatwebsite\Excel\Concerns\FromCollection;

class VerifikasiStaffExport implements FromCollection
{
    public function collection()
    {
        return PerjalananDinas::all();
    }
}
