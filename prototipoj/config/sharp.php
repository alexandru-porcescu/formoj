<?php

return [

    "name" => "Prototipoj",

    "locale" => env("SHARP_LOCALE", "fr_FR.UTF-8"),

    "entities" => [
        "form" => [
            "list" => \Code16\Formoj\Sharp\FormojFormSharpEntityList::class,
            "form" => \Code16\Formoj\Sharp\FormojFormSharpForm::class,
            "validator" => \Code16\Formoj\Sharp\FormojFormSharpValidator::class,
        ],
        "field" => [
            "list" => \Code16\Formoj\Sharp\FormojFieldSharpEntityList::class,
//            "form" => \Code16\Formoj\Sharp\FormojFormSharpForm::class,
//            "validator" => \Code16\Formoj\Sharp\FormojFormSharpValidator::class,
        ],
    ],

    "menu" => [
        [
            "label" => "Formulaires",
            "entities" => [
                [
                    "entity" => "form",
                    "label" => "Formulaires",
                    "icon" => "fa-list-alt"
                ],
                [
                    "entity" => "field",
                    "label" => "Champs",
                    "icon" => "fa-square-o"
                ],
            ]
        ]
    ],

    "uploads" => [
        "tmp_dir" => env("SHARP_UPLOADS_TMP_DIR", "tmp"),
        "thumbnails_dir" => env("SHARP_UPLOADS_THUMBS_DIR", "thumbnails"),
    ],

    "auth" => [
        "login_attribute" => "email",
        "password_attribute" => "password",
        "display_attribute" => "email",
    ]
];