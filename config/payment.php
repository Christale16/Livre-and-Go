<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Comptes de réception des paiements
    |--------------------------------------------------------------------------
    |
    | Renseigne ici (via le fichier .env) les numéros/coordonnées sur
    | lesquels tes clients doivent envoyer leur paiement. Ces valeurs
    | s'affichent sur le formulaire de commande.
    |
    */

    'mtn_momo' => [
        'label' => 'MTN Mobile Money',
        'number' => env('PAYMENT_MTN_NUMBER', 'À renseigner'),
        'holder' => env('PAYMENT_MTN_NAME', 'À renseigner'),
    ],

    'moov_celtiis' => [
        'label' => 'Celtiis Cash',
        'number' => env('PAYMENT_MOOV_NUMBER', 'À renseigner'),
        'holder' => env('PAYMENT_MOOV_NAME', 'À renseigner'),
    ],

    'bank_transfer' => [
        'label' => 'Virement bancaire',
        'bank_name' => env('PAYMENT_BANK_NAME', 'À renseigner'),
        'rib' => env('PAYMENT_BANK_RIB', 'À renseigner'),
        'holder' => env('PAYMENT_BANK_HOLDER', 'À renseigner'),
    ],

];
