<?php

return [
    'send_enquiry' => 'Envoyer la demande',
    'submit_request' => 'Soumettre la demande',
    'optional' => '(Facultatif)',

    'name' => 'Nom',
    'name_placeholder' => 'Nom *',
    'email' => 'E-mail professionnel',
    'email_placeholder' => 'E-mail professionnel *',
    'phone' => 'Téléphone / WhatsApp',
    'phone_placeholder' => 'Téléphone / WhatsApp (facultatif)',
    'company' => 'Entreprise',
    'company_placeholder' => 'Entreprise *',
    'service' => 'Service souhaité',
    'service_placeholder' => 'Service souhaité *',
    'service_choose' => 'Choisissez un service',
    'message' => 'Description du projet',
    'message_placeholder' => 'Description du projet *',
    'message_hint' => 'Objectifs, périmètre, systèmes existants et tout ce que nous devrions savoir…',
    'consent' => 'J’accepte la :link et j’accepte que Tectignis me contacte au sujet de cette demande.',
    'privacy_policy' => 'Politique de confidentialité',

    'phone_input' => [
        'country_code' => 'Indicatif du pays : :country',
        'number' => 'Numéro de téléphone',
        'search' => 'Rechercher un pays',
        'search_placeholder' => 'Rechercher un pays ou un indicatif',
        'codes' => 'Indicatifs pays',
        'suggested' => 'Suggestions',
        'all' => 'Tous les pays',
        'no_match' => 'Aucun pays ne correspond à votre recherche.',
    ],

    'consultation' => [
        'title' => 'Réserver une consultation technique',
        'text' => 'Présentez-nous votre projet et un ingénieur solutions reviendra vers vous.',
        'close' => 'Fermer',
    ],

    /*
     * Display labels for the "Service Required" options. The submitted and
     * stored value stays the English name.
     */
    'services_general' => 'Général',
    'services' => [
        'custom-software' => 'Logiciel sur mesure',
        'web-apps' => 'Applications web',
        'saas' => 'SaaS',
        'erpcrm' => 'ERP/CRM',
        'mobile-apps' => 'Applications mobiles',
        'e-commerce' => 'E-commerce',
        'website-development' => 'Développement de sites web',
        'generative-ai' => 'IA générative',
        'ai-agents' => 'Agents IA',
        'ocr' => 'OCR',
        'chatbots' => 'Chatbots',
        'business-automation' => 'Automatisation des processus',
        'ai-integration' => 'Intégration de l’IA',
        'aws' => 'AWS',
        'azure' => 'Azure',
        'gcp' => 'GCP',
        'cloud-migration' => 'Migration vers le cloud',
        'devops' => 'DevOps',
        'vapt' => 'VAPT',
        'firewall' => 'Pare-feu',
        'networking' => 'Réseaux',
        'cctv' => 'Vidéosurveillance',
        'access-control' => 'Contrôle d’accès',
        'servers-storage' => 'Serveurs et stockage',
        'structured-cabling' => 'Câblage structuré',
        'amc' => 'AMC',
        'it-consulting' => 'Conseil informatique',
        'other-not-sure' => 'Autre / Je ne sais pas',
    ],

    'attributes' => [
        'email' => 'e-mail professionnel',
        'company' => 'entreprise',
        'phone' => 'numéro de téléphone / WhatsApp',
        'service' => 'service souhaité',
        'message' => 'description du projet',
        'consent' => 'consentement de confidentialité',
        'captcha' => 'CAPTCHA',
    ],

    'messages' => [
        'phone_invalid' => 'Veuillez saisir un numéro de téléphone valide pour l’indicatif du pays sélectionné.',
        'message_min' => 'Veuillez nous en dire un peu plus sur votre projet (au moins :min caractères).',
        'consent_accepted' => 'Veuillez accepter la politique de confidentialité afin que nous puissions répondre à votre demande.',
        'captcha' => 'Veuillez confirmer que vous n’êtes pas un robot, puis réessayer.',
    ],

    'thank_you' => [
        'title' => 'Merci',
        'heading' => 'Merci !',
        'heading_name' => 'Merci, :name !',
        'received' => 'Nous avons bien reçu votre demande.',
        'received_service' => 'Nous avons bien reçu votre demande concernant :service.',
        'next' => 'Notre équipe va étudier les informations transmises et reviendra vers vous.',
        'back_home' => 'Retour à l’accueil',
    ],
];
