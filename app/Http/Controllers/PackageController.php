<?php

namespace App\Http\Controllers;

use App\Models\MonthlyInvoicePackage;
use App\Models\StudentTutoringPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function tutoringPackageAjax(Request $request)
    {

        $name = trim($request->name);
        $id = getOriginalPackageIdFromCode($name);

        $studentTutoringPackages = StudentTutoringPackage::select(['student_tutoring_packages.id as id', 'student_tutoring_packages.status'])
            ->selectRaw("CONCAT(students.first_name,' ',students.last_name) as name")
            ->join('students', 'students.id', '=', 'student_tutoring_packages.student_id');
        if(Auth::user()->hasRole(['tutor'])){
            $studentTutoringPackages = $studentTutoringPackages->whereHas('tutors', function ($query) {
                $query->where('tutors.id', Auth::user()->id);
            });
        }

        $studentTutoringPackages = $studentTutoringPackages
            ->where(function ($q) use ($id, $name){
                $q->where('student_tutoring_packages.id', 'LIKE', "%{$id}%")
                    ->orWhere('students.first_name', 'LIKE', "%{$name}%")
                    ->orWhere('students.last_name', 'LIKE', "%{$name}%");
            })
            ->where(function ($q){
                $q->where('student_tutoring_packages.status',  true);
            })
            ->limit(5);

        $monthlyInvoicePackages = MonthlyInvoicePackage::select(['monthly_invoice_packages.id as id','monthly_invoice_packages.status'])
            ->selectRaw("CONCAT(students.first_name,' ',students.last_name) as name")
            ->join('students', 'students.id', '=', 'monthly_invoice_packages.student_id');
        if(Auth::user()->hasRole(['tutor'])){
            $monthlyInvoicePackages = $monthlyInvoicePackages->whereHas('tutors', function ($query) {
                $query->where('tutors.id', Auth::user()->id);
            });
        }


        $allPackages = $monthlyInvoicePackages
            ->where(function ($q) use ($id, $name){
                $q->where('monthly_invoice_packages.id', 'LIKE', "%{$id}%")
                    ->orWhere('students.first_name', 'LIKE', "%{$name}%")
                    ->orWhere('students.last_name', 'LIKE', "%{$name}%");
            })
            ->where(function ($q){
                $q->where('monthly_invoice_packages.status',  true);
            })
            ->limit(5)
            ->union($studentTutoringPackages)->get();

        $packages = [];
        foreach ($allPackages as $package) {
            $data = [];
            $data['id'] = getPackageCodeFromId($package);
            $data['text'] = getPackageCodeFromId($package).' - '.$package->name;
            $packages[] = $data;
        }
        return response()->json($packages);
    }
}
