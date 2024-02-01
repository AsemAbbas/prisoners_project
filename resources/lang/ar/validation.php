<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted' => 'يجب قبول الحقل :attribute',
    'active_url' => 'الحقل :attribute لا يُمثّل رابطًا صحيحًا',
    'after' => 'يجب على الحقل :attribute أن يكون تاريخًا لاحقًا للتاريخ :date.',
    'after_or_equal' => 'الحقل :attribute يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date.',
    'alpha' => 'يجب أن لا يحتوي الحقل :attribute سوى على حروف',
    'alpha_dash' => 'يجب أن لا يحتوي الحقل :attribute على حروف، أرقام ومطّات.',
    'alpha_num' => 'يجب أن يحتوي :attribute على حروفٍ وأرقامٍ فقط',
    'array' => 'يجب أن يكون الحقل :attribute ًمصفوفة',
    'before' => 'يجب على الحقل :attribute أن يكون تاريخًا سابقًا للتاريخ :date.',
    'before_or_equal' => 'الحقل :attribute يجب أن يكون تاريخا سابقا أو مطابقا للتاريخ :date',
    'between' => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون عدد حروف النّص :attribute بين :min و :max',
        'array' => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max',
    ],
    'boolean' => 'يجب أن تكون قيمة الحقل :attribute إما true أو false ',
    'confirmed' => 'حقل التأكيد غير مُطابق للحقل :attribute',
    'date' => 'الحقل :attribute ليس تاريخًا صحيحًا',
    'date_format' => 'لا يتوافق الحقل :attribute مع الشكل :format.',
    'different' => 'يجب أن يكون الحقلان :attribute و :other مُختلفان',
    'digits' => 'يجب أن يحتوي الحقل :attribute على :digits رقمًا/أرقام',
    'digits_between' => 'يجب أن يحتوي الحقل :attribute بين :min و :max رقمًا/أرقام ',
    'dimensions' => 'الـ :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct' => 'للحقل :attribute قيمة مُكرّرة.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح البُنية',
    'exists' => 'الحقل :attribute لاغٍ',
    'file' => 'الـ :attribute يجب أن يكون من ملفا.',
    'filled' => 'الحقل :attribute إجباري',
    'image' => 'يجب أن يكون الحقل :attribute صورةً',
    'in' => 'الحقل :attribute لاغٍ',
    'in_array' => 'الحقل :attribute غير موجود في :other.',
    'integer' => 'يجب أن يكون الحقل :attribute عددًا صحيحًا',
    'ip' => 'يجب أن يكون الحقل :attribute عنوان IP ذا بُنية صحيحة',
    'ipv4' => 'يجب أن يكون الحقل :attribute عنوان IPv4 ذا بنية صحيحة.',
    'ipv6' => 'يجب أن يكون الحقل :attribute عنوان IPv6 ذا بنية صحيحة.',
    'json' => 'يجب أن يكون الحقل :attribute نصا من نوع JSON.',
    'max' => [
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute مساوية أو أصغر لـ :max.',
        'file' => 'يجب أن لا يتجاوز حجم الملف :attribute :max كيلوبايت',
        'string' => 'يجب أن لا يتجاوز طول نص :attribute :max حروفٍ/حرفًا',
        'array' => 'يجب أن لا يحتوي الحقل :attribute على أكثر من :max عناصر/عنصر.',
    ],
    'mimes' => 'يجب أن يكون الحقل ملفًا من نوع : :values.',
    'mimetypes' => 'يجب أن يكون الحقل ملفًا من نوع : :values.',
    'min' => [
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute مساوية أو أكبر لـ :min.',
        'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت',
        'string' => 'يجب أن يكون طول نص :attribute على الأقل :min حروفٍ/حرفًا',
        'array' => 'يجب أن يحتوي الحقل :attribute على الأقل على :min عُنصرًا/عناصر',
    ],
    'not_in' => 'الحقل :attribute لاغٍ',
    'numeric' => 'يجب على الحقل :attribute أن يكون رقمًا',
    'present' => 'يجب تقديم الحقل :attribute',
    'regex' => 'صيغة الحقل :attribute .غير صحيحة',
    'required' => 'الحقل :attribute مطلوب.',
    'required_if' => 'الحقل :attribute مطلوب في حال ما إذا كان :other يساوي :value.',
    'required_unless' => 'الحقل :attribute مطلوب في حال ما لم يكن :other يساوي :values.',
    'required_with' => 'الحقل :attribute مطلوب إذا توفّر :values.',
    'required_with_all' => 'الحقل :attribute مطلوب إذا توفّر :values.',
    'required_without' => 'الحقل :attribute مطلوب إذا لم يتوفّر :values.',
    'required_without_all' => 'الحقل :attribute مطلوب إذا لم يتوفّر :values.',
    'same' => 'يجب أن يتطابق الحقل :attribute مع :other',
    'size' => [
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute مساوية لـ :size',
        'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت',
        'string' => 'يجب أن يحتوي النص :attribute على :size حروفٍ/حرفًا بالظبط',
        'array' => 'يجب أن يحتوي الحقل :attribute على :size عنصرٍ/عناصر بالظبط',
    ],
    'string' => 'يجب أن يكون الحقل :attribute نصآ.',
    'timezone' => 'يجب أن يكون :attribute نطاقًا زمنيًا صحيحًا',
    'unique' => 'قيمة الحقل :attribute مُستخدمة من قبل',
    'uploaded' => 'فشل في تحميل الـ :attribute',
    'url' => 'صيغة الرابط :attribute غير صحيحة',

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
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [


        'news_title' => 'عنوان الخبر',
        'news_photo' => 'صورة الخبر',
        'news_url' => 'رابط الخبر',
        'news_type_id' => 'تصنيف الخبر',
        'town_id' => 'البلدة',
        'city_id' => 'المحافظة',
        'education_level' => 'المستوى التعليمي',
        'judgment_in_lifetime' => 'الحكم مؤبدات',
        'judgment_in_years' => 'الحكم سنوات',
        'judgment_in_months' => 'الحكم أشهر',
        'search.identification_number' => 'رقم الهوية',

        'search.first_name' => 'الاسم الأول',
        'search.second_name' => 'اسم الاب',
        'search.last_name' => 'اسم العائلة',
        '*.old_arrest_start_date' => 'بداية الاعتقال',
        '*.old_arrest_end_date' => 'نهاية الاعتقال',
        '*.arrested_side' => 'جهة الاعتقال',
        'suggester_identification_number' => 'رقم هوية مقدم البيانات',
        'suggester_name' => 'اسم مقدم البيانات',
        'suggester_phone_number' => 'رقم هاتف مقدم البيانات',
        'note' => 'الملاحظات',
        'prisoner_type_id' => 'تصنيف الأسير',
        'prisoner_type_name' => 'اسم تصنيف الأسير',
        'news_type_color' => 'لون التصنيف',
        'news_type_name' => 'اسم التصنيف',
        'belong_id' => 'الإنتماء',
        'belong_name' => 'اسم الإنتماء',
        'statistic_type' => 'نوع الاحصائية',
        'statistic_number' => 'مجموع الاحصائية',
        'social_name' => 'اسم الموقع',
        'social_link' => 'رابط الموقع',
        'social_photo' => 'صورة الموقع',
        'health_id' => 'الحالة الصحية',
        'health_name' => 'اسم الحالة الصحية',
        'relationship_id' => 'صلة القرابة',
        'relationship_name' => 'اسم صلة القرابة',
        'social_type' => 'الحالة الإجتماعية',
        'first_phone_number' => 'رقم التواصل',
        'first_phone_owner' => 'اسم صاحب الرقم',
        'second_phone_number' => 'رقم التواصل الإضافي',
        'second_phone_owner' => 'اسم صاحب الرقم الإضافي',

        'city_name' => 'اسم المحافظة',

        'login' => 'اسم المستخدم أو البريد الإلكتروني',
        'identification_number' => 'رقم الهوية',
        'arrest_start_date' => 'تاريخ الإعتقال',
        'release_date' => 'تاريخ الإفراج',
        'second_name' => 'اسم الأب',
        'nationality' => 'الجنسية',
        'place_of_birth' => 'مكان الميلاد',
        'village' => 'القرية',
        'place_of_residence' => 'مكان الإقامة',
        'parts_preserved_quran' => 'الاجزاء التي تحفظها من القرآن',
        'recitation_and_intonation' => 'التلاوة والتجويد',
        'courses' => 'دورات حصلت عليها',
        'level_name' => 'اسم المستوى',
        'level_id' => 'المستوى',
        'education_sessions_name' => 'اسم الجلسة',
        'education_session_id' => 'الجلسة',
        'course_name' => 'اسم المساق',
        'course_video_url' => 'رابط فيديو االمساق',
        'course_pdf_url' => 'ملف االمساق',
        'exam_name' => 'اسم التقويم',
        'exam_type' => 'نوع التقويم',
        'father_name' => 'اسم الأب',
        'mather_name' => 'اسم الأم',
        'wife_name' => 'اسم الزوجة',
        'date_of_birth' => 'تاريخ الميلاد',
        'social_status' => 'الحالة الإجتماعية',
        'number_of_children' => 'عدد الأبناء',
        'number_of_dependent_children' => 'عدد الأبناء المعالين',
        'affiliation' => 'جهة الإنتماء',
        'date_of_marriage' => 'تاريخ الزواج',
        'educational_level' => 'المؤهل العلمي',
        'mailing_address' => 'عنوان المراسلة',
        'phone_number' => 'رقم الهاتف',
        'breadwinner' => 'اسم المعيل',
        'agent' => ' اسم الوكيل',
        'supervisor' => 'اسم المشرف',
        'status_type' => 'نوع الحالة',
        'occupation' => 'المهنة',
        'certified' => 'معتمد',
        'conversion_a_certified' => 'معتمد للتحويل',
        'affiliation_history' => 'تاريخ الإنتماء',
        'arrest_type' => 'نوع الإعتقال',
        'name' => 'الاسم',
        'username' => 'اسم المُستخدم',
        'email' => 'البريد الالكتروني',
        'first_name' => 'الاسم الأول',
        'third_name' => 'اسم الجد',
        'last_name' => 'اسم العائلة',
        'password' => 'كلمة المرور',
        'new_password' => 'كلمة المرور الجديدة',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'city' => 'المحافظة',
        'country' => 'الدولة',
        'address' => 'العنوان',
        'phone' => 'الهاتف',
        'mobile' => 'الجوال',
        'age' => 'العمر',
        'sex' => 'النوع',
        'gender' => 'الجنس',
        'answers' => 'أجوبة',
        'day' => 'اليوم',
        'month' => 'الشهر',
        'year' => 'السنة',
        'hour' => 'ساعة',
        'minute' => 'دقيقة',
        'second' => 'ثانية',
        'content' => 'المُحتوى',
        'description' => 'الوصف',
        'excerpt' => 'المُلخص',
        'date' => 'التاريخ',
        'time' => 'الوقت',
        'available' => 'مُتاح',
        'size' => 'الحجم',
        'price' => 'السعر',
        'desc' => 'نبذه',
        'q' => 'البحث',
        'link' => ' ',
        'slug' => ' ',
    ],

];
