<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        $studentRoleId = Role::where('name', 'student')->value('id');

        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password'] ?? '123456'),
            'role_id' => $studentRoleId,
        ]);
    }
}
