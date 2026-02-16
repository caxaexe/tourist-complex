<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $logs = AuditLog::query()
            ->with('user')
            ->when($q, function ($query) use ($q) {
                $query->where('action', 'like', "%{$q}%")
                      ->orWhere('entity_type', 'like', "%{$q}%")
                      ->orWhere('url', 'like', "%{$q}%")
                      ->orWhere('ip', 'like', "%{$q}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('audit_logs.index', compact('logs', 'q'));
    }
}
