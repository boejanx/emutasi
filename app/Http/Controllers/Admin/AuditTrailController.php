<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SystemLog;

class AuditTrailController extends Controller
{
    public function index()
    {
        $logs = SystemLog::with('user')->latest()->get();
        return view('admin.audit.index', compact('logs'));
    }
}
