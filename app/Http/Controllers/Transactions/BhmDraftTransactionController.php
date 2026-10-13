<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Transactions\BhmDraftTransaction;
use App\Models\Transactions\BhmTransaction;
use App\Models\Transactions\BhmTransactionType;
use App\Models\Buildings\BhmCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BhmDraftTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = BhmDraftTransaction::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', $search)
                  ->orWhere('ticket_number', 'like', "{$search}%")
                  ->orWhere('national_id', $search)
                  ->orWhere('national_id', 'like', "{$search}%")
                  ->orWhere('phone', $search)
                  ->orWhere('phone', 'like', "{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $drafts = $query->paginate(15)->withQueryString();

        return view('transactions.drafts.index', compact('drafts'));
    }

    public function show($id)
    {
        $draft = BhmDraftTransaction::findOrFail($id);
        $transactionTypes = BhmTransactionType::all();
        $complaintTypes = collect([]);
        $departments = collect([
            (object)['id' => 1, 'name' => 'الهندسة والتنظيم'],
            (object)['id' => 2, 'name' => 'الجباية'],
            (object)['id' => 3, 'name' => 'المياه والصرف الصحي'],
            (object)['id' => 4, 'name' => 'الحرف والصناعات'],
            (object)['id' => 5, 'name' => 'الشؤون الإدارية'],
            (object)['id' => 6, 'name' => 'الرقابة الداخلية'],
            (object)['id' => 7, 'name' => 'المستودعات'],
        ]);
        return view('transactions.drafts.show', compact('draft', 'transactionTypes', 'complaintTypes', 'departments'));
    }

    public function convertToTransaction(Request $request, $id)
    {
        $draft = BhmDraftTransaction::findOrFail($id);
        
        $validated = $request->validate([
            'transaction_type_id' => 'required|exists:bhm_transaction_types,id',
            'customer_id' => 'required|exists:bhm_customers,id',
        ]);

        DB::beginTransaction();
        try {
            $transaction = BhmTransaction::create([
                'transaction_number' => BhmTransaction::generateTransactionNumber(),
                'transaction_type_id' => $validated['transaction_type_id'],
                'customer_id' => $validated['customer_id'],
                'details' => $draft->details,
                'status' => 'pending',
            ]);

            $draft->update([
                'status' => 'converted',
                'transaction_id' => $transaction->id,
            ]);

            DB::commit();
            return redirect()->route('transactions.show', $transaction->id)->with('success', 'تم تحويل الطلب المبدئي إلى معاملة رسمية بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تحويل الطلب: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        BhmDraftTransaction::findOrFail($id)->delete();
        return redirect()->route('transactions.drafts.index')->with('success', 'تم حذف الطلب بنجاح.');
    }
}
