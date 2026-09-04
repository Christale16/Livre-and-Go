<?php

return [
    'accepted' => 'Le champ :attribute doit être accepté.',
    'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
    'email' => 'Le champ :attribute doit être une adresse email valide.',
    'in' => 'Le champ :attribute sélectionné est invalide.',
    'max' => [
        'numeric' => 'Le champ :attribute ne peut pas être supérieur à :max.',
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    ],
    'min' => [
        'numeric' => 'Le champ :attribute doit être supérieur ou égal à :min.',
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'numeric' => 'Le champ :attribute doit être un nombre.',
    'required' => 'Le champ :attribute est obligatoire.',
    'string' => 'Le champ :attribute doit être une chaîne de caractères.',
    'unique' => 'Cette valeur pour :attribute est déjà utilisée.',
    'confirmed_password' => 'La confirmation du mot de passe ne correspond pas.',

    'attributes' => [
        'name' => 'nom',
        'email' => 'email',
        'phone' => 'téléphone',
        'password' => 'mot de passe',
        'password_confirmation' => 'confirmation du mot de passe',
        'role' => 'rôle',
        'vehicle_type' => 'type de véhicule',
        'pickup_address' => 'adresse de retrait',
        'delivery_address' => 'adresse de livraison',
        'description' => 'description',
        'price' => 'prix',
        'content' => 'message',
        'status' => 'statut',
        'lat' => 'latitude',
        'lng' => 'longitude',
    ],
];
