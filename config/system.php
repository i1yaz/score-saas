<?php

return [

    /**
     * Various invoice statuses, with their corresponding bootstrap colors (colors are used for labels etc)
     * [IMPORTANT WARNING]
     * Only change the color values
     * [AVAILABLE VALUES]
     * default|info|warning|success|danger|primary|green|lime|brown
     */
    'invoice_statuses' => [
        'draft' => 'default',
        'due' => 'warning',
        'overdue' => 'danger',
        'paid' => 'success',
        'part_paid' => 'info',
    ],


    /**
     * Show task timer seconds, when displaying the timers (excluding the timesheet page, which will always display seconds)
     * [AVAILABLE VALUES]
     * TRUE|FALSE
     */
    'timers' => [
        'display_seconds' => TRUE,
    ],
    'settings_system_logo_versioning' => '3',
    'settings_system_logo_frontend_name' => 'logo-frontend.png',
    'frontend-domain' => env('FRONTEND_DOMAIN',''),
    'landlord-domain' => env('FRONTEND_DOMAIN',''),
    'landlord-domain-without-protocol' => env('FRONTEND_DOMAIN_WITHOUT_PROTOCOL',''),
    'settings_system_pagination_limits' => 20,
];
