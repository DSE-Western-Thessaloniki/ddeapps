module.exports = {
    env: {
        browser: true,
        es2021: true,
    },
    extends: [
        "plugin:vue/essential",
        "standard",
        "plugin:prettier-vue/recommended",
    ],
    parserOptions: {
        ecmaVersion: 12,
        sourceType: "module",
    },
    plugins: ["vue", "prettier"],
    rules: {
        "prettier-vue/prettier": [
            "error",
            {
                trailingComma: "es5",
            },
        ],
    },
};
