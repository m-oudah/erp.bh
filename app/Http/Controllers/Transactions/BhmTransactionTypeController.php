<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Transactions\BhmTransactionType;
use Illuminate\Http\Request;

class BhmTransactionTypeController extends Controller
{
    public function index()
    {
        $types = BhmTransactionType::all();
        if (request()->expectsJson()) {
            return response()->json($types);
        }
        return view('transactions.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'sla_days' => 'required|integer|min:1',
            'default_depts' => 'nullable|array',
            'responsible_dept' => 'nullable|string',
            'request_fees' => 'nullable|string',
            'required_docs' => 'nullable|array',
            'execution_steps' => 'nullable|string',
        ]);

        $type = BhmTransactionType::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'type' => $type]);
        }
        return back()->with('success', 'تم إضافة النوع بنجاح.');
    }

    public function update(Request $request, $id)
    {
        $type = BhmTransactionType::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'sla_days' => 'required|integer|min:1',
            'default_depts' => 'nullable|array',
            'responsible_dept' => 'nullable|string',
            'request_fees' => 'nullable|string',
            'required_docs' => 'nullable|array',
            'execution_steps' => 'nullable|string',
        ]);

        $type->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'type' => $type]);
        }
        return back()->with('success', 'تم تحديث النوع بنجاح.');
    }

    public function destroy($id)
    {
        BhmTransactionType::findOrFail($id)->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'تم حذف النوع بنجاح.');
    }
}
