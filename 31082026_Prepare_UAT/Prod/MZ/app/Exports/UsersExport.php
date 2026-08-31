<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithStyles
{
    protected $userData;

    public function __construct($userData)
    {
        $this->userData = $userData;
    }

    public function collection()
    {
        return collect($this->userData->map(function ($user, $index) {
            $roleNames = $user->roles->pluck('display_name')->implode(', ');

            $subgroupName = isset($user->user_unit->subgroup_info_id) && $user->user_unit->subgroup_info_id != 0 ? $user->user_unit->subgroup?->name : '';
            $groupName = isset($user->user_unit->group_info_id) && $user->user_unit->group_info_id != 0 ? $user->user_unit->group->name : '';
            $departmentName = isset($user->user_unit->department_id) && $user->user_unit->department_id != 0 ? $user->user_unit->department->name : '';
            $divisionName = isset($user->user_unit->division_id) && $user->user_unit->division_id != 0 ? $user->user_unit->division->name : '';

            $displayName = $subgroupName ?: ($groupName ?: ($departmentName ?: $divisionName));
            return [
                'SL' => $index + 1,
                'User ID' => $user->user_id,
                'Name' => $user->name,
                'Designation' => $user->designation,
                'Email' => $user->email,
                'Mobile No' => $user->mobile_no,
                'Emp ID' => $user->emp_id,
                'Remarks' => $user->remarks,
                'Last Login' => $user->last_login_time,
                'Unit Name' => $displayName,
                'Role Name' => $roleNames,
                'Status' => $user->status == 1 ? 'Active' : ($user->status == 0 ? 'Inactive' : 'Closed'),
                'Created' => \Carbon\Carbon::parse($user->created_at)->format('d-m-Y h:i:s A')
            ];
        }));
    }

    public function headings(): array
    {
        return [
            'SL',
            'User ID',
            'Name',
            'Designation',
            'Email',
            'Mobile No',
            'Emp ID',
            'Remarks',
            'Last Login',
            'Unit Name',
            'Role Name',
            'Status',
            'Created'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FF008000'], // Green font color
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => [
                        'argb' => 'FFFFE699', // Light yellow background
                    ]
                ]
            ],
        ];
    }
}


