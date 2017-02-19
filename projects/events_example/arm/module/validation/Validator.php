<?php

namespace ARM\Module\Validation;

use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Database\Capsule\Manager as Capsule;

class Validator
{
    public static function make(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $translationPath = __DIR__ . '/lang';
        $translationLocale = 'pt-br';

        $translationFileLoader = new \Illuminate\Translation\FileLoader(new \Illuminate\Filesystem\Filesystem, $translationPath);
        $translator = new \Illuminate\Translation\Translator($translationFileLoader, $translationLocale);
        $validationFactory = new \Illuminate\Validation\Factory($translator);

        $capsule = \EloquentBootstrap::capsule();
        $databaseManager = $capsule->getDatabaseManager();

        $databasePresenceVerifier = new DatabasePresenceVerifier($databaseManager);
        $validationFactory->setPresenceVerifier($databasePresenceVerifier);

        $validator = $validationFactory->make($data, $rules, $messages, $customAttributes);
        return $validator;
    }
}