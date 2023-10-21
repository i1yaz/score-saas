<?php

use App\Models\ParentUser;
use Database\Seeders\DatabaseSeeder;

it('return yes or no string',function (){
    expect(booleanSelect(1))->toBeString("yes");
    expect(booleanSelect(0))->toBeString("no");
    expect(booleanSelect(3))->toBeString("no");
});

it('shows role description of logged in User',function (){
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

it('shows the role of logged in user',function (){
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

it('shows family code from id',function (){
    $parent = ParentUser::factory()->create();
    $code = getFamilyCodeFromId($parent->id);
    expect($code)->toEqual($parent->id);

    $code = getFamilyCodeFromId("");
    expect($code)->toEqual("");
});
