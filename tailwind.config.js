/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Plus Jakarta Sans",
                    "Inter",
                    "ui-sans-serif",
                    "system-ui",
                    "-apple-system",
                    "sans-serif",
                ],
                display: [
                    "Plus Jakarta Sans",
                    "Inter",
                    "ui-sans-serif",
                    "system-ui",
                ],
            },
            colors: {
                primary: {
                    50: "#eff6ff",
                    100: "#dbeafe",
                    200: "#bfdbfe",
                    300: "#93c5fd",
                    400: "#60a5fa",
                    500: "#3b82f6",
                    600: "#2563eb",
                    700: "#1d4ed8",
                },
            },
        },
    },
    plugins: [],
    safelist: [
        // Role selector card — Superadmin active state
        'border-purple-500',
        'bg-purple-50/60',
        'ring-2',
        'ring-purple-200',
        'bg-purple-600',
        'text-purple-700',
        // Role selector card — HRD active state
        'border-[#003d7c]',
        'ring-[#003d7c]/20',
        'bg-[#003d7c]/5',
        'bg-[#003d7c]',
    ],
};
