<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class TeacherController extends Controller
{
    public function index()
    {
        $payment = Payment::orderBy('created_at', 'DESC')->get();

        return view('teacher.dashboard', compact('payment'));
    }
}
