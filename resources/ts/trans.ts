type TranslationValue =
    | string
    | TranslationDictionary
    | NestedTranslationDictionary;

/**
 * Walk the dot separated segments of the given key inside a translation
 * dictionary, returning the matching value or null when it is missing.
 */
function lookup(
    dictionary: { [key: string]: TranslationValue },
    key: string,
): TranslationValue | { [key: string]: TranslationValue } | null {
    let current:
        | TranslationValue
        | { [key: string]: TranslationValue }
        | null = dictionary;

    for (const partialKey of key.split(".")) {
        if (typeof current !== "object" || current === null) {
            return null;
        }

        current = current[partialKey] ?? null;
    }

    return current;
}

/**
 * Translate the given key.
 */
export default function __(
    key: string,
    replace?: Record<string, string>,
): string {
    let translation: string | null = null;

    try {
        const localeTranslations = window._translations?.[window._locale];

        if (localeTranslations) {
            const phpTranslation = lookup(localeTranslations.php, key);

            if (typeof phpTranslation === "string" && phpTranslation !== "") {
                translation = phpTranslation;
            } else if (localeTranslations.json?.[key]) {
                translation = localeTranslations.json[key];
            }
        }
    } catch {
        translation = null;
    }

    let result: string = translation ?? key;

    Object.entries(replace ?? {}).forEach(([placeholder, value]) => {
        result = result.replace(":" + placeholder, value);
    });

    return result;
}
