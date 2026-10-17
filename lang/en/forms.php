<?php

return [
    'send_enquiry' => 'Send Enquiry',
    'submit_request' => 'Submit Request',
    'optional' => '(Optional)',

    'name' => 'Name',
    'name_placeholder' => 'Name *',
    'email' => 'Business Email',
    'email_placeholder' => 'Business Email *',
    'phone' => 'Phone / WhatsApp',
    'phone_placeholder' => 'Phone / WhatsApp (optional)',
    'company' => 'Company',
    'company_placeholder' => 'Company *',
    'service' => 'Service Required',
    'service_placeholder' => 'Service Required *',
    'service_choose' => 'Choose a service',
    'message' => 'Project Description',
    'message_placeholder' => 'Project Description *',
    'message_hint' => 'Goals, scope, current systems and anything else we should know…',
    'consent' => 'I agree to the :link and to Tectignis contacting me about this enquiry.',
    'privacy_policy' => 'Privacy Policy',

    'phone_input' => [
        'country_code' => 'Country code: :country',
        'number' => 'Phone number',
        'search' => 'Search countries',
        'search_placeholder' => 'Search country or code',
        'codes' => 'Country codes',
        'suggested' => 'Suggested',
        'all' => 'All countries',
        'no_match' => 'No countries match your search.',
    ],

    'consultation' => [
        'title' => 'Book a Technical Consultation',
        'text' => 'Tell us about your project and a solution engineer will get back to you.',
        'close' => 'Close',
    ],

    /*
     * Display labels for the "Service Required" options. The submitted and
     * stored value stays the English name.
     */
    'services_general' => 'General',
    'services' => [
        'custom-software' => 'Custom Software',
        'web-apps' => 'Web Apps',
        'saas' => 'SaaS',
        'erpcrm' => 'ERP/CRM',
        'mobile-apps' => 'Mobile Apps',
        'e-commerce' => 'E-commerce',
        'website-development' => 'Website Development',
        'generative-ai' => 'Generative AI',
        'ai-agents' => 'AI Agents',
        'ocr' => 'OCR',
        'chatbots' => 'Chatbots',
        'business-automation' => 'Business Automation',
        'ai-integration' => 'AI Integration',
        'aws' => 'AWS',
        'azure' => 'Azure',
        'gcp' => 'GCP',
        'cloud-migration' => 'Cloud Migration',
        'devops' => 'DevOps',
        'vapt' => 'VAPT',
        'firewall' => 'Firewall',
        'networking' => 'Networking',
        'cctv' => 'CCTV',
        'access-control' => 'Access Control',
        'servers-storage' => 'Servers & Storage',
        'structured-cabling' => 'Structured Cabling',
        'amc' => 'AMC',
        'it-consulting' => 'IT Consulting',
        'other-not-sure' => 'Other / Not sure',
    ],

    'attributes' => [
        'email' => 'business email',
        'company' => 'company',
        'phone' => 'phone / WhatsApp number',
        'service' => 'service required',
        'message' => 'project description',
        'consent' => 'privacy consent',
        'captcha' => 'CAPTCHA',
    ],

    'messages' => [
        'phone_invalid' => 'Please enter a valid phone number for the selected country code.',
        'message_min' => 'Please tell us a little more about your project (at least :min characters).',
        'consent_accepted' => 'Please agree to the privacy policy so we can respond to your enquiry.',
        'captcha' => 'Please confirm you are not a robot and try again.',
    ],

    'thank_you' => [
        'title' => 'Thank You',
        'heading' => 'Thank you!',
        'heading_name' => 'Thank you, :name!',
        'received' => 'We have received your enquiry.',
        'received_service' => 'We have received your enquiry about :service.',
        'next' => 'Our team will review the details you shared and get in touch with you.',
        'back_home' => 'Back to Home',
    ],
];
