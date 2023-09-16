<?php


use App\Models\ParentUser;

if (! function_exists('booleanSelect')) {
    function booleanSelect($value):string
    {
        return $value==1?'yes':'no';
    }
}
if (! function_exists('getRoleOfLoggedInUser')) {
        function getRoleOfLoggedInUser():string
    {
                if (Auth::user()->hasRole('super-admin')){
                        return 'Super Admin';
        }
        if (Auth::user()->hasRole('admin')){
                        return 'Admin';
        }
        if (Auth::user()->hasRole('student')){
                        return 'Student';
        }
        if (Auth::user()->hasRole('parent')){
                        return 'Parent';
        }
        return "";
    }
}
if (! function_exists('getFamilyCodeFromId')) {
    function getFamilyCodeFromId($id):string
    {
        return $id + ParentUser::FAMILY_CODE_START;
    }
}
