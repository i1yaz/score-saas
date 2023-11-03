<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\MonthlyInvoicePackage;
use App\Models\NonInvoicePackage;
use App\Models\ParentUser;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class InvoiceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'invoice_package_type_id',
        'due_date',
        'fully_paid_at',
        'general_description',
        'detailed_description',
        'email_to_parent',
        'email_to_student',
        'amount_remaining',
        'paid_status',
        'paid_by_modal',
        'paid_by_id',
        'invoiceable_type',
        'invoiceable_id',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Invoice::class;
    }

    public function show($id)
    {
        $invoice = Invoice::query()->select(
            [
                'invoices.id as invoice_id', 'invoices.paid_status as invoice_status', 'invoices.invoiceable_type', 'student_tutoring_packages.student_id',
                'students.parent_id as parent_id', 'students.id as student_id', 'students.email as student_email', 'parents.id as student_id', 'parents.email as parent_email', 'invoices.created_at as invoice_created_at',
                'invoices.due_date', 'student_tutoring_packages.hourly_rate', 'student_tutoring_packages.hours', 'student_tutoring_packages.discount',
                'student_tutoring_packages.discount_type',
                'invoices.fully_paid_at',
                'invoices.general_description',
                'invoices.detailed_description',
                'invoices.paid_status as invoice_status',
                'tutoring_package_types.name as tutoring_package_type_name',
                'monthly_invoice_packages.hourly_rate', 'monthly_invoice_packages.discount',
                'monthly_invoice_packages.discount_type',
            ])
            ->selectRaw('sum(payments.amount) as amount_paid')
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('monthly_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'monthly_invoice_packages.id')->where('invoices.invoiceable_type', '=', MonthlyInvoicePackage::class);
            })
            ->leftJoin('payments', 'payments.invoice_id', 'invoices.id')
            ->leftJoin('tutoring_package_types', 'student_tutoring_packages.tutoring_package_type_id', '=', 'tutoring_package_types.id')
            ->leftJoin('students', 'student_tutoring_packages.student_id', '=', 'students.id')
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.id');
        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $invoice = $invoice->where('parent_id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $invoice = $invoice->where('student_id', Auth::id())
                ->WhereRaw('CASE WHEN students.parent_id IS NULL THEN true ELSE false END');
        }

        return $invoice->where('invoices.id', $id)->first();

    }

    public function createOrUpdateInvoiceForPackage($studentTutoringPackage, $input = []): Invoice
    {

        $invoice = Invoice::where('invoiceable_type', StudentTutoringPackage::class)
            ->where('invoiceable_id', $studentTutoringPackage->id)
            ->first();

        if (!$invoice){
            $invoice = new Invoice();

        }
        $invoice->invoice_package_type_id = 1;
        $invoice->due_date = $studentTutoringPackage->due_date;
        $invoice->general_description = $input['general_description'] ?? null;
        $invoice->detailed_description = $input['detailed_description'] ?? null;
        $invoice->email_to_parent = $input['email_to_parent'] ?? false;
        $invoice->paid_status = Invoice::PENDING;
        $invoice->invoiceable_type = StudentTutoringPackage::class;
        $invoice->invoiceable_id = $studentTutoringPackage->id;
        $invoice->auth_guard = Auth::guard()->name;
        $invoice->added_by = Auth::id();
        $invoice->save();
        return $invoice;
    }

    public function createOrUpdateInvoiceForMonthlyPackage(MonthlyInvoicePackageRepository|MonthlyInvoicePackage $monthlyInvoicePackage, $input = []): Invoice
    {
        $invoice = Invoice::where('invoiceable_type', MonthlyInvoicePackage::class)
            ->where('invoiceable_id', $monthlyInvoicePackage->id)
            ->first();
        if (!$invoice){
            $invoice = new Invoice();
        }
        $invoice->invoice_package_type_id = 2;
        $invoice->due_date = $monthlyInvoicePackage->due_date;
        $invoice->general_description = $input['general_description'] ?? null;
        $invoice->detailed_description = $input['detailed_description'] ?? null;
        $invoice->email_to_parent = $input['email_to_parent'] ?? false;
        $invoice->amount_paid = 0;
        $invoice->paid_status = Invoice::DRAFT;
        if ($input['is_score_guaranteed'] || $input['is_free']){
            $invoice->paid_status = Invoice::PAID;
            $invoice->fully_paid_at = Carbon::now();
        }
        $invoice->invoiceable_type = MonthlyInvoicePackage::class;
        $invoice->invoiceable_id = $monthlyInvoicePackage->id;
        $invoice->auth_guard = Auth::guard()->name;
        $invoice->added_by = Auth::id();
        $invoice->save();
        return $invoice;
    }

    /**
     * @throws \Exception
     */
    public function create(array $input): NonInvoicePackage
    {
        $data = [];
        $totalFinalAmount = 0;
        foreach ($input['item_id'] as $key => $item_id) {
            $total = (float) $input['price'][$key]* $input['quantity'][$key];
            $tax = 0;
            if (!empty($input['tax_id'])){
                $tax = getTaxAmountForLine($input['tax_id'],$total,$key);
                $taxIds = getTaxIdsForLineInJson($input['tax_id'], $key);
            }
            $finalAmount = ($total + $tax);
            $data[$item_id] = [
                    'tax_ids' => $taxIds ?? null,
                    'price' => $input['price'][$key],
                    'qty' => $input['quantity'][$key],
                    'tax_amount' => $tax,
                    'final_amount' =>  $finalAmount,
                    'auth_guard' => Auth::guard()->name,
                    'added_by' => Auth::id(),
            ];
            $totalFinalAmount += $finalAmount;

        }
        $discountedAmountOnSubtotal = 0;
        if(!empty($input['discount'])){
            $discountedAmountOnSubtotal = getDiscountedAmountOnSubtotal($input['discount_type'], $input['discount'], $totalFinalAmount);
        }
        $totalFinalAmount =  $totalFinalAmount - $discountedAmountOnSubtotal;
        $taxOnSubtotal= 0;
        if (!empty($input['tax2_id'])){
            $taxOnSubtotal = chargeTaxOnSubtotal($input['tax2_id'], $totalFinalAmount);
            $tax2Ids =  json_encode($input['tax2_id']);
        }
        $totalFinalAmount = $totalFinalAmount+$taxOnSubtotal;

        DB::beginTransaction();
        try {
            $nonPackageInvoice = new NonInvoicePackage();
            $nonPackageInvoice->client_id = $input['client_id'];
            $nonPackageInvoice->tax2_ids = $tax2Ids ?? null;
            $nonPackageInvoice->discount_amount = $discountedAmountOnSubtotal;
            $nonPackageInvoice->tax_amount = $taxOnSubtotal;
            $nonPackageInvoice->final_amount = $totalFinalAmount;
            $nonPackageInvoice->auth_guard = Auth::guard()->name;
            $nonPackageInvoice->allow_partial_payment = ($input['allow_partial_payment'] == 1) ?? false;
            $nonPackageInvoice->added_by = Auth::id();
            $nonPackageInvoice->save();

            $invoice = new Invoice();
            $invoice->invoice_package_type_id = 5;
            $invoice->due_date =  Carbon::parse($input['due_date']);
            $invoice->general_description = $input['general_description'] ?? null;
            $invoice->detailed_description = $input['detailed_description'] ?? null;
            $invoice->invoiceable_type = NonInvoicePackage::class;
            $invoice->invoiceable_id = $nonPackageInvoice->id;
            $invoice->paid_status = Invoice::PENDING;
            $invoice->auth_guard = Auth::guard()->name;
            $invoice->added_by = Auth::id();
            $invoice->save();
            $invoice->items()->sync($data);
            DB::commit();
            Flash::success('Invoice saved successfully.');
            return $nonPackageInvoice;
        }catch (QueryException $queryException){
            DB::rollBack();
            report($queryException);
            Flash::error('something went wrong');
        }

    }

    public function showNonPackageInvoice($id)
    {
        $invoice = Invoice::query()->select(
            [
                'invoices.id as invoice_id',
                'non_invoice_packages.final_amount'
            ])
            ->selectRaw('sum(payments.amount) as amount_paid')
            ->leftJoin('payments','payments.invoice_id','invoices.id')
            ->leftJoin('non_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'non_invoice_packages.id')->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
            });
        if (Auth::user()->hasRole('client') && Auth::user() instanceof Client) {
            $invoice = $invoice->where('non_invoice_packages.client_id', Auth::id());
        }
        return $invoice->where('invoices.id', $id)->first();
    }

    public function getInvoiceData($invoice)
    {
    }

    public function getNonPackageInvoiceData($invoice)
    {
    }

    public function getPaymentGateways(): array
    {
        return [
            'stripe' => 'Stripe',
        ];
    }

    public function showTutoringPackageInvoice($id)
    {
        $records = Invoice::query()->select(
            [
                'invoices.id as invoice_id',
                'student_tutoring_packages.hourly_rate',
                'student_tutoring_packages.hours',
                'student_tutoring_packages.discount',
                'student_tutoring_packages.discount_type',
            ])
            ->selectRaw('sum(payments.amount) as amount_paid')
            ->leftJoin('payments','payments.invoice_id','invoices.id')
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('students','students.id','student_tutoring_packages.student_id')
            ->leftJoin('parents','parents.id','students.parent_id');

        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $records = $records->where(function ($q){
                $q->where('student_tutoring_packages.student_id', Auth::id());
            });
        }
        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $records = $records->where(function ($q){
                $q->where('parents.id', Auth::id());
            });
        }
        return $records->where('invoices.id', $id)->first();
    }
}
