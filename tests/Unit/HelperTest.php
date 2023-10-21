<?php

use App\Models\Invoice;
use App\Models\MonthlyInvoicePackage;
use App\Models\ParentUser;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use App\Models\Tutor;
use Database\Seeders\DatabaseSeeder;

it('return yes or no string',function (){
    expect(booleanSelect(1))->toBeString("yes");
    expect(booleanSelect(0))->toBeString("no");
    expect(booleanSelect(3))->toBeString("no");
});

it('return role description of logged in User',function (){
    $this->seed(DatabaseSeeder::class);
    loginAsSuperAdmin();
    $superAdmin = getRoleDescriptionOfLoggedInUser();
    expect($superAdmin)->toBeString("Super Admin");
    //
    loginAsAdmin();
    $admin = getRoleDescriptionOfLoggedInUser();
    expect($admin)->toBeString("Admin");
    //
    loginAsParent();
    $parent = getRoleDescriptionOfLoggedInUser();
    expect($parent)->toBeString("Parent");
    //
    loginAsStudent();
    $student = getRoleDescriptionOfLoggedInUser();
    expect($student)->toBeString("Student");
    //
    loginAsTutor();
    $tutor = getRoleDescriptionOfLoggedInUser();
    expect($tutor)->toBeString("Tutor");
});

it('return the role of logged in user',function (){
    $this->seed(DatabaseSeeder::class);
    loginAsSuperAdmin();
    $superAdmin = getRoleOfLoggedInUser();
    expect($superAdmin)->toBeString("super-admin");
    //
    loginAsAdmin();
    $admin = getRoleOfLoggedInUser();
    expect($admin)->toBeString("admin");
    //
    loginAsParent();
    $parent = getRoleOfLoggedInUser();
    expect($parent)->toBeString("parent");
    //
    loginAsStudent();
    $student = getRoleOfLoggedInUser();
    expect($student)->toBeString("student");
    //
    loginAsTutor();
    $tutor = getRoleOfLoggedInUser();
    expect($tutor)->toBeString("tutor");
});

it('return family code from id',function (){
    $parent = ParentUser::factory()->create();
    $code = getFamilyCodeFromId($parent->id);
    expect($code)->toEqual($parent->id);

    $code = getFamilyCodeFromId("");
    expect($code)->toEqual("");
});

