import { AxiosInstance } from "axios";
import Fuse from "fuse.js";

declare global {
    type TranslationDictionary = { [key: string]: string };
    type NestedTranslationDictionary = { [key: string]: TranslationDictionary };

    type XLSX_JSON = {
        id: number;
        [key: string]: string | number;
    };

    interface Window {
        axios: AxiosInstance;
        Fuse: Fuse;
        $: JQueryStatic;
        CKEDITOR_BASEPATH: string;
        itemsArray: { id: number; title: string }[];
        _locale: string;
        _translations: {
            [key: string]: {
                json: TranslationDictionary;
                php: {
                    [key: string]:
                        | NestedTranslationDictionary
                        | TranslationDictionary
                        | string;
                };
            };
        };
    }
}
