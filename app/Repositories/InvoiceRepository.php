<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\MonthlyInvoicePackage;
use App\Models\ParentUser;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'amount_paid',
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
                'invoices.amount_paid',
                'invoices.general_description',
                'invoices.detailed_description',
                'invoices.paid_status as invoice_status',
                'tutoring_package_types.name as tutoring_package_type_name',

            ])
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
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
        $invoice->amount_paid = 0;
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

    public function create(array $input): Model
    {
        // client_id" => "3"
        //  "due_date" => "10/31/2023"
        //  "general_description" => null
        //  "detailed_description" => null
        //  "item_id" => array:2 [
        //    1 => "2"
        //    2 => "1"
        //  ]
        //  "quantity" => array:2 [
        //    1 => "1"
        //    2 => "2"
        //  ]
        //  "price" => array:2 [
        //    1 => "10.8"
        //    2 => "14.6"
        //  ]
        //  "tax_id" => array:3 [
        //    0 => array:1 [
        //        1 => "1"
        //    ]
        //    1 => array:1 [
        //        2 => "1"
        //    ]
        //    2 => array:1 [
        //        2 => "2"
        //    ]
        //  ]
        //  "discount" => "11"
        //  "discount_type" => "1"
        //  "tax2_id" => array:2 [
        //        0 => "1"
        //        1 => "2"
        //  ]
        //  "first_name" => null
        //  "last_name" => null
        //  "email" => null
        //  "address" => null
//        $user->roles()->sync([1 => ['expires' => true], 2, 3]);
        $invoice = new Invoice();
        $invoice->invoice_package_type_id = 5;
        $invoice->due_date = $input['due_date'];
        $invoice->general_description = $input['general_description'] ?? null;
        $invoice->detailed_description = $input['detailed_description'] ?? null;
        $invoice->invoiceable_type = Client::class;
        $invoice->tax2_ids = json_encode($input['tax2_id']);
        $invoice->invoiceable_id = $input['client_id'];
        $invoice->auth_guard = Auth::guard()->name;
        $invoice->added_by = Auth::id();
//        $invoice->save();
        $data = [];
        $totalFinalAmount = 0;
        foreach ($input['item_id'] as $key => $item_id) {
            $taxedAmount = getTaxAmountForLine($input['tax_id'], $input['price'][$key], $input['quantity'][$key],$key);
            $finalAmount = (($input['quantity'][$key] * $input['price'][$key]) + $taxedAmount);
            $data[$item_id] = [
                    'tax_ids' => getTaxIdsForLineInJson($input['tax_id'], $key),
                    'price' => $input['price'][$key],
                    'qty' => $input['quantity'][$key],
                    'tax_amount' => $taxedAmount,
                    'final_amount' =>  $finalAmount,
                    'auth_guard' => Auth::guard()->name,
                    'added_by' => Auth::id(),
            ];
            $totalFinalAmount += $finalAmount;
//            $invoice->items()->attach($item_id, [
//                'qty' => $input['quantity'][$key],
//                'price' => $input['price'][$key],
//                'tax_ids' => json_encode($input['tax_id'][$key]),
//                'tax2_ids' => json_encode($input['tax2_id'][$key]),
//                'discount' => $discountedAmount,
//                'discount_type' => $input['discount_type'],
//                'final_amount' => ($input['quantity'][$key] * $input['price'][$key]) - $discountedAmount,
//                'auth_guard' => Auth::guard()->name,
//                'added_by' => Auth::id(),
//            ]);
        }
        $discountedAmountOnSubtotal = getDiscountedAmountOnSubtotal($input['discount_type'], $input['discount'], $totalFinalAmount);

        dd($data,$invoice,$totalFinalAmount,$discountedAmountOnSubtotal);

    }
}
