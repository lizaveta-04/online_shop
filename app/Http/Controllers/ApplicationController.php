<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop\Application;

class ApplicationController extends BaseController
{
    /**
     * Форма заявки.
     */
    public function create()
    {
        return view('applications.create');
    }

    /**
     * Сохранение заявки.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'wishes' => 'nullable|string',
        ]);

        Application::create($validated);

        return redirect()->route('home')->with('success', 'Ваша заявка успешно отправлена! Мы свяжемся с вами в ближайшее время.');
    }
}
