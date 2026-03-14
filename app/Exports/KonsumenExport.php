<?php

namespace App\Exports;

use App\Models\Konsumen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KonsumenExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = Konsumen::select(
            'nama',
            'no_hp',
            'email',
            'alamat',
            'sumber_lead',
            'status'
        );

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'No HP',
            'Email',
            'Alamat',
            'Sumber Lead',
            'Status'
        ];
    }
}