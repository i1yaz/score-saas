<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInstallments;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class InstallmentController extends Controller
{
    public function createInstallments(CreateInstallments $request,$invoiceId)
    {
        $data = getInstallmentsAndDueDate($request,$invoiceId);
        $installments = $data['installments'];
        $invoice = $data['invoice'];
        $this->authorize('createInstallments', $invoice);
        if ($invoice->has_installments) {
            Flash::error('Installments already created for this invoice');
            return redirect(route('invoices.index'));
        }
        return view('invoices.create-installments', ['installments' => $installments, 'invoiceId' => $invoiceId,'due_date' => $request->due_date,'installmentsCount' => $request->installments]);
    }
    public function storeInstallments(CreateInstallments $request,$invoiceId)
    {
        $data = getInstallmentsAndDueDate($request,$invoiceId);
        $installments = $data['installments'];
        $invoice = $data['invoice'];
        $this->authorize('createInstallments', $invoice);
        if ($invoice->has_installments) {
            Flash::error('Installments already created for this invoice');
            return redirect(route('invoices.index'));
        }
        $createInstallments = [];
        foreach ($installments as $installment) {
            $createInstallments[] = [
                'invoice_id' => $invoiceId,
                'amount' => $installment->amountToBePaid,
                'due_date' => $installment->dueDate,
                'auth_guard' => Auth::guard()->name,
                'added_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Installment::insert($createInstallments);
        $invoice->has_installments = true;
        $invoice->save();
        return response()->json(
            [
                'success' => true,
                'message' => 'Installments created successfully',
                'redirectTo' => route('invoices.show',$invoiceId)
            ]
        );
    }

    public function payInstallments(Request $request, $installment)
    {
        $installment = Installment::findOrFail($installment);
        if ($installment->is_paid===true){
            Flash::error('Installment already paid');
            return redirect(route('invoices.show',$installment->invoice_id));
        }

        return view('invoices.pay-installment', [
            'installment' => $installment,
            'stripeKey' =>  config('services.stripe.key'),
            'paymentModes' => getPaymentGateways(),
            'totalAmount' => $installment->amount,
        ]);
    }
}
