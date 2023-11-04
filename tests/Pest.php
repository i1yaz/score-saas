<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Models\ParentUser;
use App\Models\Student;
use App\Models\User;

uses(
    Tests\TestCase::class,
    \Illuminate\Foundation\Testing\LazilyRefreshDatabase::class,
)->in('Unit', 'Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function loginAsSuperAdmin($user = null)
{
    return test()->actingAs($user ?? User::first());
}

function loginAsAdmin()
{
    if (empty($user)) {
        $user = User::factory()->create();
        $user->addRole('admin');
    }

    return test()->actingAs($user);
}
function loginAsParent($user = null)
{
    if (empty($user)) {
        $user = ParentUser::factory()->create();
        $user->addRole('parent');
    }

    return test()->actingAs($user);
}
function loginAsStudent($user = null)
{
    if (empty($user)) {
        $user = Student::factory()->create();
        $user->addRole('student');
    }

    return test()->actingAs($user);
}
function loginAsTutor($user = null)
{
    if (empty($user)) {
        $user = \App\Models\Tutor::factory()->create();
        $user->addRole('tutor');
    }

    return test()->actingAs($user);
}
