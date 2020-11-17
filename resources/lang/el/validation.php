<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Το πεδίο :attribute must be accepted.',
    'active_url' => 'Το πεδίο :attribute δεν έχει έγκυρο URL.',
    'after' => 'Το πεδίο :attribute πρέπει να έχει μια ημερομηνία μεταγενέστερη της :date.',
    'after_or_equal' => 'Το πεδίο :attribute πρέπει να έχει μια ημερομηνία μεταγενέστερη ή ίση με :date.',
    'alpha' => 'Το πεδίο :attribute μπορεί να περίεχει μόνο γράμματα.',
    'alpha_dash' => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα, αριθμούς, παύλες and κάτω παύλα.',
    'alpha_num' => 'Το πεδίο :attribute μπορεί να περίεχει μόνο γράμματα και αριθμούς.',
    'array' => 'Το πεδίο :attribute πρέπει να είναι ένας πίνακας.',
    'before' => 'Το πεδίο :attribute πρέπει να έχει ημερομηνία προγενέστερη της :date.',
    'before_or_equal' => 'Το πεδίο :attribute πρέπει να είναι μια ημερομηνία προγενέστερη ή ίση με :date.',
    'between' => [
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min και :max.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min και :max kilobytes.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min και :max χαρακτήρες.',
        'array' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min και :max αντικείμενα.',
    ],
    'boolean' => 'Το πεδίο :attribute πρέπει να είναι αληθές ή ψευδές.',
    'confirmed' => 'Το πεδίο :attribute confirmation does not match.',
    'date' => 'Το πεδίο :attribute δεν είναι έγκυρη ημερομηνία.',
    'date_equals' => 'Το πεδίο :attribute πρέπει να είναι μια ημερομηνία ίση με :date.',
    'date_format' => 'Το πεδίο :attribute δεν ταιριάζει με την μορφή :format.',
    'different' => 'Το πεδίο :attribute και το :other θα πρέπει να είναι διαφορετικά.',
    'digits' => 'Το πεδίο :attribute πρέπει να είναι :digits ψηφία.',
    'digits_between' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min και :max ψηφία.',
    'dimensions' => 'Το πεδίο :attribute έχει μη έγκυρες διαστάσεις εικόνας.',
    'distinct' => 'Το πεδίο :attribute έχει μια διπλότυπη τιμή.',
    'email' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη διεύθυνση email.',
    'ends_with' => 'Το πεδίο :attribute πρέπει να τελειώνει με ένα από τα ακόλουθα: :values.',
    'exists' => 'Το επιλεγμένο πεδίο :attribute είναι άκυρο.',
    'file' => 'Το πεδίο :attribute πρέπει να είναι ένα αρχείο.',
    'filled' => 'Το πεδίο :attribute πρέπει να έχει τιμή.',
    'gt' => [
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι μεγαλύτερο από :value.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι μεγαλύτερο από :value kilobytes.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι μεγαλύτερο από :value χαρακτήρες.',
        'array' => 'Το πεδίο :attribute πρέπει να έχει περισσότερα από :value αντικείμενα.',
    ],
    'gte' => [
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι μεγαλύτερο από or equal :value.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι μεγαλύτερο από or equal :value kilobytes.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι μεγαλύτερο από or equal :value χαρακτήρες.',
        'array' => 'Το πεδίο :attribute πρέπει να έχει :value αντικείμενα ή περισσότερα.',
    ],
    'image' => 'Το πεδίο :attribute πρέπει να είναι εικόνα.',
    'in' => 'Το επιλεγμένο πεδίο :attribute είναι άκυρο.',
    'in_array' => 'Το πεδίο :attribute δεν υπάρχει στο :other.',
    'integer' => 'Το πεδίο :attribute πρέπει να είναι ακέραιος.',
    'ip' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη διεύθυνση IP.',
    'ipv4' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη διεύθυνση IPv4.',
    'ipv6' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη διεύθυνση IPv6.',
    'json' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη συμβολοσειρά JSON.',
    'lt' => [
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι μικρότερο από :value.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι μικρότερο από :value kilobytes.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι μικρότερο από :value χαρακτήρες.',
        'array' => 'Το πεδίο :attribute πρέπει να έχει λιγότερα από :value αντικείμενα.',
    ],
    'lte' => [
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι μικρότερο από or equal :value.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι μικρότερο από or equal :value kilobytes.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι μικρότερο από or equal :value χαρακτήρες.',
        'array' => 'Το πεδίο :attribute δεν πρέπει να έχει περισσότερα από :value αντικείμενα.',
    ],
    'max' => [
        'numeric' => 'Το πεδίο :attribute δεν μπορεί να είναι μεγαλύτερο από :max.',
        'file' => 'Το πεδίο :attribute δεν μπορεί να είναι μεγαλύτερο από :max kilobytes.',
        'string' => 'Το πεδίο :attribute δεν μπορεί να είναι μεγαλύτερο από :max χαρακτήρες.',
        'array' => 'Το πεδίο :attribute δεν μπορεί να έχει περισσότερα από :max αντικείμενα.',
    ],
    'mimes' => 'Το πεδίο :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'mimetypes' => 'Το πεδίο :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'min' => [
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι τουλάχιστον :min.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι τουλάχιστον :min kilobytes.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι τουλάχιστον :min χαρακτήρες.',
        'array' => 'Το πεδίο :attribute πρέπει να έχει τουλάχιστον :min αντικείμενα.',
    ],
    'not_in' => 'Το επιλεγμένο πεδίο :attribute είναι άκυρο.',
    'not_regex' => 'Η μορφή του πεδίου :attribute είναι άκυρη.',
    'numeric' => 'Το πεδίο :attribute πρέπει να είναι αριθμός.',
    'password' => 'Ο κωδικός είναι λάθος.',
    'present' => 'Το πεδίο :attribute πρέπει να υπάρχει.',
    'regex' => 'Η μορφή του πεδίου :attribute είναι άκυρη.',
    'required' => 'Το πεδίο :attribute απαιτείται.',
    'required_if' => 'Το πεδίο :attribute απαιτείται όταν το :other είναι :value.',
    'required_unless' => 'Το πεδίο :attribute απαιτείται εκτός αν το :other είναι :values.',
    'required_with' => 'Το πεδίο :attribute απαιτείται όταν εμφανίζεται η τιμή :values.',
    'required_with_all' => 'Το πεδίο :attribute απαιτείται όταν εμφανίζονται οι τιμές :values.',
    'required_without' => 'Το πεδίο :attribute απαιτείται όταν δεν εμφανίζονται οι τιμές :values.',
    'required_without_all' => 'Το πεδίο :attribute απαιτείται όταν δεν εμφανίζεται καμία από τις τιμές :values.',
    'same' => 'Το πεδίο :attribute και το :other πρέπει να ταυτίζονται.',
    'size' => [
        'numeric' => 'Το πεδίο :attribute πρέπει να έχει μέγεθος :size.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι :size kilobytes.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι :size χαρακτήρες.',
        'array' => 'Το πεδίο :attribute πρέπει να περιέχει :size αντικείμενα.',
    ],
    'starts_with' => 'Το πεδίο :attribute πρέπει να ξεκινάει με ένα από τα ακόλουθα: :values.',
    'string' => 'Το πεδίο :attribute πρέπει να είναι συμβολοσειρά.',
    'timezone' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη ζώνη ώρας.',
    'unique' => 'Το :attribute είναι ήδη πιασμένο.',
    'uploaded' => 'Απέτυχε η μεταφόρτωση του :attribute.',
    'url' => 'Η μορφή του :attribute είναι άκυρη.',
    'uuid' => 'Το :attribute πρέπει να είναι ένα έγκυρο UUID.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
