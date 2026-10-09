import { describe, expect, it } from "vitest";
import { namesMatch, normalizeForComparison } from "./recipientMatch";

describe("normalizeForComparison", () => {
    it("trims surrounding whitespace", () => {
        expect(normalizeForComparison("  ΛΕΞΗ\n")).toBe("ΛΕΞΗ");
    });

    it("strips Greek accents via NFD", () => {
        expect(normalizeForComparison("ΛΈΞΗ")).toBe("ΛΕΞΗ");
    });
});

describe("namesMatch", () => {
    it("matches names differing only in tonos", () => {
        expect(namesMatch("ΛΕΞΗ", "ΛΈΞΗ")).toBe(true);
    });

    it("matches names differing only in case", () => {
        expect(namesMatch("ΛΕΞΗ", "λεξη")).toBe(true);
    });

    it("matches iota with and without dialytika", () => {
        expect(namesMatch("ΙΩΑΝΝΗΣ", "Ιωάννης")).toBe(true);
        expect(namesMatch("ΚΑΛΑΙΣΚΟΥ", "Καλαΐσκου")).toBe(true);
    });

    it("matches final sigma with plain sigma", () => {
        expect(namesMatch("πετρος", "πετροσ")).toBe(true);
    });

    it("matches names with trailing whitespace differences", () => {
        expect(namesMatch("ΛΕΞΗ ", "ΛΕΞΗ")).toBe(true);
        expect(namesMatch("ΛΕΞΗ", "ΛΕΞΗ\n")).toBe(true);
        expect(namesMatch(" ΛΕΞΗ", "ΛΕΞΗ")).toBe(true);
    });

    it("does not match Greek letters against latin lookalikes", () => {
        expect(namesMatch("ΛΕΞΗ", "ΛEXH")).toBe(false);
    });

    it("does not match hyphen against space", () => {
        expect(namesMatch("ΠΑΠΑ-ΔΟΠΟΥΛΟΣ", "ΠΑΠΑ ΔΟΠΟΥΛΟΣ")).toBe(false);
    });

    it("does not match differing internal whitespace", () => {
        expect(namesMatch("Α Β", "ΑΒ")).toBe(false);
    });

    it("does not match hyphen-less variants", () => {
        expect(namesMatch("ΠΑΠΑΔΟΠΟΥΛΟΣ", "ΠΑΠΑ-ΔΟΠΟΥΛΟΣ")).toBe(false);
    });
});
