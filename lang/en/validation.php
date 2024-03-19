<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute field must be accepted.',
    'accepted_if' => 'The :attribute field must be accepted when :other is :value.',
    'active_url' => 'The :attribute field must be a valid URL.',
    'after' => 'The :attribute field must be a date after :date.',
    'after_or_equal' => 'The :attribute field must be a date after or equal to :date.',
    'alpha' => 'The :attribute field must only contain letters.',
    'alpha_dash' => 'The :attribute field must only contain letters, numbers, dashes, and underscores.',
    'alpha_num' => 'The :attribute field must only contain letters and numbers.',
    'array' => 'The :attribute field must be an array.',
    'ascii' => 'The :attribute field must only contain single-byte alphanumeric characters and symbols.',
    'before' => 'The :attribute field must be a date before :date.',
    'before_or_equal' => 'The :attribute field must be a date before or equal to :date.',
    'between' => [
        'array' => 'The :attribute field must have between :min and :max items.',
        'file' => 'The :attribute field must be between :min and :max kilobytes.',
        'numeric' => 'The :attribute field must be between :min and :max.',
        'string' => 'The :attribute field must be between :min and :max characters.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'can' => 'The :attribute field contains an unauthorized value.',
    'confirmed' => 'The :attribute field confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute field must be a valid date.',
    'date_equals' => 'The :attribute field must be a date equal to :date.',
    'date_format' => 'The :attribute field must match the format :format.',
    'decimal' => 'The :attribute field must have :decimal decimal places.',
    'declined' => 'The :attribute field must be declined.',
    'declined_if' => 'The :attribute field must be declined when :other is :value.',
    'different' => 'The :attribute field and :other must be different.',
    'digits' => 'The :attribute field must be :digits digits.',
    'digits_between' => 'The :attribute field must be between :min and :max digits.',
    'dimensions' => 'The :attribute field has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'doesnt_end_with' => 'The :attribute field must not end with one of the following: :values.',
    'doesnt_start_with' => 'The :attribute field must not start with one of the following: :values.',
    'email' => 'The :attribute field must be a valid email address.',
    'ends_with' => 'The :attribute field must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute field must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'array' => 'The :attribute field must have more than :value items.',
        'file' => 'The :attribute field must be greater than :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than :value.',
        'string' => 'The :attribute field must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'The :attribute field must have :value items or more.',
        'file' => 'The :attribute field must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than or equal to :value.',
        'string' => 'The :attribute field must be greater than or equal to :value characters.',
    ],
    'image' => 'The :attribute field must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field must exist in :other.',
    'integer' => 'The :attribute field must be an integer.',
    'ip' => 'The :attribute field must be a valid IP address.',
    'ipv4' => 'The :attribute field must be a valid IPv4 address.',
    'ipv6' => 'The :attribute field must be a valid IPv6 address.',
    'json' => 'The :attribute field must be a valid JSON string.',
    'lowercase' => 'The :attribute field must be lowercase.',
    'lt' => [
        'array' => 'The :attribute field must have less than :value items.',
        'file' => 'The :attribute field must be less than :value kilobytes.',
        'numeric' => 'The :attribute field must be less than :value.',
        'string' => 'The :attribute field must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'The :attribute field must not have more than :value items.',
        'file' => 'The :attribute field must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be less than or equal to :value.',
        'string' => 'The :attribute field must be less than or equal to :value characters.',
    ],
    'mac_address' => 'The :attribute field must be a valid MAC address.',
    'max' => [
        'array' => 'The :attribute field must not have more than :max items.',
        'file' => 'The :attribute field must not be greater than :max kilobytes.',
        'numeric' => 'The :attribute field must not be greater than :max.',
        'string' => 'The :attribute field must not be greater than :max characters.',
    ],
    'max_digits' => 'The :attribute field must not have more than :max digits.',
    'mimes' => 'The :attribute field must be a file of type: :values.',
    'mimetypes' => 'The :attribute field must be a file of type: :values.',
    'min' => [
        'array' => 'The :attribute field must have at least :min items.',
        'file' => 'The :attribute field must be at least :min kilobytes.',
        'numeric' => 'The :attribute field must be at least :min.',
        'string' => 'The :attribute field must be at least :min characters.',
    ],
    'min_digits' => 'The :attribute field must have at least :min digits.',
    'missing' => 'The :attribute field must be missing.',
    'missing_if' => 'The :attribute field must be missing when :other is :value.',
    'missing_unless' => 'The :attribute field must be missing unless :other is :value.',
    'missing_with' => 'The :attribute field must be missing when :values is present.',
    'missing_with_all' => 'The :attribute field must be missing when :values are present.',
    'multiple_of' => 'The :attribute field must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute field format is invalid.',
    'numeric' => 'The :attribute field must be a number.',
    'password' => [
        'letters' => 'The :attribute field must contain at least one letter.',
        'mixed' => 'The :attribute field must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute field must contain at least one number.',
        'symbols' => 'The :attribute field must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute field format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_if_accepted' => 'The :attribute field is required when :other is accepted.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute field must match :other.',
    'size' => [
        'array' => 'The :attribute field must contain :size items.',
        'file' => 'The :attribute field must be :size kilobytes.',
        'numeric' => 'The :attribute field must be :size.',
        'string' => 'The :attribute field must be :size characters.',
    ],
    'starts_with' => 'The :attribute field must start with one of the following: :values.',
    'string' => 'The :attribute field must be a string.',
    'timezone' => 'The :attribute field must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'uppercase' => 'The :attribute field must be uppercase.',
    'url' => 'The :attribute field must be a valid URL.',
    'ulid' => 'The :attribute field must be a valid ULID.',
    'uuid' => 'The :attribute field must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    | These lines are used to swap attribute (form field name) place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email".
    |
     */
    'attributes' => [

        //common fields
        'role_id' => strtolower(__('lang.user_role')),
        'first_name' => strtolower(__('lang.first_name')),
        'last_name' => strtolower(__('lang.last_name')),
        'email' => strtolower(__('lang.email_address')),
        'password' => strtolower(__('lang.password')),
        'password_confirmation' => strtolower(__('lang.password_confirmation')),
        'city' => strtolower(__('lang.city')),
        'country' => strtolower(__('lang.country')),
        'phone' => strtolower(__('lang.phone')),
        'day' => strtolower(__('lang.day')),
        'month' => strtolower(__('lang.month')),
        'year' => strtolower(__('lang.year')),
        'hour' => strtolower(__('lang.hour')),
        'minute' => strtolower(__('lang.minute')),
        'second' => strtolower(__('lang.second')),
        'title' => strtolower(__('lang.title')),
        'date' => strtolower(__('lang.date')),
        'time' => strtolower(__('lang.time')),
        'recaptcha' => strtolower(__('lang.recaptcha')),
        'subject' => strtolower(__('lang.subject')),
        'message' => strtolower(__('lang.message')),

        //create or edit projects form
        'project_title' => strtolower(__('lang.project_title')),
        'project_date_start' => strtolower(__('lang.start_date')),
        'project_categoryid' => strtolower(__('lang.category')),
        'clientperm_tasks' => strtolower(__('lang.project_title')),
        'project_clientid' => strtolower(__('lang.client')),

        //create or edit clients form
        'client_company_name' => strtolower(__('lang.company_name')),

        //create or edit leads
        'lead_title' => strtolower(__('lang.title')),
        'lead_firstname' => strtolower(__('lang.first_name')),
        'lead_lastname' => strtolower(__('lang.last_name')),
        'lead_email' => strtolower(__('lang.email_address')),
        'lead_company_name' => strtolower(__('lang.company_name')),
        'lead_job_position' => strtolower(__('lang.job_title')),
        'lead_street' => strtolower(__('lang.street')),
        'lead_city' => strtolower(__('lang.city')),
        'lead_state' => strtolower(__('lang.state')),
        'lead_zip' => strtolower(__('lang.zipcode')),
        'lead_country' => strtolower(__('lang.country')),
        'lead_website' => strtolower(__('lang.website')),


        //create or edit task
        'task_projectid' => strtolower(__('lang.project')),
        'task_title' => strtolower(__('lang.title')),
        'task_status' => strtolower(__('lang.status')),
        'task_priority' => strtolower(__('lang.priority')),

        //create or edit invoice or clone
        'invoice_date' => strtolower(__('lang.invoice_date')),
        'bill_due_date' => strtolower(__('lang.due_date')),
        'bill_clientid' => strtolower(__('lang.client')),
        'bill_projectid' => strtolower(__('lang.project')),
        'bill_categoryid' => strtolower(__('lang.category')),
        'bill_recurring' => strtolower(__('lang.recurring')),
        'bill_recurring_duration' => strtolower(__('lang.duration')),
        'bill_recurring_period' => strtolower(__('lang.period')),
        'bill_recurring_cycles' => strtolower(__('lang.cycles')),
        'bill_recurring_next' => strtolower(__('lang.start_date')),

        'js_item_unit' => strtolower(__('lang.unit')),
        'bill_amount_before_tax' => strtolower(__('lang.amount_before_tax')),
        'bill_final_amount' => strtolower(__('lang.final_amount')),
        'bill_tax_total_amount' => strtolower(__('lang.tax_amount')),
        'bill_discount_percentage' => strtolower(__('lang.discount')),
        'bill_subtotal' => strtolower(__('lang.subtotal')),
        'bill_tax_total_percentage' => strtolower(__('lang.tax')),
        'bill_discount_amount' => strtolower(__('lang.discount')),


        //create or edit estimates
        'bill_date' => strtolower(__('lang.date')),
        'bill_expiry_date' => strtolower(__('lang.expiry_date')),

        //items
        'item_description' => strtolower(__('lang.description')),
        'item_unit' => strtolower(__('lang.unit')),
        'item_rate' => strtolower(__('lang.rate')),
        'item_categoryid' => strtolower(__('lang.category')),

        //note
        'note_title' => strtolower(__('lang.title')),
        'note_description' => strtolower(__('lang.description')),

        //payment
        'payment_gateway' => strtolower(__('lang.payment_method')),
        'payment_date' => strtolower(__('lang.payment_date')),
        'payment_amount' => strtolower(__('lang.amount')),
        'payment_invoiceid' => strtolower(__('lang.invoice_id')),

        //settings - general
        'settings_company_name' => strtolower(__('lang.company_name')),
        'settings_system_date_format' => strtolower(__('lang.date_format')),
        'settings_system_datepicker_format' => strtolower(__('lang.date_picker_format')),
        'settings_system_default_leftmenu' => strtolower(__('lang.main_menu_default_state')),
        'settings_system_default_statspanel' => strtolower(__('lang.stats_panel_default_state')),
        'settings_system_pagination_limits' => strtolower(__('lang.table_pagination_limits')),
        'settings_system_kanban_pagination_limits' => strtolower(__('lang.kanban_pagination_limits')),
        'settings_system_currency_symbol' => strtolower(__('lang.currency_symbol')),
        'settings_system_currency_position' => strtolower(__('lang.currency_symbol_position')),
        'settings_system_close_modals_body_click' => strtolower(__('lang.modal_window_close_on_body_click')),

        'settings_company_address_line_1' => strtolower(__('lang.address')),
        'settings_company_city' => strtolower(__('lang.city')),
        'settings_company_state' => strtolower(__('lang.state')),
        'settings_company_zipcode' => strtolower(__('lang.zipcode')),
        'settings_company_country' => strtolower(__('lang.country')),
        'settings_company_telephone' => strtolower(__('lang.telephone')),

        'settings_projects_default_hourly_rate' => strtolower(__('lang.rate')),
        'settings_projects_assignedperm_tasks_collaborate' => strtolower(__('lang.permissions')),
        'settings_projects_clientperm_tasks_view' => strtolower(__('lang.permissions')),
        'settings_projects_clientperm_tasks_collaborate' => strtolower(__('lang.permissions')),
        'settings_projects_clientperm_tasks_create' => strtolower(__('lang.permissions')),
        'settings_projects_clientperm_timesheets_view' => strtolower(__('lang.permissions')),
        'settings_projects_clientperm_expenses_view' => strtolower(__('lang.permissions')),

        'foo' => strtolower(__('lang.bar')),


        //settings - categories
        'category_name' => strtolower(__('lang.name')),

        //settig - tags
        'tag_title' => strtolower(__('lang.tag_title')),

        //setting - lead sources
        'leadsources_title' => strtolower(__('lang.source_name')),

        //setting - lead sources
        'leadstatus_title' => strtolower(__('lang.status_name')),

        //setting - theme
        'settings_theme_name' => strtolower(__('lang.theme')),

        //setting - email
        'settings_email_from_address' => strtolower(__('lang.system_email_address')),
        'settings_email_from_name' => strtolower(__('lang.system_from_name')),
        'settings_email_smtp_host' => strtolower(__('lang.smtp_host')),
        'settings_email_smtp_port' => strtolower(__('lang.smtp_port')),
        'settings_email_smtp_username' => strtolower(__('lang.username')),
        'settings_email_smtp_password' => strtolower(__('lang.password')),

        //settings invoices
        'settings_invoices_recurring_grace_period' => strtolower(__('lang.bill_recurring_grace_period')),

        //milestone
        'milestone_title' => strtolower(__('lang.milestone_name')),

        //knowledgebase
        'category_visibility' => strtolower(__('lang.visible_to')),
        'knowledgebase_title' => strtolower(__('lang.title')),
        'knowledgebase_text' => strtolower(__('lang.description')),
        'kbcategory_title' => strtolower(__('lang.category_name')),

        //expenses
        'expense_description' => strtolower(__('lang.description')),
        'expense_clientid' => strtolower(__('lang.client')),
        'expense_projectid' => strtolower(__('lang.project')),
        'expense_date' => strtolower(__('lang.date')),
        'expense_amount' => strtolower(__('lang.amount')),
        'expense_categoryid' => strtolower(__('lang.category')),
        'expense_billable' => strtolower(__('lang.bar')),

        //tickets
        'ticket_clientid' => strtolower(__('lang.client')),
        'ticket_categoryid' => strtolower(__('lang.department')),
        'ticket_projectid' => strtolower(__('lang.project')),
        'ticket_subject' => strtolower(__('lang.subject')),
        'ticket_message' => strtolower(__('lang.message')),
        'ticketreply_text' => strtolower(__('lang.message')),
        'ticketreply_ticketid' => strtolower(__('lang.ticket')),

        //comment
        'comment_text' => strtolower(__('lang.comment')),

        //email templates
        'emailtemplate_body' => strtolower(__('lang.email_body')),
        'emailtemplate_subject' => strtolower(__('lang.email_subject')),

        //settings - payment methods
        'settings_paypal_currency' => strtolower(__('lang.currency')),
        'settings_stripe_display_name' => strtolower(__('lang.display_name')),
        'settings_paypal_display_name' => strtolower(__('lang.display_name')),
        'settings_bank_display_name' => strtolower(__('lang.display_name')),

        //settings - milestone
        'milestonecategory_title' => strtolower(__('lang.milestone_name')),

        //cards
        'checklist_text' => strtolower(__('lang.checklist')),

    ],


];
