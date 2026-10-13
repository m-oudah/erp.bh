<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Transactions\BhmTransaction;
use App\Models\Transactions\BhmTransactionType;
use App\Models\Transactions\BhmDepartmentResponse;
use App\Models\Transactions\BhmTransactionAuditLog;
use App\Models\Buildings\BhmCustomer;
use App\Models\Buildings\BhmBuilding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BhmTransactionController extends Controller
{
    public function dashboard(Request $request) { 
        $transactions = collect();
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $cleanedTerm = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search);
            $terms = array_filter(explode(' ', trim($cleanedTerm)));
            $boolQuery = !empty($terms) ? implode(' ', array_map(fn($t) => '+' . $t . '*', $terms)) : null;

            $transactions = \App\Models\Transactions\BhmTransaction::with(['customer', 'building', 'transactionType'])
                ->where(function ($q) use ($search, $boolQuery) {
                    $q->where('transaction_number', $search)
                      ->orWhere('transaction_number', 'like', "{$search}%")
                      ->orWhere('transaction_number', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($q2) use ($search, $boolQuery) {
                          $q2->where('id_no', $search)
                             ->orWhere('mobile', $search)
                             ->orWhere('id_no', 'like', "{$search}%")
                             ->orWhere('mobile', 'like', "{$search}%");
                          if ($boolQuery) {
                              $q2->orWhereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$boolQuery]);
                          }
                          $q2->orWhere('name', 'like', "%{$search}%");
                      });
                })->get();
        }
        return view('transactions.transactions.dashboard', compact('transactions')); 
    }

    public function history(Request $request)
    {
        $query = BhmTransaction::with(['customer', 'building', 'transactionType', 'departmentResponses'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $cleanedTerm = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search);
            $terms = array_filter(explode(' ', trim($cleanedTerm)));
            $boolQuery = !empty($terms) ? implode(' ', array_map(fn($t) => '+' . $t . '*', $terms)) : null;

            $query->where(function ($q) use ($search, $boolQuery) {
                $q->where('transaction_number', $search)
                    ->orWhere('transaction_number', 'like', "{$search}%")
                    ->orWhere('transaction_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search, $boolQuery) {
                        $q2->where('id_no', $search)
                           ->orWhere('mobile', $search)
                           ->orWhere('id_no', 'like', "{$search}%")
                           ->orWhere('mobile', 'like', "{$search}%");
                        if ($boolQuery) {
                            $q2->orWhereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$boolQuery]);
                        }
                        $q2->orWhere('name', 'like', "%{$search}%");
                    });

                if ($boolQuery) {
                    $q->orWhereRaw("MATCH(details) AGAINST(? IN BOOLEAN MODE)", [$boolQuery]);
                }
                $q->orWhere('details', 'like', "%{$search}%")
                  ->orWhereHas('transactionType', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(15)->withQueryString();
        $transactionTypes = BhmTransactionType::all();
        
        $activeTab = $request->input('tab', 'transactions');
        // Dummy or actual paginators for the tabs in the view
        $complaints = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        $drafts = \App\Models\Transactions\BhmDraftTransaction::where('status', 'pending')->paginate(15);

        return view('transactions.transactions.history', compact('transactions', 'transactionTypes', 'activeTab', 'complaints', 'drafts'));
    }

    public function create()
    {
        $transactionTypes = BhmTransactionType::all();
        $customers = BhmCustomer::orderBy('name')->get();
        $buildings = BhmBuilding::orderBy('file_number')->get();
        $departments = collect([
            (object)['id' => 1, 'name' => 'الهندسة والتنظيم'],
            (object)['id' => 2, 'name' => 'الجباية'],
            (object)['id' => 3, 'name' => 'المياه والصرف الصحي'],
            (object)['id' => 4, 'name' => 'الحرف والصناعات'],
            (object)['id' => 5, 'name' => 'الشؤون الإدارية'],
            (object)['id' => 6, 'name' => 'الرقابة الداخلية'],
            (object)['id' => 7, 'name' => 'المستودعات'],
        ]);
        return view('transactions.transactions.create', compact('transactionTypes', 'customers', 'buildings', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_type_id' => 'required|exists:bhm_transaction_types,id',
            'customer_id' => 'required|exists:bhm_customers,id',
            'building_id' => 'nullable|exists:bhm_buildings,id',
            'details' => 'nullable|string',
            'departments' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $transaction = BhmTransaction::create([
                'transaction_number' => BhmTransaction::generateTransactionNumber(),
                'transaction_type_id' => $validated['transaction_type_id'],
                'customer_id' => $validated['customer_id'],
                'building_id' => $validated['building_id'] ?? null,
                'details' => $validated['details'],
                'status' => 'pending',
            ]);

            // Create initial audit log
            BhmTransactionAuditLog::create([
                'transaction_id' => $transaction->id,
                'user_name' => auth()->user()->name ?? 'System',
                'action' => 'إنشاء معاملة جديدة',
                'new_values' => $transaction->toArray(),
            ]);

            // Assign departments if any
            if (!empty($validated['departments'])) {
                foreach ($validated['departments'] as $deptName) {
                    BhmDepartmentResponse::create([
                        'transaction_id' => $transaction->id,
                        'department_name' => $deptName,
                        'status' => 'pending',
                    ]);
                }
            } else {
                // If default depts exist
                $type = BhmTransactionType::find($validated['transaction_type_id']);
                if ($type && $type->default_depts) {
                    foreach ($type->default_depts as $deptName) {
                        BhmDepartmentResponse::create([
                            'transaction_id' => $transaction->id,
                            'department_name' => $deptName,
                            'status' => 'pending',
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'تم إنشاء المعاملة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء المعاملة: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaction = BhmTransaction::with([
            'customer', 
            'building', 
            'transactionType', 
            'departmentResponses', 
            'attachments', 
            'logs'
        ])->findOrFail($id);
        
        $departments = collect([
            (object)['id' => 1, 'name' => 'الهندسة والتنظيم'],
            (object)['id' => 2, 'name' => 'الجباية'],
            (object)['id' => 3, 'name' => 'المياه والصرف الصحي'],
            (object)['id' => 4, 'name' => 'الحرف والصناعات'],
            (object)['id' => 5, 'name' => 'الشؤون الإدارية'],
            (object)['id' => 6, 'name' => 'الرقابة الداخلية'],
            (object)['id' => 7, 'name' => 'المستودعات'],
        ]);

        return view('transactions.transactions.show', compact('transaction', 'departments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = BhmTransaction::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,returned,approved,closed',
            'mayor_opinion' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
        ]);

        $oldValues = $transaction->toArray();
        $transaction->update($validated);

        BhmTransactionAuditLog::create([
            'transaction_id' => $transaction->id,
            'user_name' => auth()->user()->name ?? 'System',
            'action' => 'تحديث حالة المعاملة إلى: ' . $validated['status'],
            'old_values' => $oldValues,
            'new_values' => $transaction->toArray(),
        ]);

        return back()->with('success', 'تم تحديث حالة المعاملة بنجاح.');
    }

    public function destroy($id)
    {
        $transaction = BhmTransaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'تم حذف المعاملة بنجاح.');
    }
}
