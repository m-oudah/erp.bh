<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Transactions\BhmDepartmentResponse;
use App\Models\Transactions\BhmTransactionAuditLog;
use Illuminate\Http\Request;

class BhmDepartmentResponseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:bhm_transactions,id',
            'department_name' => 'required|string',
        ]);

        $response = BhmDepartmentResponse::create([
            'transaction_id' => $validated['transaction_id'],
            'department_name' => $validated['department_name'],
            'status' => 'pending',
        ]);

        BhmTransactionAuditLog::create([
            'transaction_id' => $validated['transaction_id'],
            'user_name' => auth()->user()->name ?? 'System',
            'action' => 'توجيه المعاملة إلى قسم: ' . $validated['department_name'],
            'new_values' => $response->toArray(),
        ]);

        return back()->with('success', 'تم التوجيه بنجاح.');
    }

    public function update(Request $request, $id)
    {
        $response = BhmDepartmentResponse::findOrFail($id);
        
        $validated = $request->validate([
            'response_text' => 'nullable|string',
            'status' => 'required|in:pending,responded,returned',
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('transactions/responses', 'public');
            $validated['attachment_path'] = $path;
        }

        $validated['responded_at'] = now();
        $validated['duration_days'] = $response->created_at->diffInDays(now());

        $oldValues = $response->toArray();
        $response->update($validated);

        BhmTransactionAuditLog::create([
            'transaction_id' => $response->transaction_id,
            'user_name' => auth()->user()->name ?? 'System',
            'action' => 'تحديث رد القسم: ' . $response->department_name,
            'old_values' => $oldValues,
            'new_values' => $response->toArray(),
        ]);

        return back()->with('success', 'تم تحديث رد القسم بنجاح.');
    }

    public function destroy($id)
    {
        $response = BhmDepartmentResponse::findOrFail($id);
        $transactionId = $response->transaction_id;
        $deptName = $response->department_name;
        
        $response->delete();

        BhmTransactionAuditLog::create([
            'transaction_id' => $transactionId,
            'user_name' => auth()->user()->name ?? 'System',
            'action' => 'حذف توجيه القسم: ' . $deptName,
        ]);

        return back()->with('success', 'تم حذف التوجيه بنجاح.');
    }
}
