import { afterEach, describe, expect, it, vi } from "vitest";
import __ from "./trans";

const translations = {
    el: {
        php: {
            auth: {
                failed: "Τα στοιχεία εισόδου δεν ταιριάζουν.",
            },
            validation: {
                required: "Το :attribute απαιτείται.",
            },
        },
        json: {
            Zoom: "Μεγέθυνση",
            auth: "Ταυτοποίηση",
        },
    },
};

afterEach(() => {
    vi.unstubAllGlobals();
});

describe("__", () => {
    it("returns the key when the translations payload is missing", () => {
        vi.stubGlobal("window", { _locale: "el" });

        expect(() => __("Zoom")).not.toThrow();
        expect(__("Zoom")).toBe("Zoom");
    });

    it("returns the key when the current locale is not in the payload", () => {
        vi.stubGlobal("window", {
            _locale: "de",
            _translations: translations,
        });

        expect(__("Zoom")).toBe("Zoom");
    });

    it("returns a JSON translation", () => {
        vi.stubGlobal("window", {
            _locale: "el",
            _translations: translations,
        });

        expect(__("Zoom")).toBe("Μεγέθυνση");
    });

    it("resolves nested PHP group keys", () => {
        vi.stubGlobal("window", {
            _locale: "el",
            _translations: translations,
        });

        expect(__("auth.failed")).toBe(
            "Τα στοιχεία εισόδου δεν ταιριάζουν.",
        );
    });

    it("prefers the PHP translation when both sources define the key", () => {
        vi.stubGlobal("window", {
            _locale: "el",
            _translations: {
                el: {
                    php: { auth: { failed: "από php" } },
                    json: { "auth.failed": "από json" },
                },
            },
        });

        expect(__("auth.failed")).toBe("από php");
    });

    it("falls back to JSON when the PHP lookup returns a group array", () => {
        vi.stubGlobal("window", {
            _locale: "el",
            _translations: translations,
        });

        expect(__("auth")).toBe("Ταυτοποίηση");
    });

    it("replaces placeholders", () => {
        vi.stubGlobal("window", {
            _locale: "el",
            _translations: translations,
        });

        expect(__("validation.required", { attribute: "email" })).toBe(
            "Το email απαιτείται.",
        );
    });

    it("returns the key when no translation exists", () => {
        vi.stubGlobal("window", {
            _locale: "el",
            _translations: translations,
        });

        expect(__("Missing key")).toBe("Missing key");
    });
});
