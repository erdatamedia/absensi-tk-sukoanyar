<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $action = $request->input('action');
        $perPage = (int) $request->input('per_page', 20);

        if ($perPage <= 0) {
            $perPage = 20;
        }

        if (!Schema::hasTable('activity_logs')) {
            $logs = new LengthAwarePaginator(
                collect(),
                0,
                $perPage,
                LengthAwarePaginator::resolveCurrentPage(),
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            return view('activity-log.index', [
                'logs' => $logs,
                'actions' => collect(),
                'q' => $q,
                'actionFilter' => $action,
                'perPage' => $perPage,
                'missingTable' => true,
            ]);
        }

        $logs = ActivityLog::with('user')
            ->when($action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('description', 'like', '%' . $q . '%')
                        ->orWhere('action', 'like', '%' . $q . '%')
                        ->orWhereHas('user', function ($userQuery) use ($q) {
                            $userQuery->where('name', 'like', '%' . $q . '%')
                                ->orWhere('email', 'like', '%' . $q . '%');
                        });
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $actions = ActivityLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('activity-log.index', [
            'logs' => $logs,
            'actions' => $actions,
            'q' => $q,
            'actionFilter' => $action,
            'perPage' => $perPage,
            'missingTable' => false,
        ]);
    }
}
