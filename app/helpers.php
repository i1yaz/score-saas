<?php

use App\Models\ParentUser;
use Illuminate\Http\UploadedFile;

if (! function_exists('booleanSelect')) {
    function booleanSelect($value): string
    {
        return $value == 1 ? 'yes' : 'no';
    }
}
if (! function_exists('getRoleDescriptionOfLoggedInUser')) {
    function getRoleDescriptionOfLoggedInUser(): string
    {
        if (Auth::user()->hasRole('super-admin')) {
            return 'Super Admin';
        }
        if (Auth::user()->hasRole('admin')) {
            return 'Admin';
        }
        if (Auth::user()->hasRole('student')) {
            return 'Student';
        }
        if (Auth::user()->hasRole('parent')) {
            return 'Parent';
        }
        if (Auth::user()->hasRole('tutor')) {
            return 'Tutor';
        }

        return '';
    }
}
if (! function_exists('getRoleOfLoggedInUser')) {
    function getRoleOfLoggedInUser(): string
    {
        if (Auth::user()->hasRole('super-admin')) {
            return 'super-admin';
        }
        if (Auth::user()->hasRole('admin')) {
            return 'admin';
        }
        if (Auth::user()->hasRole('student')) {
            return 'student';
        }
        if (Auth::user()->hasRole('parent')) {
            return 'parent';
        }
        if (Auth::user()->hasRole('tutor')) {
            return 'tutor';
        }

        return '';
    }
}
if (! function_exists('getFamilyCodeFromId')) {
    function getFamilyCodeFromId($id): string
    {
        return $id + ParentUser::FAMILY_CODE_START;
    }
}

if (! function_exists('storeFile')) {
    function storeFile(string $path, File|UploadedFile $file, string $name = null): string
    {
        if (! empty($name)) {
            return Storage::putFileAs($path, $file, $name);

        }

        return Storage::putFile($path, $file);

    }
}
if (! function_exists('getFile')) {
    function getFile($id): string
    {

    }
}
if (! function_exists('deleteFile')) {
    function deleteFile(string|array $files): void
    {
        Storage::delete($files);
    }
}
