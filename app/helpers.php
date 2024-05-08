<?php

use App\Helpers\MonthlyInstallments;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\MonthlyInvoicePackage;
use App\Models\NonInvoicePackage;
use App\Models\ParentUser;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use App\Models\Tax;
use App\Models\Tutor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\SubscriptionItem;

if (! function_exists('booleanSelect')) {
    function booleanSelect($value): string
    {
        return $value == 1 ? 'yes' : 'no';
    }
}
if (! function_exists('getRoleDescriptionOfLoggedInUser')) {
    function getRoleDescriptionOfLoggedInUser(): string
    {
        if (Auth::check()) {

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
            if (Auth::user()->hasRole('client')) {
                return 'Client';
            }
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
        if (! empty($id)) {
            return $id;
        }

        return '';
    }
}

if (! function_exists('getStudentTutoringPackageCodeFromId')) {
    function getStudentTutoringPackageCodeFromId($id): string
    {
        return StudentTutoringPackage::PREFIX_START.($id);
    }
}

if (! function_exists('getOriginalStudentTutoringPackageIdFromCode')) {
    function getOriginalStudentTutoringPackageIdFromCode($code): string
    {
        return str_replace(StudentTutoringPackage::PREFIX_START, '', $code);
    }
}
if (! function_exists('getOriginalMonthlyInvoicePackageIdFromCode')) {
    function getOriginalMonthlyInvoicePackageIdFromCode($code): string
    {
        return str_replace(MonthlyInvoicePackage::PREFIX_START, '', $code);
    }
}
if (! function_exists('getOriginalPackageIdFromCode')) {
    function getOriginalPackageIdFromCode($code): string
    {
        return str_replace([StudentTutoringPackage::PREFIX_START, MonthlyInvoicePackage::PREFIX_START], '', $code);
    }
}
if (! function_exists('getPackageCodeFromId')) {
    function getPackageCodeFromId(?int $studentTutoringPackageId = null, ?int $monthlyInvoicePackage = null): string
    {
        if (! empty($studentTutoringPackageId)) {
            return getStudentTutoringPackageCodeFromId($studentTutoringPackageId);
        }
        if (! empty($monthlyInvoicePackage)) {
            return getMonthlyInvoicePackageCodeFromId($monthlyInvoicePackage);
        }

        return '';
    }
}
if (! function_exists('getPackageCodeFromModelAndId')) {
    function getPackageCodeFromModelAndId(string $type, $id): string
    {
        if ($type === StudentTutoringPackage::class) {
            return getStudentTutoringPackageCodeFromId($id);
        }
        if ($type === MonthlyInvoicePackage::class) {
            return getMonthlyInvoicePackageCodeFromId($id);
        }
        if ($type === NonInvoicePackage::class) {
            return getNonInvoicePackageCodeFromId($id);
        }

        return '';

    }

    function getNonInvoicePackageCodeFromId($id): string
    {
        return NonInvoicePackage::PREFIX_START.($id);
    }
}
if (! function_exists('getPackageCodeFromModel')) {
    function getPackageCodeFromModel(Model $model): string
    {

        if ($model instanceof StudentTutoringPackage) {
            return getStudentTutoringPackageCodeFromId($model->id);
        }
        if ($model instanceof MonthlyInvoicePackage) {
            return getMonthlyInvoicePackageCodeFromId($model->id);
        }

        return '';
    }
}
if (! function_exists('getPackageCodeFromTypeAndId')) {
    function getPackageCodeFromTypeAndId(string $type, int $id): string
    {

        $type = strtolower($type);
        if ($type == 's') {
            return getStudentTutoringPackageCodeFromId($id);
        }
        if ($type == 'm') {
            return getMonthlyInvoicePackageCodeFromId($id);
        }

        return '';
    }
}
if (! function_exists('getPackageIdFromCode')) {
    function getPackageIdFromCode($code): string
    {
        if (str_contains($code, StudentTutoringPackage::PREFIX_START)) {
            return getOriginalStudentTutoringPackageIdFromCode($code);
        }
        if (str_contains($code, MonthlyInvoicePackage::PREFIX_START)) {
            return getOriginalPackageIdFromCode($code);
        }

        return '';
    }
}

if (! function_exists('getInvoiceCodeFromId')) {
    function getInvoiceCodeFromId($id): string
    {
        return Invoice::PREFIX_START.($id);
    }
}
if (! function_exists('getMonthlyInvoicePackageCodeFromId')) {
    function getMonthlyInvoicePackageCodeFromId(int $id): string
    {
        return MonthlyInvoicePackage::PREFIX_START.($id);
    }
}

if (! function_exists('storeFile')) {
    function storeFile(string $path, File|UploadedFile $file, ?string $name = null): string
    {
        $path = getCurrentTenant('/').$path;
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

if (! function_exists('getPriceFromHoursAndHourlyWithDiscount')) {
    /**
     * Get price from hourly rate and hours
     */
    function getPriceFromHoursAndHourlyWithDiscount(float $hourlyRate, ?float $hours, int $discount = 1, ?int $discountType = 1): string
    {
        if (is_null($hours)) {
            $hours = 0;
        }
        if (is_null($discountType)) {
            $discountType = 1;
        }
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
     * @param  float  $hourlyRate  The hourly rate for the service.
     * @param  float  $hours  The number of hours for the service.
     * @param  float  $discount  The discount amount (default = 0).
     * @param  int  $discountType  The type of discount (default = 0).
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
     * @param  float|null  $amount  The float number to be formatted.
     * @param  int  $decimals
     * @return string The formatted number as a string.
     */
    function formatAmountWithCurrency(?float $amount, $decimals = 2): string
    {
        if (is_null($amount)) {
            $amount = 0;
        }

        return getCurrencySymbol().formatAmountWithoutCurrency($amount, $decimals);
    }
}
if (! function_exists('formatAmountWithoutCurrency')) {
    function formatAmountWithoutCurrency(float $amount, $decimals = 2): string
    {
        return number_format($amount, 2, '.', '');
    }
}

if (! function_exists('getCurrencySymbol')) {

    function getCurrencySymbol(): mixed
    {
        return '$';
        //        static $currencySymbol;
        //        /** @var Setting $currencySymbol */
        //        if (empty($currencySymbol)) {
        //            $currencySymbol = Currency::where('id', getSettingValue('current_currency'))->pluck('icon')->first();
        //        }
        //
        //        return $currencySymbol;
    }
}
if (! function_exists('cleanAmountWithCurrencyFormat')) {
    function cleanAmountWithCurrencyFormat(string $amount): string
    {
        return str_replace('$', '', $amount);
    }
}

if (! function_exists('formatDate')) {
    function formatDate($date): string
    {
        if (empty($date)) {
            return '';
        }

        return date('m/d/Y', strtotime($date));
    }
}
if (! function_exists('formatTime')) {
    function formatTime($time): string
    {
        if (empty($time)) {
            return '';
        }

        return date('H:i', strtotime($time));
    }
}

if (! function_exists('getInvoiceTypeFromClass')) {
    function getInvoiceTypeFromClass($type, $slug = false): string
    {
        if ($type == StudentTutoringPackage::class) {
            $name = 'Tutoring Package';
            if ($slug) {
                $name = Str::slug($name);
            }

            return $name;
        }
        if ($type == MonthlyInvoicePackage::class) {
            $name = 'Monthly Invoice Package';
            if ($slug) {
                $name = Str::slug($name);
            }

            return $name;
        }
        if ($type == NonInvoicePackage::class) {
            $name = 'Non Package Invoice';
            if ($slug) {
                $name = Str::slug($name);
            }

            return $name;
        }
        return "No invoice type for type {$type} and slug {$slug}";
    }
}
if (! function_exists('getInvoiceStatusFromId')) {
    /**
     * Get the status of an invoice based on its ID.
     *
     * @param  int  $status  The ID of the invoice status.
     * @return string The description of the invoice status.
     */
    function getInvoiceStatusFromId(int $status, $subscriptionType = StudentTutoringPackage::class, $isActive = false, $subscriptionId = '', $startDate = ''): string
    {
        if ($subscriptionType == MonthlyInvoicePackage::class) {
            if (! empty($subscriptionId) && $isActive) {
                return '<span class="badge badge-success">Subscribed</span>';
            }
            if (! empty($subscriptionId) && ! $isActive) {
                return '<span class="badge badge-info">Unsubscribed/Completed</span>';
            }
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->endOfDay();
            if (empty($subscriptionId) && $startDate->isPast()) {
                return '<span class="badge badge-danger">Expired</span>';
            }
            if (empty($subscriptionId)) {
                return '<span class="badge badge-warning">Pending for subscription</span>';
            }
        }

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
if (!function_exists('getSuccessErrorBadge')){
    function getSuccessErrorBadge(bool $status,$successMessage,$errorMessage): string
    {
        return match ($status) {
            true => "<span class='badge badge-success'>".$successMessage."</span>",
            false => "<span class='badge badge-danger'>".$errorMessage."</span>",
        };
    }
}
if (! function_exists('getRemainingAmountFromTotalAndPaidAmount')) {
    function getRemainingAmountFromTotalAndPaidAmount(float $total, float $paid): string
    {
        return formatAmountWithCurrency($total - $paid);
    }
}

if (! function_exists('booleanToYesNo')) {
    function booleanToYesNo($value)
    {
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        if ($value === true) {
            return 'Yes';
        }
        if ($value === false) {
            return 'No';
        }
    }
}
if (! function_exists('yesNoToBoolean')) {
    function yesNoToBoolean($value)
    {
        $value = strtolower($value);
        if ($value == 'yes') {
            return true;
        }
        if ($value == 'no') {
            return false;
        }
    }
}

if (! function_exists('getHexColors')) {
    function getHexColors($i): string
    {
        if ($i > 9) {
            $i = $i % 10;
        }
        $colors = [
            0 => '#0074D9', // Blue
            1 => '#6F7378',  // Gray
            2 => '#FF4136', // Red
            3 => '#2ECC40', // Green
            4 => '#FF851B', // Orange
            5 => '#F012BE', // Purple
            6 => '#39CCCC', // Teal
            7 => '#01FF70', // Lime
            8 => '#85144b', // Maroon
            9 => '#FFDC00', // Yellow
        ];

        return $colors[$i];
    }
}
if (! function_exists('getTutorHourlyRateForStudentTutoringPackage')) {
    function getTutorHourlyRateForStudentTutoringPackage(StudentTutoringPackage $studentTutoringPackage, Tutor|int|null $tutor = null): string
    {
        if (empty($studentTutoringPackage->tutor_hourly_rate)) {
            if ($tutor) {
                if (! $tutor instanceof Tutor) {
                    $tutor = Tutor::find($tutor);
                }

                return $tutor->hourly_rate;
            }
            $firstTutor = $studentTutoringPackage->tutors()->first();
            $hourlyRate = $firstTutor->hourly_rate;
        } else {
            $hourlyRate = $studentTutoringPackage->tutor_hourly_rate;
        }

        return $hourlyRate;
    }
}
if (! function_exists('getTutorHourlyRateForMonthlyInvoicePackage')) {
    function getTutorHourlyRateForMonthlyInvoicePackage(MonthlyInvoicePackage $monthlyInvoicePackage, Tutor|int|null $tutor = null): string
    {
        if (empty($monthlyInvoicePackage->tutor_hourly_rate)) {
            if ($tutor) {
                if (! $tutor instanceof Tutor) {
                    $tutor = Tutor::find($tutor);
                }

                return $tutor->hourly_rate;
            }
            $firstTutor = $monthlyInvoicePackage->tutors()->first();
            $hourlyRate = $firstTutor->hourly_rate;
        } else {
            $hourlyRate = $monthlyInvoicePackage->tutor_hourly_rate;
        }

        return $hourlyRate;
    }
}
if (! function_exists('getTotalChargedTimeFromStudentTutoringPackageInSeconds')) {
    function getTotalChargedTimeFromStudentTutoringPackageInSeconds(StudentTutoringPackage $studentTutoringPackage): float|int
    {
        $sessions = \App\Models\Session::where('student_tutoring_package_id', $studentTutoringPackage->id)->get();
        $totalChargedTime = 0;
        foreach ($sessions as $session) {
            $totalChargedTime += getTotalChargedTimeInSecondsFromSession($session);

        }

        return $totalChargedTime;
    }
}
if (! function_exists('getTotalChargedTimeFromMonthlyInvoicePackageInSeconds')) {
    function getTotalChargedTimeFromMonthlyInvoicePackageInSeconds(MonthlyInvoicePackage $monthlyInvoicePackage, $sessions = null, string|Carbon|null $month = null): float|int
    {
        if ($sessions == 'all') {
            $sessions = \App\Models\Session::where('monthly_invoice_package_id', $monthlyInvoicePackage->id)
                ->get();
        }
        if (empty($sessions)) {
            $sessions = \App\Models\Session::where('monthly_invoice_package_id', $monthlyInvoicePackage->id)
                ->whereMonth('scheduled_date', $month ?? Carbon::now()->month)
                ->get();
        }
        $totalChargedTime = 0;
        foreach ($sessions as $session) {
            $totalChargedTime += getTotalChargedTimeInSecondsFromSession($session);

        }

        return $totalChargedTime;
    }
}
if (! function_exists('getTotalInvoicePriceForMonthlyInvoicePackageFromSessions')) {
    function getTotalInvoicePriceForMonthlyInvoicePackageFromSessions(MonthlyInvoicePackage $monthlyInvoicePackage, $sessions): string
    {
        $totalChargedTime = getTotalChargedTimeFromMonthlyInvoicePackageInSeconds($monthlyInvoicePackage, sessions: $sessions);
        $hourlyRate = $monthlyInvoicePackage->hourly_rate;
        $totalChargedTime = $totalChargedTime * ($hourlyRate / 3600);

        return formatAmountWithCurrency($totalChargedTime);
    }
}
if (! function_exists('getTotalInvoicePriceFromMonthlyInvoicePackage')) {
    function getTotalInvoicePriceFromMonthlyInvoicePackage(MonthlyInvoicePackage $monthlyInvoicePackage): string
    {
        $totalChargedTime = getTotalChargedTimeFromMonthlyInvoicePackageInSeconds($monthlyInvoicePackage, 'all');
        $hourlyRate = $monthlyInvoicePackage->hourly_rate;
        $totalChargedTime = getChargedAmountFromSecondsAndHourlyRate($totalChargedTime, $hourlyRate); // $totalChargedTime * ($hourlyRate / 3600);

        return formatAmountWithCurrency($totalChargedTime);
    }
}
if (! function_exists('getChargedAmountFromSecondsAndHourlyRate')) {
    function getChargedAmountFromSecondsAndHourlyRate($seconds, $hourlyRate)
    {
        $totalHours = floor($seconds / 3600);
        $totalMinutes = floor(($seconds / 60) % 60);
        $perMinuteRate = formatAmountWithoutCurrency($hourlyRate / 60);
        $hourlyChargedAmount = $totalHours * $hourlyRate;
        $minutesChargedAmount = $totalMinutes * $perMinuteRate;

        return formatAmountWithoutCurrency($hourlyChargedAmount + $minutesChargedAmount);
    }
}
if (! function_exists('getChargedAmountFromMinutesAndHourlyRate')) {
    function getChargedAmountFromMinutesAndHourlyRate($minutes, $hourlyRate)
    {
        return getChargedAmountFromSecondsAndHourlyRate($minutes * 60, $hourlyRate);
    }
}
if (! function_exists('getTotalChargedTimeOfSessionFromSessionInSeconds')) {
    function getTotalChargedTimeOfSessionFromSessionInSeconds(App\Models\Session $session): float|int
    {
        if ($session->session_completion_code === \App\Models\Session::VOID_COMPLETION_CODE || $session->session_completion_code === \App\Models\Session::CANCELED_COMPLETION_CODE) {
            return 0;
        }
        $totalChargedTime = 0;
        $totalChargedTime += (strtotime($session->end_time) - strtotime($session->start_time));
        if ($session->session_completion_code === \App\Models\Session::PARTIAL_COMPLETION_CODE && filter_var($session->charge_missed_time, FILTER_VALIDATE_BOOLEAN) === true) {
            $totalChargedTime += chargeSessionMissedTimeInSeconds($session, $totalChargedTime);

        }

        return $totalChargedTime;
    }
}
if (! function_exists('getTotalChargedTimeInHoursSecondsMinutesFromSession')) {
    function getTotalChargedTimeInHoursSecondsMinutesFromSession(App\Models\Session $session): string
    {
        return formatTimeFromSeconds(getTotalChargedTimeInSecondsFromSession($session));
    }
}
if (! function_exists('getTotalChargedTimeInSecondsFromSession')) {
    function getTotalChargedTimeInSecondsFromSession(App\Models\Session $session): float|int
    {
        $totalChargedSessionTime = getTotalChargedSessionTimeFromSessionInSeconds($session);
        $totalChargedMissedTime = getTotalChargedMissedSessionTimeFromSessionInSeconds($session);

        return $totalChargedSessionTime + $totalChargedMissedTime;
    }
}
if (! function_exists('formatTimeFromSeconds')) {
    function formatTimeFromSeconds($seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;
        if ($seconds == 0) {
            return sprintf('%02dh:%02dm', $hours, $minutes);

        }

        return sprintf('%02dh:%02dm:%02ds', $hours, $minutes, $seconds);
    }
}
if (! function_exists('getTotalTutorPaymentForStudentTutoringPackage')) {
    function getTotalTutorPaymentForStudentTutoringPackage(StudentTutoringPackage $studentTutoringPackage): string
    {
        $totalChargedTimeInSeconds = getTotalChargedTimeFromStudentTutoringPackageInSeconds($studentTutoringPackage);
        if (! empty($studentTutoringPackage->tutor_hourly_rate)) {
            $hourlyRate = getTutorHourlyRateForStudentTutoringPackage($studentTutoringPackage);
            $hourlyRateInSeconds = $hourlyRate / 3600;

            return formatAmountWithCurrency($totalChargedTimeInSeconds * $hourlyRateInSeconds);
        }
        if ($hourlyRateInSeconds = ($studentTutoringPackage->tutors()->first()->hourly_rate / 3600) ?? 0) {
            return formatAmountWithCurrency($totalChargedTimeInSeconds * $hourlyRateInSeconds);
        }

        return formatAmountWithCurrency(0);
    }
}
if (! function_exists('getTotalTutorPaymentForMonthlyInvoicePackage')) {
    function getTotalTutorPaymentForMonthlyInvoicePackage(MonthlyInvoicePackage $monthlyInvoicePackage)
    {
        $totalChargedTimeInSeconds = getTotalChargedTimeFromMonthlyInvoicePackageInSeconds($monthlyInvoicePackage, 'all');
        if (! empty($monthlyInvoicePackage->tutor_hourly_rate)) {
            $hourlyRate = getTutorHourlyRateForMonthlyInvoicePackage($monthlyInvoicePackage);
            $hourlyRateInSeconds = $hourlyRate / 3600;

            return formatAmountWithCurrency($totalChargedTimeInSeconds * $hourlyRateInSeconds);
        }
    }
}
if (! function_exists('getTotalHours')) {
    function getTotalHours($session): float|int
    {
        $totalChargedTime = 0;
        $totalChargedTime += (strtotime($session->end_time) - strtotime($session->start_time)) / 3600;

        return $totalChargedTime;
    }
}
if (! function_exists('getSessionCodeFromId')) {
    function getSessionCodeFromId($id): string
    {
        return \App\Models\Session::PREFIX.($id);
    }
}
if (! function_exists('getTotalTutorChargedAmountFromSession')) {
    function getTotalTutorChargedAmountFromSession(App\Models\Session $session, $package): string
    {
        $totalChargedTimeInSeconds = getTotalChargedTimeInSecondsFromSession($session);
        if ($package instanceof StudentTutoringPackage) {
            $hourlyRate = getTutorHourlyRateForStudentTutoringPackage($package, $session->tutor_id);
        }
        if ($package instanceof MonthlyInvoicePackage) {
            $hourlyRate = getTutorHourlyRateForMonthlyInvoicePackage($package, $session->tutor_id);
        }

        $totalChargedTime = $totalChargedTimeInSeconds * ($hourlyRate / 3600);

        return formatAmountWithCurrency($totalChargedTime);
    }
}
if (! function_exists('chargeSessionMissedTimeInSeconds')) {
    function chargeSessionMissedTimeInSeconds(App\Models\Session $session, int $totalChargedTime = 0): int
    {
        $totalMissedTime = 0;
        if (((int) $session->session_completion_code === 2 && filter_var($session->charge_missed_time, FILTER_VALIDATE_BOOLEAN) === true) || (int) $session->session_completion_code === 3) {

            $scheduledDate = Carbon::createFromFormat('Y-m-d H:i:s', $session->scheduled_date)->format('m/d/Y');
            $sessionStart = $session->start_time;
            $sessionEnd = $session->end_time;

            $sessionStart = Carbon::createFromFormat('m/d/Y H:i:s', "$scheduledDate $sessionStart");
            $sessionEnd = Carbon::createFromFormat('m/d/Y H:i:s', "$scheduledDate $sessionEnd");

            $attendedStartTime = $session->attended_start_time;
            $attendedEndTime = $session->attended_end_time;
            if ($session->session_completion_code == 3) {
                $attendedStartTime = $attendedEndTime = $session->start_time;
            }
            $attendedStartTime = Carbon::createFromFormat('m/d/Y H:i:s', "$scheduledDate $attendedStartTime");
            $attendedEndTime = Carbon::createFromFormat('m/d/Y H:i:s', "$scheduledDate $attendedEndTime");
            // Calculate missed time
            $missedStart = $sessionStart->diffInSeconds($attendedStartTime);
            $missedEnd = $sessionEnd->diffInSeconds($attendedEndTime);

            // Calculate total missed time
            $totalMissedTime = $missedStart + $missedEnd;
            $totalMissedTime = $totalMissedTime - $totalChargedTime;
        }

        return $totalMissedTime;
    }
}
if (! function_exists('getTotalChargedSessionTimeFromSessionInSeconds')) {
    function getTotalChargedSessionTimeFromSessionInSeconds(App\Models\Session $session): float|int
    {
        $scheduledDate = Carbon::createFromFormat('Y-m-d H:i:s', $session->scheduled_date)->format('m/d/Y');
        $sessionEnd = $session->end_time;
        $sessionEnd = Carbon::createFromFormat('m/d/Y H:i:s', "$scheduledDate $sessionEnd");
        $now = Carbon::now();
        if ($now->lte($sessionEnd)) {
            return 0;
        }
        if ($session->session_completion_code == 3) {
            return 0;
        }
        if ($session->session_completion_code !== \App\Models\Session::PARTIAL_COMPLETION_CODE) {
            return getTotalChargedTimeOfSessionFromSessionInSeconds($session);
        }
        $totalChargedTime = 0;
        $totalChargedTime += (strtotime($session->attended_end_time) - strtotime($session->attended_start_time));

        return $totalChargedTime;
    }
}
if (! function_exists('isMissedTimeCharged')) {
    function isMissedTimeCharged(\App\Models\Session $session): bool
    {
        if ($session->session_completion_code == \App\Models\Session::PARTIAL_COMPLETION_CODE && filter_var($session->charge_missed_time, FILTER_VALIDATE_BOOLEAN) === true) {
            return true;
        }
        if ($session->session_completion_code == 3) {
            return true;
        }

        return false;
    }
}
if (! function_exists('getTotalChargedMissedSessionTimeFromSessionInSeconds')) {
    function getTotalChargedMissedSessionTimeFromSessionInSeconds(App\Models\Session $session): int
    {
        $scheduledDate = Carbon::createFromFormat('Y-m-d H:i:s', $session->scheduled_date)->format('m/d/Y');
        $sessionEnd = $session->end_time;
        $sessionEnd = Carbon::createFromFormat('m/d/Y H:i:s', "$scheduledDate $sessionEnd");
        $now = Carbon::now();
        if ($session->id == 5004) {
            //            dd($now->lte($sessionEnd)   );
        }
        if ($now->lte($sessionEnd)) {
            return 0;
        }
        if (in_array($session->session_completion_code, [4, 5]) || ($session->session_completion_code == 2 && filter_var($session->charge_missed_time, FILTER_VALIDATE_BOOLEAN) === false)) {
            return 0;
        }

        return chargeSessionMissedTimeInSeconds($session);
    }
}
if (! function_exists('isInputRequired')) {
    function isInputRequired(Model $model, $input): void
    {

    }
}
if (! function_exists('getPriceFromMonthlyInvoicePackage')) {
    function getPriceFromMonthlyInvoicePackage(MonthlyInvoicePackage $monthlyInvoicePackage)
    {
        \App\Models\Session::select(['id', 'monthly_invoice_package_id', 'start_time', 'end_time', 'scheduled_date', 'session_completion_code', 'attended_start_time', 'attended_end_time', 'charge_missed_time'])
            ->where('monthly_invoice_package_id', $monthlyInvoicePackage->id)->get();
    }
}
if (! function_exists('getDiscountedAmountOnSubtotal')) {
    function getDiscountedAmountOnSubtotal($discount_type, $discount, $amount): float|int
    {
        if ($discount_type == Invoice::FLAT_DISCOUNT) {
            return formatAmountWithoutCurrency($discount);
        }
        if ($discount_type == Invoice::PERCENTAGE_DISCOUNT) {
            return formatAmountWithoutCurrency((($amount) * $discount) / 100);
        }

        return formatAmountWithoutCurrency($amount);

    }
}
if (! function_exists('getTaxAmountForLine')) {
    function getTaxAmountForLine($taxes, $total, $key): float|int
    {
        $totalTax = 0;
        foreach ($taxes as $tax) {
            if (! empty($tax[$key])) {
                $tax = Tax::find($tax[$key]);
                $totalTax = $totalTax + (($total * $tax->value) / 100);
                $total = $total + $totalTax;
            }
        }

        return (float) $totalTax;
    }
}
if (! function_exists('getTaxIdsForLineInJson')) {
    function getTaxIdsForLineInJson($taxes, $key): bool|string
    {
        $taxIds = [];
        foreach ($taxes as $tax) {
            if (! empty($tax[$key])) {
                $taxIds[] = $tax[$key];
            }
        }

        return json_encode($taxIds);
    }
}
if (! function_exists('chargeTaxOnSubtotal')) {
    function chargeTaxOnSubtotal($taxes, $total): float|int
    {
        $totalTax = 0;
        foreach ($taxes as $tax) {
            $tax = Tax::find($tax);
            $totalTax = $totalTax + (($total * $tax->value) / 100);
            $total = $total + $totalTax;
        }

        return (float) $totalTax;
    }
}
if (! function_exists('getInvoiceCodeFromInvoiceableTypeAndId')) {
    function getInvoiceCodeFromInvoiceableTypeAndId(string $model, $id): string
    {
        if ($model === StudentTutoringPackage::class) {
            return getStudentTutoringPackageCodeFromId($id);
        }
        if ($model === MonthlyInvoicePackage::class) {
            return getMonthlyInvoicePackageCodeFromId($id);
        }
        if ($model === NonInvoicePackage::class) {
            return getNonInvoicePackageCodeFromId($id);
        }

        return '';

    }
}
if (! function_exists('setStripeApiKey')) {
    function setStripeApiKey($getClient = false)
    {
        $stripeSecretKey = config('services.stripe.secret_key');
        //        $stripeSecret = getSettingValue('stripe_secret');
        if (! $getClient) {
            isset($stripeSecret) ? Stripe::setApiKey($stripeSecret) : Stripe::setApiKey($stripeSecretKey);

        } else {
            return new \Stripe\StripeClient($stripeSecret ?? $stripeSecretKey);
        }
    }
}
if (! function_exists('getSettingValue')) {

    function getSettingValue($keyName): mixed
    {
        $key = 'setting'.'-'.$keyName;
        static $settingValues;

        if (isset($settingValues[$key])) {
            return $settingValues[$key];
        }
        /** @var Setting $setting */
        // $setting = Setting::where('key', '=', $keyName)->first();
        $settingValues[$key] = $setting->value;

        return $setting->value;
    }
}
if (! function_exists('getInvoiceCurrencyCode')) {
    function getInvoiceCurrencyCode(): string
    {
        return 'USD';
    }
}
if (! function_exists('getAuthModelFromGuard')) {
    function getAuthModelFromGuard(string $authGuard): string
    {
        if ($authGuard == 'web') {
            return User::class;
        }
        if ($authGuard == 'tutor') {
            return Tutor::class;
        }
        if ($authGuard == 'student') {
            return Student::class;
        }
        if ($authGuard == 'parent') {
            return ParentUser::class;
        }
        if ($authGuard == 'client') {
            return Client::class;
        }

    }
}

if (! function_exists('getRemainingAmount')) {
    function getRemainingAmount(Invoice $invoice)
    {
        if ($invoice->invoiceable_type == NonInvoicePackage::class) {
            $remainingAmount = $invoice->final_amount - $invoice->amount_paid;
            $remainingAmount = $remainingAmount + $invoice->amount_refunded;

            return formatAmountWithCurrency($remainingAmount);
        }
        if ($invoice->invoiceable_type == StudentTutoringPackage::class) {

            $hours = $invoice->hours;
            $hourly_rate = $invoice->tutoring_hourly_rate;
            $discount = $invoice->discount;
            $discount_type = $invoice->discount_type;
            $final_amount = cleanAmountWithCurrencyFormat(getPriceFromHoursAndHourlyWithDiscount($hourly_rate, $hours, $discount, $discount_type));
            $final_amount = $final_amount + $invoice->amount_refunded;

            return formatAmountWithCurrency($final_amount - $invoice->amount_paid);
        }
        if ($invoice->invoiceable_type == MonthlyInvoicePackage::class) {

            $hours = $invoice->hours;
            $hourly_rate = $invoice->tutoring_hourly_rate;
            $discount = $invoice->discount;
            $discount_type = $invoice->discount_type;
            $final_amount = cleanAmountWithCurrencyFormat(getPriceFromHoursAndHourlyWithDiscount($hourly_rate, $hours, $discount, $discount_type));
        }

        return 0;
    }
}
if (! function_exists('getPaymentGatewayNameFromId')) {
    function getPaymentGatewayNameFromId($id): string
    {
        if ($id == Payment::STRIPE) {
            return 'Stripe';
        }
        if ($id == Payment::PAYPAL) {
            return 'PayPal';
        }

        return '';
    }
}
if (!function_exists('getPaymentGateways')){
    function getPaymentGateways(): array
    {
        return [
            'stripe' => 'Stripe',
        ];
    }
}
if (! function_exists('getStripeCustomerIdFromUser')) {
    function getStripeCustomerIdFromUser(Authenticatable $user): string
    {
        if (! empty($user->stripe_customer_id)) {
            return $user->stripe_customer_id;
        } else {
            $customer = Customer::create([
                'name' => Auth::user()->fullName,
                'email' => Auth::user()->email,
            ]);
            $user->stripe_customer_id = $customer->id;
            $user->save();

            return $customer->id;
        }
    }
}
if (! function_exists('getCurrentMonthUsageFromMonthlyPackage')) {
    function getCurrentMonthUsageFromMonthlyPackage(MonthlyInvoicePackage $invoicePackage, $MonthlyInvoicePackageId = null)
    {
        $totalTimeInSeconds = getTotalChargedTimeFromMonthlyInvoicePackageInSeconds($invoicePackage);
        dd($totalTimeInSeconds);
    }
}
if (! function_exists('createUsageRecord')) {
    function createUsageRecord($subscriptionItemId, int $hours = 0, $action = 'increment')
    {
        SubscriptionItem::createUsageRecord($subscriptionItemId, [
            'quantity' => $hours,
            'action' => $action,
        ]);
    }
}

if (! function_exists('getFutureDueDate')) {
    function getFutureDueDate(Carbon $date): Carbon
    {
        if ($date->isPast()) {
            $due_date = $date->addHour();
            if ($due_date->isNextDay()) {
                $due_date = $due_date->endOfDay();
            }

            return getFutureDueDate($due_date);
        } else {
            $due_date = $date;
        }

        return $due_date;
    }
}

if (! function_exists('getCurrentTenant')) {
    function getCurrentTenant($slash): int|string
    {
        return 'tenant_1'.$slash;
    }
}
if (! function_exists('getLastThirtyDays')) {
    function getLastThirtyDays(): array
    {
        $today = Carbon::now();
        $lastThirtyDays = [];
        for ($i = 0; $i < 30; $i++) {
            $lastThirtyDays[] = $today->copy()->subDays($i)->format('d M');
        }

        return array_reverse($lastThirtyDays);
    }
}
if (! function_exists('getLastThirtyDaySessionCountDateWise')) {
    function getLastThirtyDaySessionCountDateWise(): array
    {

        $sessionCounts = Cache::rememberForever('session_count', function () {
            $today = Carbon::now()->toDateString();
            $thirtyDaysAgo = Carbon::now()->subDays(29)->toDateString();

            return \App\Models\Session::query()
                ->leftJoin(
                    DB::raw("(SELECT DISTINCT scheduled_date FROM sessions WHERE scheduled_date BETWEEN '{$thirtyDaysAgo}' AND '{$today}') AS dates"),
                    'sessions.scheduled_date',
                    '=',
                    'dates.scheduled_date'
                )
                ->selectRaw('Date(dates.scheduled_date) as session_date, COALESCE(COUNT(sessions.id), 0) as count')
                ->groupBy('session_date')
                ->orderBy('session_date', 'desc')
                ->get();
        });
        $sessionCounts = $sessionCounts->pluck('count', 'session_date')->toArray();
        $lastThirtyDays = [];
        $today = Carbon::now();
        for ($i = 0; $i < 30; $i++) {
            $lastThirtyDays[] = $today->copy()->subDays($i)->toDateString();
        }
        $result = [];
        foreach (array_reverse($lastThirtyDays) as $date) {
            $result[] = $sessionCounts[$date] ?? 0;
        }

        return $result;
    }
}
if (! function_exists('getWeeklySessionCount')) {
    function getWeeklySessionCount(array $allSessions)
    {
        $allSessions = array_reverse($allSessions);
        $count = 0;
        for ($i = 0; $i < 7; $i++) {
            $count += $allSessions[$i];
        }

        return $count;
    }
}
if (! function_exists('getTwelveMonthsName')) {
    function getTwelveMonthsName(): array
    {
        $months = [];
        $today = Carbon::now();
        for ($i = 0; $i < 12; $i++) {
            $months[] = $today->copy()->subMonths($i)->format('M');
        }

        return array_reverse($months);
    }
}
if (! function_exists('getOneYearEarning')) {
    function getOneYearEarning(): array
    {
        $earnings = Cache::rememberForever('earning_sum', function () {
            return Payment::query()
                ->selectRaw('SUBSTRING(UPPER(MONTHNAME(created_at)), 1, 3) as month, SUM(amount - amount_refunded) as earned_amount')
                ->whereBetween('created_at', [Carbon::now()->subMonths(11)->startOfMonth(), Carbon::now()->endOfMonth()])
                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
                ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
                ->get();
        });
        $earnings = $earnings->pluck('earned_amount', 'month')->toArray();
        $lastTwelveMonths = getTwelveMonthsName();
        $result = [];
        foreach ($lastTwelveMonths as $month) {
            $result[] = $earnings[strtoupper($month)] ?? 0;
        }

        return $result; //do not reverse because the months area already revered
    }
}
if (!function_exists('getInvoiceDueDate')){
    function getInvoiceDueDate(int $due_date): Carbon
    {
        $currentDay = Carbon::now()->day;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $dueDateMonth = $currentMonth;
        if ($due_date <= $currentDay) {
            $dueDateMonth++;
            if ($dueDateMonth > 12) {
                $dueDateMonth = 1;
                $currentYear++;
            }
        }
        return Carbon::createFromDate($currentYear, $dueDateMonth, $due_date);
    }
}
if (!function_exists('getInstallmentsAndDueDate')){
    function getInstallmentsAndDueDate(Request $request,int $invoiceId): array
    {
        $invoice = Invoice::select([
            'invoices.id',
            'invoices.due_date',
            'student_tutoring_packages.discount',
            'student_tutoring_packages.hours',
            'student_tutoring_packages.hourly_rate',
            'student_tutoring_packages.discount_type',
            'student_tutoring_packages.start_date',
            'invoices.has_installments',
        ])
            ->join('student_tutoring_packages', function ($query){
                $query->on('student_tutoring_packages.id', '=', 'invoices.invoiceable_id')
                    ->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->where('invoices.id','=' ,$invoiceId)->firstOrFail();


        $dueDate = getInvoiceDueDate($request->due_date);
        $totalAmount = cleanAmountWithCurrencyFormat(getPriceFromHoursAndHourlyWithDiscount($invoice->hourly_rate, $invoice->hours, $invoice->discount, $invoice->discount_type));
        $remainingAmount = $totalAmount - $invoice->amount_paid ?? 0;
        $remainingAmount = $remainingAmount + $invoice->amount_refunded;
        $installments =  MonthlyInstallments::calculate($remainingAmount, 0, $request->installments,$dueDate);
        return [
            'installments' => $installments,
            'invoice' => $invoice,
        ];
    }
}

if (!function_exists('booleanToActiveCheck')){
    function booleanToActiveCheck(bool $status): string
    {
        return match ($status) {
            true => "<span class='badge badge-success'>Active</span>",
            false => "<span class='badge badge-danger'>Inactive </span>",
        };
    }
}
