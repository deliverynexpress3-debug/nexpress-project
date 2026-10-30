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

    'accepted' => 'The :attribute must be accepted.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'Please enter a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'Please upload :attribute as a JPG or PNG image under 2 MB.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'numeric' => 'The :attribute must not be greater than :max.',
        'file' => 'Please upload :attribute under 2 MB.',
        'string' => 'The :attribute must not be greater than :max characters.',
        'array' => 'The :attribute must not have more than :max items.',
    ],
    'mimes' => 'Please upload :attribute as a JPG or PNG image. Other formats are not allowed.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'Please enter at least :min characters for :attribute.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'Please enter a valid :attribute.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'Please enter :attribute.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'Please upload :attribute as a JPG or PNG image under 2 MB. The file could not be uploaded.',
    'url' => 'Please enter a valid link for :attribute.',
    'uuid' => 'The :attribute must be a valid UUID.',
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
        'portrait_img' => [
            'required' => 'Please upload a portrait image.',
            'image' => 'Please upload a portrait image as a JPG or PNG file under 2 MB.',
            'mimes' => 'Please upload a portrait image as a JPG or PNG file. Other formats are not allowed.',
            'max' => 'Please upload a portrait image under 2 MB.',
            'uploaded' => 'The portrait image could not be uploaded. Please use a JPG or PNG file under 2 MB.',
        ],
        'landscape_img' => [
            'image' => 'Please upload a landscape image as a JPG or PNG file under 2 MB.',
            'mimes' => 'Please upload a landscape image as a JPG or PNG file. Other formats are not allowed.',
            'max' => 'Please upload a landscape image under 2 MB.',
            'uploaded' => 'The landscape image could not be uploaded. Please use a JPG or PNG file under 2 MB.',
        ],
        'image' => [
            'image' => 'Please upload an image as a JPG or PNG file under 2 MB.',
            'mimes' => 'Please upload an image as a JPG or PNG file. Other formats are not allowed.',
            'max' => 'Please upload an image under 2 MB.',
            'uploaded' => 'The image could not be uploaded. Please use a JPG or PNG file under 2 MB.',
        ],
        'upload' => [
            'image' => 'Please upload an image as a JPG or PNG file under 2 MB.',
            'mimes' => 'Please upload an image as a JPG or PNG file. Other formats are not allowed.',
            'max' => 'Please upload an image under 2 MB.',
            'uploaded' => 'The image could not be uploaded. Please use a JPG or PNG file under 2 MB.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'name',
        'full_name' => 'full name',
        'email' => 'email address',
        'password' => 'password',
        'mobile_number' => 'mobile number',
        'country_code' => 'country code',
        'country_name' => 'country name',
        'customer_id' => 'customer id',
        'seller_id' => 'seller id',
        'delivery_boy_id' => 'delivery partner id',
        'order_status' => 'order status',
        'status' => 'status',
        'type' => 'login type',
        'type_id' => 'type',
        'is_home_screen' => 'home screen option',
        'category_id' => 'category',
        'sub_category_id' => 'sub category',
        'brand_id' => 'brand',
        'item_id' => 'product',
        'price' => 'price',
        'unit' => 'unit',
        'per_unit' => 'quantity per unit',
        'total_stock' => 'stock',
        'video_type' => 'video type',
        'portrait_img' => 'portrait image',
        'landscape_img' => 'landscape image',
        'image' => 'image',
        'upload' => 'image',
        'group_order_id' => 'order id',
        'order_id' => 'order id',
        'otp' => 'OTP',
        'address' => 'address',
        'quantity' => 'quantity',
        'page_no' => 'page number',
        'query' => 'search word',
        'section_id' => 'section',
        'promo_code' => 'promo code',
        'url' => 'video link',
    ],

];
