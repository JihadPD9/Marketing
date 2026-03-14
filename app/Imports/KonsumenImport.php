<?php

namespace App\Imports;

use App\Models\Konsumen;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KonsumenImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Konsumen([
            'nama' => $row['nama'],
            'no_hp' => $row['no_hp'],
            'email' => $row['email'],
            'alamat' => $row['alamat'],
            'sumber_lead' => $row['sumber_lead'],
            'status' => $row['status'] ?? 'Prospek',
            'user_id' => Auth::id()
        ]);
    }
}