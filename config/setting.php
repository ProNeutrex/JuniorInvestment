<?php

return [
    'global' => [
        'title' => 'Configurações Globais',
    
        'elements' => [
            [
                'type' => 'file',
                'data' => 'string',
                'name' => 'site_logo',
                'label' => 'Logo do Site',
                'rules' => 'mimes:jpeg,jpg,png|max:1000',
                'value' => 'default/fav.png',
            ],
            [
                'type' => 'file',
                'data' => 'string',
                'name' => 'site_favicon',
                'label' => 'Favicon do Site',
                'rules' => 'mimes:jpeg,jpg,png|max:1000',
                'value' => 'image/logo.png',
            ],
            [
                'type' => 'file',
                'data' => 'string',
                'name' => 'login_bg',
                'label' => 'Capa de Login do Admin',
                'rules' => 'mimes:jpeg,jpg,png|max:2000',
                'value' => 'default/auth-bg.jpg',
            ],
            [
                'type' => 'text',
                'data' => 'string',
                'name' => 'site_admin_prefix',
                'label' => 'Prefixo do Admin do Site',
                'rules' => 'required',
                'value' => 'admin',
            ],
            [
                'type' => 'switch',
                'data' => 'string',
                'name' => 'site_currency_type',
                'label' => 'Tipo de Moeda do Site',
                'rules' => 'required',
                'value' => 'fiat',
            ],
            [
                'type' => 'dropdown',
                'data' => 'string',
                'name' => 'site_currency',
                'label' => 'Moeda do Site',
                'rules' => 'required',
                'value' => 'USD',
            ],
            [
                'type' => 'dropdown',
                'data' => 'string',
                'name' => 'site_timezone',
                'label' => 'Fuso Horário do Site',
                'rules' => 'required',
                'value' => 'UTC',
            ],
            [
                'type' => 'dropdown',
                'data' => 'string',
                'name' => 'site_referral',
                'label' => 'Tipo de Indicação do Site',
                'rules' => 'required',
                'value' => 'level',
            ],
            [
                'type' => 'text',
                'data' => 'string',
                'name' => 'currency_symbol',
                'label' => 'Símbolo da Moeda',
                'rules' => 'required',
                'value' => '$',
            ],
            [
                'type' => 'text',
                'data' => 'integer',
                'name' => 'referral_code_limit',
                'label' => 'Limite de Código de Indicação',
                'rules' => 'required',
                'value' => '6',
            ],
            [
                'type' => 'dropdown',
                'data' => 'string',
                'name' => 'home_redirect',
                'label' => 'Redirecionamento para a Página Inicial',
                'rules' => 'required',
                'value' => '/',
            ],
            [
                'type' => 'text',
                'data' => 'string',
                'name' => 'site_title',
                'label' => 'Título do Site',
                'rules' => 'required|min:2|max:50',
                'value' => 'Hyiprio',
            ],
            [
                'type' => 'email',
                'data' => 'string',
                'name' => 'site_email',
                'label' => 'Email do Site',
                'rules' => 'required',
                'value' => 'admin@tdevs.co',
            ],
            [
                'type' => 'email',
                'data' => 'string',
                'name' => 'support_email',
                'label' => 'Email de Suporte',
                'rules' => 'required',
                'value' => 'support@tdevs.co',
            ],
        ],
    ],
    
    'permission' => [
        'title' => 'Configurações de Permissões',
        'elements' => [
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'email_verification',
                'label' => 'Verificação de Email',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'kyc_verification',
                'label' => 'Verificação KYC',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'fa_verification',
                'label' => 'Verificação 2FA',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'account_creation',
                'label' => 'Criação de Conta',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'user_deposit',
                'label' => 'Depósito do Usuário',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'user_withdraw',
                'label' => 'Retirada do Usuário',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'transfer_status',
                'label' => 'Enviar Dinheiro do Usuário',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'sign_up_referral',
                'label' => 'Indicação de Usuário',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'referral_signup_bonus',
                'label' => 'Bônus de Inscrição',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'investment_referral_bounty',
                'label' => 'Bônus de Indicação de Investimento',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'deposit_referral_bounty',
                'label' => 'Bônus de Indicação de Depósito',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'site_animation',
                'label' => 'Animação do Site',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'back_to_top',
                'label' => 'Voltar ao Topo do Site',
                'rules' => 'required',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'debug_mode',
                'label' => 'Modo de Desenvolvimento',
                'rules' => 'required',
                'value' => 0,
            ],
        ],
    ],    

    'fee' => [
        'title' => 'Site Fee, Limit and Bonus Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'min_send', // unique name for field
                'label' => 'Minimum Send Money', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'max_send', // unique name for field
                'label' => 'Maximum Send Money', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'send_charge_type', // unique name for field
                'label' => 'Send Money Charge Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'send_charge', // unique name for field
                'label' => 'Send Money Charge', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'wallet_exchange_charge_type', // unique name for field
                'label' => 'Wallet Exchange Charge Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'wallet_exchange_charge', // unique name for field
                'label' => 'Wallet Exchange Charge', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],

            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'referral_bonus', // unique name for field
                'label' => 'Referral Bonus', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 20, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'signup_bonus', // unique name for field
                'label' => 'Sign Up Bonus', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 20, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'wallet_exchange_day_limit', // unique name for field
                'label' => 'Wallet Exchange Limit', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 10, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'send_money_day_limit', // unique name for field
                'label' => 'Send Money Day Limit', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 14, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'withdraw_day_limit', // unique name for field
                'label' => 'Withdraw Day Limit', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 11, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'investment_cancellation_daily_limit', // unique name for field
                'label' => 'Investment Cancellation Daily Limit', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 6, // default value if you want
            ],
        ],
    ],

    'mail' => [
        'title' => 'Mail Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_from_name', // unique name for field
                'label' => 'Email From Name', // you know what label it is
                'rules' => 'required|min:5|max:50', // validation rule of laravel
                'value' => 'Tdevs', // default value if you want
            ],
            [
                'type' => 'email', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_from_address', // unique name for field
                'label' => 'Email From Address', // you know what label it is
                'rules' => 'required|min:5|max:50', // validation rule of laravel
                'value' => 'wd2rasel@gmail.com', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mailing_driver', // unique name for field
                'label' => 'Mailing Driver', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'SMTP', // default value if you want
            ],

            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mail_username', // unique name for field
                'label' => 'Mail Username', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '465', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mail_password', // unique name for field
                'label' => 'Mail Password', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '0000', // default value if you want
            ],

            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mail_host', // unique name for field
                'label' => 'Mail Host', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'mail.tdevs.co', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'integer', // data type, string, int, boolean
                'name' => 'mail_port', // unique name for field
                'label' => 'Mail Port', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '465', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mail_secure', // unique name for field
                'label' => 'Mail Secure', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'ssl', // default value if you want
            ],
        ],
    ],

    'site_maintenance' => [
        'title' => 'Site Maintenance',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'maintenance_mode', // unique name for field
                'label' => 'Maintenance Mode', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'secret_key', // unique name for field
                'label' => 'Secret Key', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'secret', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'maintenance_title', // unique name for field
                'label' => 'Title', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'Site is not under maintenance', // default value if you want
            ],
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'maintenance_text', // unique name for field
                'label' => 'Maintenance Text', // you know what label it is
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => 'Sorry for interrupt! Site will live soon.', // default value if you want
            ],
        ],
    ],

    'gdpr' => [
        'title' => 'GDPR Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'gdpr_status', // unique name for field
                'label' => 'GDPR Status', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_text', // unique name for field
                'label' => 'GDPR Text', // you know what label it is
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => 'Please allow us to collect data about how you use our website. We will use it to improve our website, make your browsing experience and our business decisions better. Learn more', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_button_label', // unique name for field
                'label' => 'Button Label', // you know what label it is
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => 'Learn More', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_button_url', // unique name for field
                'label' => 'Button URL', // you know what label it is
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => '/privacy-policy', // default value if you want
            ],
        ],
    ],

];
