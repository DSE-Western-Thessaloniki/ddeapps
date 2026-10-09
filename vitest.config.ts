import { defineConfig } from "vitest/config";

export default defineConfig({
    test: {
        environment: "node",
        include: ["resources/ts/**/*.test.ts"],
    },
});
