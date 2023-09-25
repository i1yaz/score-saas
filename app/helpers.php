<?php

use App\Models\Invoice;
use App\Models\ParentUser;
use App\Models\StudentTutoringPackage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        if (!empty($id)){
            return $id + ParentUser::FAMILY_CODE_START;
        }
        return '';
    }
}

if (! function_exists('getStudentTutoringPackageCodeFromId')) {
    function getStudentTutoringPackageCodeFromId($id): string
    {
        return StudentTutoringPackage::PREFIX_START.($id + StudentTutoringPackage::CODE_START);
    }
}
if (!function_exists('getInvoiceCodeFromId')){
    function getInvoiceCodeFromId($id): string
    {
        return Invoice::PREFIX_START.($id + Invoice::ID_START);
    }}

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
        return '';
    }
}
if (! function_exists('deleteFile')) {
    function deleteFile(string|array $files): void
    {
        Storage::delete($files);
    }
}
if (! function_exists('getPriceFromHoursAndHourlyWithoutDiscount')) {
    /**
     * Get price from hourly rate and hours
     */
    function getPriceFromHoursAndHourlyWithoutDiscount(float $hourlyRate, float $hours): string
    {
        $price = ($hourlyRate * $hours);

        return formatAmountWithCurrency($price);
    }
}

if (! function_exists('getPriceFromHoursAndHourlyWithDiscounts')) {
    /**
     * Get price from hourly rate and hours
     */
    function getPriceFromHoursAndHourlyWithDiscount(float $hourlyRate, float $hours, float $discount = 1, int $discountType = 1): string
    {
        $price = ($hourlyRate * $hours);

        if ($discountType == StudentTutoringPackage::FLAT_DISCOUNT) {
            return formatAmountWithCurrency(($price - $discount));
        }
        if ($discountType == StudentTutoringPackage::PERCENTAGE_DISCOUNT) {
            return formatAmountWithCurrency($price - ($price * $discount) / 100);
        }

        return formatAmountWithCurrency($price);

    }
}

if (! function_exists('getDiscountedAmount')) {
    /**
     * Calculate the discounted amount based on the hourly rate, hours, and discount type.
     *
     * @param  float  $hourlyRate The hourly rate for the service.
     * @param  float  $hours The number of hours for the service.
     * @param  float  $discount The discount amount (default = 0).
     * @param  int  $discountType The type of discount (default = 0).
     * @return string The discounted amount as a formatted string.
     */
    function getDiscountedAmount(float $hourlyRate, float $hours, float $discount = 0, int $discountType = 1): string
    {
        if ($discountType == StudentTutoringPackage::FLAT_DISCOUNT) {
            return formatAmountWithCurrency($discount);
        }
        $price = ($hourlyRate * $hours);
        if ($discountType == StudentTutoringPackage::PERCENTAGE_DISCOUNT) {
            return formatAmountWithCurrency(($price * $discount) / 100);
        }

        return formatAmountWithCurrency(0);
    }
}
if (! function_exists('formatAmountWithCurrency')) {
    /**
     * Formats a given float number into a string with a currency Sign.
     *
     * @param  float  $amount The float number to be formatted.
     * @return string The formatted number as a string.
     */
    function formatAmountWithCurrency(float $amount, $decimals = 2): string
    {
        return '$'.number_format($amount, 2, '.', '');
    }
}
if (!function_exists('cleanAmountWithCurrencyFormat')){
    function cleanAmountWithCurrencyFormat(string $amount): string
    {
        return str_replace('$', '', $amount);
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date): string
    {
        return date('m/d/Y', strtotime($date));
    }
}

if (!function_exists('getInvoiceTypeFromClass')){
    function getInvoiceTypeFromClass($type): string
    {
        if ($type == StudentTutoringPackage::class) {
            return 'Tutoring Package';
        }
    }
}
if (!function_exists('getInvoiceStatusFromId')){
    /**
     * Get the status of an invoice based on its ID.
     *
     * @param int $status The ID of the invoice status.
     * @return string The description of the invoice status.
     */
    function getInvoiceStatusFromId(int $status): string
    {
        // Check the status ID and return the corresponding description
        return match ($status) {
            Invoice::DRAFT => '<span class="badge badge-secondary">Draft</span>',
            Invoice::PENDING => '<span class="badge badge-warning">Pending</span>',
            Invoice::PARTIAL_PAYMENT => '<span class="badge badge-info">Partially Paid</span>',
            Invoice::PAID => '<span class="badge badge-success">Paid</span>',
            Invoice::VOID => '<span class="badge badge-info">Void</span>',
            default => throw new InvalidArgumentException('Invalid invoice status ID'),
        };
    }
}

if (!function_exists('getRemainingAmountFromTotalAndPaidAmount')) {
    function getRemainingAmountFromTotalAndPaidAmount(float $total, float $paid): string
    {
        return formatAmountWithCurrency($total - $paid);
    }
}
