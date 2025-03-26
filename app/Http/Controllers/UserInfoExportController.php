<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class UserInfoExportController extends Controller
{
    public function exportUserInfoPDF($user_id)
    {
        $user = User::find($user_id);

        $data = [
            'Name' => $user->name,
            'Email' => $user->email,
            'Registered' => $user->email_verified_at
        ];

        $pdf = Pdf::loadView('pdf.user_info', compact('data'));

        return $pdf->download('user_info.pdf');
    }
}
