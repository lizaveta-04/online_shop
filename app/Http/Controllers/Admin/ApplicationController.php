<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Shop\Application;

class ApplicationController extends BaseController
{
    /**
     * Список заявок.
     */
    public function index(Request $request)
    {
        $query = Application::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(15);

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Детали заявки.
     */
    public function show(Application $application)
    {
        return view('admin.applications.show', compact('application'));
    }

    /**
     * Смена статуса заявки.
     */
    public function updateStatus(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,completed,rejected',
        ]);

        $application->update(['status' => $validated['status']]);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', "Статус заявки обновлён.");
    }
}
