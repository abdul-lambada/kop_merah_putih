<?php

namespace App\Exports;

use App\Models\Member;

class MembersExport
{
    protected $status;
    protected $businessSector;

    public function __construct($status = null, $businessSector = null)
    {
        $this->status = $status;
        $this->businessSector = $businessSector;
    }

    public function download()
    {
        $query = Member::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->businessSector) {
            $query->where('business_sector', $this->businessSector);
        }

        $members = $query->orderBy('created_at', 'desc')->get();

        $filename = 'anggota_kop_merahputih_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV Header
        fputcsv($output, [
            'ID', 'No. Anggota', 'NIK', 'Nama Lengkap', 'Email', 'Telepon', 
            'Alamat', 'Sektor Usaha', 'Pengalaman', 'Tanggal Bergabung', 
            'Status', 'Saldo Simpanan', 'Limit Pinjaman', 'Tanggal Verifikasi', 'Dibuat Pada'
        ]);
        
        // CSV Data
        foreach ($members as $member) {
            fputcsv($output, [
                $member->id,
                $member->member_number,
                $member->nik,
                $member->full_name,
                $member->email ?? '-',
                $member->phone,
                $member->address,
                ucfirst($member->business_sector),
                $this->formatExperience($member->experience),
                $member->join_date->format('d/m/Y'),
                ucfirst($member->status),
                'Rp ' . number_format($member->savings_balance, 0, ',', '.'),
                'Rp ' . number_format($member->loan_limit, 0, ',', '.'),
                $member->verified_at ? $member->verified_at->format('d/m/Y H:i') : '-',
                $member->created_at->format('d/m/Y H:i')
            ]);
        }
        
        fclose($output);
        exit;
    }

    private function formatExperience($experience)
    {
        $mapping = [
            'baru' => 'Baru',
            '2-5_tahun' => '2-5 Tahun',
            '5+_tahun' => '5+ Tahun'
        ];

        return $mapping[$experience] ?? $experience;
    }
}
