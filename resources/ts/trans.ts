import _ from "lodash";

/**
 * Translate the given key.
 */
export default function __(key: string, replace?: string[]) {
    let translation: string | null = null;
    let translationNotFound = true;
    let translationObject:
        | NestedTranslationDictionary
        | TranslationDictionary
        | string
        | null = null;

    try {
        let keys = key.split(".");
        keys.forEach(function (partial_key) {
            translationObject =
                window._translations[window._locale].php[partial_key];
        });

        if (typeof translationObject === "string") {
            translation = translationObject;
        }

        if (translation) {
            translationNotFound = false;
        }
    } catch (e) {
        translation = key;
    }

    if (translationNotFound) {
        translation = window._translations[window._locale].json[key]
            ? window._translations[window._locale].json[key]
            : key;
    }

    _.forEach(replace, (value, key) => {
        if (typeof translation === "string") {
            translation = translation.replace(":" + key, value);
        }
    });

    return translation;
}
