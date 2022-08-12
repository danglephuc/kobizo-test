module.exports = {
    env: {
        browser: true,
        node: true,
        es6: true,
        jquery: true
    },
    extends: [
        'eslint:recommended',
        "plugin:react/recommended",
        'plugin:prettier/recommended',
    ],
    "parserOptions": {
        "sourceType": "module",
        "ecmaVersion": 9,
    },
    'ignorePatterns': ['**/reportWebVitals.js'],
    "rules": {
        "prettier/prettier": [
            "error", {
                "endOfLine": "auto"
            }
        ]
    }
}
