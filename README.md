# Πλατφόρμα εφαρμογών ΔΔΕ Δυτ. Θεσσαλονίκης

## Πρόσθετες μεταβλητές περιβάλλοντος για το .env
Για να δουλέψουν σωστά τα πακέτα ckeditor, fontawesome και για τη δημιουργία PDF χρειάζεται μέσα στο .env να ορίσουμε επιπλέον μεταβλητές:
 - MIX_APP_URL: Πρέπει να είναι ίσο με την πλήρη διεύθυνση της ιστοσελίδας μας με http ή https ανάλογα με τις ρυθμίσεις του webserver μας (πχ. https://example.com/).
 - MIX_APP_DIR: Αν η εφαρμογή μας βρίσκεται σε υποφάκελο μέσα στον webserver μας τότε η μεταβλητή αυτή θα πρέπει να έχει την διαδρομή στην οποία βρίσκεται η εφαρμογή μας (πχ. αν η εφαρμογή είναι στο https://example.com/applications/ddeapps/ η μεταβλητή MIX_APP_DIR θα πρέπει να είναι ίση με "applications/ddeapps").
 - LARAVEL_PDF_DRIVER: Ο οδηγός (driver) για τη δημιουργία PDF μέσω του spatie/laravel-pdf (πχ. `gotenberg`, `browsershot`). Αν δεν οριστεί χρησιμοποιείται ο `browsershot`.
 - GOTENBERG_URL: Η διεύθυνση του Gotenberg server (πχ. `http://gotenberg:3000`). Απαιτείται μόνο όταν `LARAVEL_PDF_DRIVER=gotenberg`.
 - QUEUE_CONNECTION: Πρέπει να είναι `database` ώστε η δημιουργία του ZIP αρχείου αλληλογραφίας να εκτελείται στο φόντο με ένδειξη προόδου. Απαιτείται η εκκίνηση της υπηρεσίας `queue-worker` (πχ. `vendor/bin/sail up -d queue-worker`) και τα migrations των πινάκων `jobs` και `mail_merge_exports`.
