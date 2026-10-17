<?php

return [
    'send_enquiry' => 'Enviar consulta',
    'submit_request' => 'Enviar solicitud',
    'optional' => '(Opcional)',

    'name' => 'Nombre',
    'name_placeholder' => 'Nombre *',
    'email' => 'Correo electrónico corporativo',
    'email_placeholder' => 'Correo electrónico corporativo *',
    'phone' => 'Teléfono / WhatsApp',
    'phone_placeholder' => 'Teléfono / WhatsApp (opcional)',
    'company' => 'Empresa',
    'company_placeholder' => 'Empresa *',
    'service' => 'Servicio requerido',
    'service_placeholder' => 'Servicio requerido *',
    'service_choose' => 'Elija un servicio',
    'message' => 'Descripción del proyecto',
    'message_placeholder' => 'Descripción del proyecto *',
    'message_hint' => 'Objetivos, alcance, sistemas actuales y cualquier otra cosa que debamos saber…',
    'consent' => 'Acepto la :link y que Tectignis se ponga en contacto conmigo sobre esta consulta.',
    'privacy_policy' => 'Política de privacidad',

    'phone_input' => [
        'country_code' => 'Prefijo del país: :country',
        'number' => 'Número de teléfono',
        'search' => 'Buscar países',
        'search_placeholder' => 'Buscar país o prefijo',
        'codes' => 'Prefijos de país',
        'suggested' => 'Sugeridos',
        'all' => 'Todos los países',
        'no_match' => 'Ningún país coincide con su búsqueda.',
    ],

    'consultation' => [
        'title' => 'Reserve una consultoría técnica',
        'text' => 'Cuéntenos su proyecto y un ingeniero de soluciones se pondrá en contacto con usted.',
        'close' => 'Cerrar',
    ],

    /*
     * Display labels for the "Service Required" options. The submitted and
     * stored value stays the English name.
     */
    'services_general' => 'General',
    'services' => [
        'custom-software' => 'Software a medida',
        'web-apps' => 'Aplicaciones web',
        'saas' => 'SaaS',
        'erpcrm' => 'ERP/CRM',
        'mobile-apps' => 'Aplicaciones móviles',
        'e-commerce' => 'Comercio electrónico',
        'website-development' => 'Desarrollo web',
        'generative-ai' => 'IA generativa',
        'ai-agents' => 'Agentes de IA',
        'ocr' => 'OCR',
        'chatbots' => 'Chatbots',
        'business-automation' => 'Automatización de procesos',
        'ai-integration' => 'Integración de IA',
        'aws' => 'AWS',
        'azure' => 'Azure',
        'gcp' => 'GCP',
        'cloud-migration' => 'Migración a la nube',
        'devops' => 'DevOps',
        'vapt' => 'VAPT',
        'firewall' => 'Firewall',
        'networking' => 'Redes',
        'cctv' => 'CCTV',
        'access-control' => 'Control de acceso',
        'servers-storage' => 'Servidores y almacenamiento',
        'structured-cabling' => 'Cableado estructurado',
        'amc' => 'AMC',
        'it-consulting' => 'Consultoría de TI',
        'other-not-sure' => 'Otro / No estoy seguro',
    ],

    'attributes' => [
        'email' => 'correo electrónico corporativo',
        'company' => 'empresa',
        'phone' => 'número de teléfono / WhatsApp',
        'service' => 'servicio requerido',
        'message' => 'descripción del proyecto',
        'consent' => 'consentimiento de privacidad',
        'captcha' => 'CAPTCHA',
    ],

    'messages' => [
        'phone_invalid' => 'Introduzca un número de teléfono válido para el prefijo de país seleccionado.',
        'message_min' => 'Cuéntenos un poco más sobre su proyecto (al menos :min caracteres).',
        'consent_accepted' => 'Acepte la política de privacidad para que podamos responder a su consulta.',
        'captcha' => 'Confirme que no es un robot e inténtelo de nuevo.',
    ],

    'thank_you' => [
        'title' => 'Gracias',
        'heading' => '¡Gracias!',
        'heading_name' => '¡Gracias, :name!',
        'received' => 'Hemos recibido su consulta.',
        'received_service' => 'Hemos recibido su consulta sobre :service.',
        'next' => 'Nuestro equipo revisará la información que nos ha enviado y se pondrá en contacto con usted.',
        'back_home' => 'Volver al inicio',
    ],
];