it('return student tutoring package code from Id and wise versa',function (){
    $studentTutoringPackage = StudentTutoringPackage::factory()->create();
    $code = getStudentTutoringPackageCodeFromId($studentTutoringPackage->id);
    expect($code)->toEqual(StudentTutoringPackage::PREFIX_START.$studentTutoringPackage->id);
    $id = getOriginalStudentTutoringPackageIdFromCode($code);
    expect($id)->toEqual($studentTutoringPackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($studentTutoringPackage->id);
});

it('return original monthly invoice package id from code and wise versa',function (){
    $monthlyInvoicePackage = MonthlyInvoicePackage::factory()->create();
    $code = getMonthlyInvoicePackageCodeFromId($monthlyInvoicePackage->id);
    expect($code)->toEqual(MonthlyInvoicePackage::PREFIX_START.$monthlyInvoicePackage->id);
    $id = getOriginalMonthlyInvoicePackageIdFromCode($code);
    expect($id)->toEqual($monthlyInvoicePackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($monthlyInvoicePackage->id);
});
it('return Package Code From student tutoring package Id and wise versa',function (){
    $studentTutoringPackage = \App\Models\StudentTutoringPackage::factory()->create();
    $code = getPackageCodeFromId(studentTutoringPackageId: $studentTutoringPackage->id);
    expect($code)->toEqual(\App\Models\StudentTutoringPackage::PREFIX_START.$studentTutoringPackage->id);
    $id = getOriginalPackageIdFromCode($code);
    expect($id)->toEqual($studentTutoringPackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($studentTutoringPackage->id);
});

it('return Package Code From monthly invoice package Id and wise versa',function (){
    $monthlyInvoicePackage = MonthlyInvoicePackage::factory()->create();
    $code = getPackageCodeFromId(monthlyInvoicePackage: $monthlyInvoicePackage->id);
    expect($code)->toEqual(MonthlyInvoicePackage::PREFIX_START.$monthlyInvoicePackage->id);
    $id = getOriginalPackageIdFromCode($code);
    expect($id)->toEqual($monthlyInvoicePackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($monthlyInvoicePackage->id);
});
it('return Package Code From student tutoring package Model And Id',function (){
    $studentTutoringPackage = StudentTutoringPackage::factory()->create();
    $code = getPackageCodeFromModelAndId(StudentTutoringPackage::class,$studentTutoringPackage->id);
    expect($code)->toEqual(StudentTutoringPackage::PREFIX_START.$studentTutoringPackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($studentTutoringPackage->id);
});

it('return Package Code From monthly invoice package Model And Id',function (){
    $monthlyInvoicePackage = MonthlyInvoicePackage::factory()->create();
    $code = getPackageCodeFromModelAndId(MonthlyInvoicePackage::class,$monthlyInvoicePackage->id);
    expect($code)->toEqual(MonthlyInvoicePackage::PREFIX_START.$monthlyInvoicePackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($monthlyInvoicePackage->id);
});
it('return Package Code From student tutoring package Model ',function (){
    $studentTutoringPackage = StudentTutoringPackage::factory()->create();
    $code = getPackageCodeFromModel($studentTutoringPackage);
    expect($code)->toEqual(StudentTutoringPackage::PREFIX_START.$studentTutoringPackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($studentTutoringPackage->id);
});

it('return Package Code From monthly invoice package Model',function (){
    $monthlyInvoicePackage = MonthlyInvoicePackage::factory()->create();
    $code = getPackageCodeFromModel($monthlyInvoicePackage);
    expect($code)->toEqual(MonthlyInvoicePackage::PREFIX_START.$monthlyInvoicePackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($monthlyInvoicePackage->id);
});
it('return Package Code From student tutoring package Type And Id',function (){
    $studentTutoringPackage = StudentTutoringPackage::factory()->create();
    $code = getPackageCodeFromTypeAndId('s',$studentTutoringPackage->id);
    expect($code)->toEqual(StudentTutoringPackage::PREFIX_START.$studentTutoringPackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($studentTutoringPackage->id);
});
it('return Package Code From monthly invoice package Type And Id',function (){
    $monthlyInvoicePackage = MonthlyInvoicePackage::factory()->create();
    $code = getPackageCodeFromTypeAndId('m',$monthlyInvoicePackage->id);
    expect($code)->toEqual(MonthlyInvoicePackage::PREFIX_START.$monthlyInvoicePackage->id);
    $packageId = getPackageIdFromCode($code);
    expect($packageId)->toEqual($monthlyInvoicePackage->id);
});
it('return Invoice Code From Id',function (){
    $studentTutoringPackage = StudentTutoringPackage::factory()->create();
    $invoice = Invoice::factory([
        'invoiceable_type' => StudentTutoringPackage::class,
        'invoiceable_id' => $studentTutoringPackage->id,
        'amount_paid' => 0,
        'paid_by_modal' => null,
        'status' => true,
        'paid_by_id' => null
    ])->create();
    $code = getInvoiceCodeFromId($invoice->id);
    expect($code)->toEqual(Invoice::PREFIX_START.$invoice->id);
});
it('calculate Price From Hours And Hourly Rate Without Discount',function (){
    $hours = 10.25;
    $hourlyRate = 12.75;
    $price = getPriceFromHoursAndHourlyWithoutDiscount($hourlyRate,$hours);
    $originalPrice = formatAmountWithCurrency(130.6875);
    expect($price)->toEqual($originalPrice);
});

it('format amount with currency and wise versa',function (){
    $amount = 100;
    $formattedAmount = formatAmountWithCurrency($amount);
    expect($formattedAmount)->toEqual('$100.00');
    $originalAmount = cleanAmountWithCurrencyFormat($formattedAmount);
    expect($originalAmount)->toEqual($amount);
});
it('calculate Price From Hours And Hourly Rate With flat Discount',function (){
    $hours = 10.25;
    $hourlyRate = 12.75;
    $discount = 10.15;
    $price = getPriceFromHoursAndHourlyWithDiscount($hourlyRate,$hours,$discount,StudentTutoringPackage::FLAT_DISCOUNT);
    $afterDiscountedPrice = formatAmountWithCurrency((130.6875-10.15));
    expect($price)->toEqual($afterDiscountedPrice);
    $discountedPrice = getDiscountedAmount($hourlyRate,$hours,$discount,StudentTutoringPackage::FLAT_DISCOUNT);
    expect($discountedPrice)->toEqual(formatAmountWithCurrency(10.15));
});
it('calculate Price From Hours And Hourly Rate With percentage Discount',function (){
    $hours = 10.25;
    $hourlyRate = 12.75;
    $discount = 10.15;
    $price = getPriceFromHoursAndHourlyWithDiscount($hourlyRate,$hours,$discount,StudentTutoringPackage::PERCENTAGE_DISCOUNT);
    $discountedPrice = formatAmountWithCurrency((130.6875)-((130.6875/100)*10.15));
    expect($price)->toEqual($discountedPrice);
    $discountedPrice = getDiscountedAmount($hourlyRate,$hours,$discount,StudentTutoringPackage::PERCENTAGE_DISCOUNT);
    expect($discountedPrice)->toEqual(formatAmountWithCurrency(((130.6875/100)*10.15)));
});
it('return Yes No string from value',function (){

    $response = booleanToYesNo(true);
    expect($response)->toEqual('Yes');
    $response = booleanToYesNo('true');
    expect($response)->toEqual('Yes');
    $response = booleanToYesNo(1);
    expect($response)->toEqual('Yes');
    $response = booleanToYesNo('1');
    expect($response)->toEqual('Yes');
    $response = booleanToYesNo(false);
    expect($response)->toEqual('No');
    $response = booleanToYesNo('false');
    expect($response)->toEqual('No');
    $response = booleanToYesNo(0);
    expect($response)->toEqual('No');
    $response = booleanToYesNo('0');
    expect($response)->toEqual('No');
});

it('return boolean from yes no string',function (){
    $response = yesNoToBoolean('Yes');
    expect($response)->toEqual(true);
    $response = yesNoToBoolean('yes');
    expect($response)->toEqual(true);
    $response = yesNoToBoolean('No');
    expect($response)->toEqual(false);
    $response = yesNoToBoolean('no');
    expect($response)->toEqual(false);
});
it('return Tutor Hourly Rate For Student Tutoring Package',function (){
    $studentTutoringPackage = StudentTutoringPackage::factory(['tutor_hourly_rate'=>null])->create();
    $tutor = Tutor::factory()->create();
    $studentTutoringPackage->tutors()->sync($tutor->id);
    $tutorHourlyRate = getTutorHourlyRateForStudentTutoringPackage($studentTutoringPackage);
    expect($tutorHourlyRate)->toEqual($tutor->hourly_rate);

    $studentTutoringPackage = StudentTutoringPackage::factory(['tutor_hourly_rate'=>50])->create();
    $tutor1 = Tutor::factory()->create();
    $studentTutoringPackage->tutors()->sync($tutor1->id);
    $tutorHourlyRate = getTutorHourlyRateForStudentTutoringPackage($studentTutoringPackage);
    expect($tutorHourlyRate)->toEqual(50);

    $studentTutoringPackage = StudentTutoringPackage::factory(['tutor_hourly_rate'=>50])->create();
    $studentTutoringPackage->tutors()->sync([$tutor->id,$tutor1->id]);
    $tutorHourlyRate = getTutorHourlyRateForStudentTutoringPackage($studentTutoringPackage);
    expect($tutorHourlyRate)->toEqual(50);

});
it('return Tutor Hourly Rate For Monthly Invoice Package',function (){
    $monthlyInvoicePackage = MonthlyInvoicePackage::factory(['tutor_hourly_rate'=>40])->create();
    $tutor = Tutor::factory()->create();
    $monthlyInvoicePackage->tutors()->sync($tutor->id);
    $tutorHourlyRate = getTutorHourlyRateForMonthlyInvoicePackage($monthlyInvoicePackage,$tutor);
    expect($tutorHourlyRate)->toEqual(40);
    $tutorHourlyRate = getTutorHourlyRateForMonthlyInvoicePackage($monthlyInvoicePackage,$tutor->id);
    expect($tutorHourlyRate)->toEqual(40);
});
