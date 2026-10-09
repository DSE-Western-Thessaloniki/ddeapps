const collator = new Intl.Collator("el-GR", { sensitivity: "base" });

const combiningMarks = /[\u0300-\u036f]/g;

export function normalizeForComparison(value: string): string {
    return value.trim().normalize("NFD").replace(combiningMarks, "");
}

export function namesMatch(a: string, b: string): boolean {
    return collator.compare(normalizeForComparison(a), normalizeForComparison(b)) === 0;
}
