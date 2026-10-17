<?php

return [
    'send_enquiry' => 'إرسال الاستفسار',
    'submit_request' => 'إرسال الطلب',
    'optional' => '(اختياري)',

    'name' => 'الاسم',
    'name_placeholder' => 'الاسم *',
    'email' => 'البريد الإلكتروني للعمل',
    'email_placeholder' => 'البريد الإلكتروني للعمل *',
    'phone' => 'الهاتف / WhatsApp',
    'phone_placeholder' => 'الهاتف / WhatsApp (اختياري)',
    'company' => 'الشركة',
    'company_placeholder' => 'الشركة *',
    'service' => 'الخدمة المطلوبة',
    'service_placeholder' => 'الخدمة المطلوبة *',
    'service_choose' => 'اختر خدمة',
    'message' => 'وصف المشروع',
    'message_placeholder' => 'وصف المشروع *',
    'message_hint' => 'الأهداف والنطاق والأنظمة الحالية وأي شيء آخر ينبغي أن نعرفه…',
    'consent' => 'أوافق على :link وعلى تواصل Tectignis معي بشأن هذا الاستفسار.',
    'privacy_policy' => 'سياسة الخصوصية',

    'phone_input' => [
        'country_code' => 'رمز الدولة: :country',
        'number' => 'رقم الهاتف',
        'search' => 'البحث في الدول',
        'search_placeholder' => 'ابحث عن دولة أو رمز',
        'codes' => 'رموز الدول',
        'suggested' => 'مقترحة',
        'all' => 'جميع الدول',
        'no_match' => 'لا توجد دول مطابقة لبحثك.',
    ],

    'consultation' => [
        'title' => 'احجز استشارة تقنية',
        'text' => 'أخبرنا عن مشروعك وسيتواصل معك أحد مهندسي الحلول.',
        'close' => 'إغلاق',
    ],

    /*
     * Display labels for the "Service Required" options. The submitted and
     * stored value stays the English name.
     */
    'services_general' => 'عام',
    'services' => [
        'custom-software' => 'برمجيات مخصصة',
        'web-apps' => 'تطبيقات الويب',
        'saas' => 'SaaS',
        'erpcrm' => 'ERP/CRM',
        'mobile-apps' => 'تطبيقات الجوال',
        'e-commerce' => 'التجارة الإلكترونية',
        'website-development' => 'تطوير المواقع الإلكترونية',
        'generative-ai' => 'الذكاء الاصطناعي التوليدي',
        'ai-agents' => 'وكلاء الذكاء الاصطناعي',
        'ocr' => 'OCR',
        'chatbots' => 'روبوتات المحادثة',
        'business-automation' => 'أتمتة الأعمال',
        'ai-integration' => 'تكامل الذكاء الاصطناعي',
        'aws' => 'AWS',
        'azure' => 'Azure',
        'gcp' => 'GCP',
        'cloud-migration' => 'الترحيل إلى السحابة',
        'devops' => 'DevOps',
        'vapt' => 'VAPT',
        'firewall' => 'جدار الحماية',
        'networking' => 'الشبكات',
        'cctv' => 'CCTV',
        'access-control' => 'التحكم في الدخول',
        'servers-storage' => 'الخوادم والتخزين',
        'structured-cabling' => 'الكابلات المهيكلة',
        'amc' => 'AMC',
        'it-consulting' => 'استشارات تقنية المعلومات',
        'other-not-sure' => 'أخرى / غير متأكد',
    ],

    'attributes' => [
        'email' => 'البريد الإلكتروني للعمل',
        'company' => 'الشركة',
        'phone' => 'رقم الهاتف / WhatsApp',
        'service' => 'الخدمة المطلوبة',
        'message' => 'وصف المشروع',
        'consent' => 'الموافقة على سياسة الخصوصية',
        'captcha' => 'CAPTCHA',
    ],

    'messages' => [
        'phone_invalid' => 'يرجى إدخال رقم هاتف صحيح لرمز الدولة المحدد.',
        'message_min' => 'يرجى إخبارنا بالمزيد عن مشروعك (:min حرفًا على الأقل).',
        'consent_accepted' => 'يرجى الموافقة على سياسة الخصوصية حتى نتمكن من الرد على استفسارك.',
        'captcha' => 'يرجى تأكيد أنك لست روبوتًا ثم المحاولة مرة أخرى.',
    ],

    'thank_you' => [
        'title' => 'شكرًا لك',
        'heading' => 'شكرًا لك!',
        'heading_name' => 'شكرًا لك، :name!',
        'received' => 'لقد تلقّينا استفسارك.',
        'received_service' => 'لقد تلقّينا استفسارك بخصوص :service.',
        'next' => 'سيراجع فريقنا التفاصيل التي شاركتها وسيتواصل معك.',
        'back_home' => 'العودة إلى الرئيسية',
    ],
];
