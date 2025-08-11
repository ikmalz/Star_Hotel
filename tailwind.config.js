import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views//*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                flyAcross: {
                    "0%": {
                        transform:
                            "translateX(-150%) translateY(0) scale(0.95) rotate(5deg)",
                        opacity: "0",
                        filter: "blur(3px)",
                    },
                    "5%": { opacity: "1", filter: "blur(0)" },
                    "50%": {
                        transform:
                            "translateX(50vw) translateY(-5px) scale(1) rotate(-3deg)",
                    },
                    "95%": { opacity: "1", filter: "blur(0)" },
                    "100%": {
                        transform:
                            "translateX(150vw) translateY(5px) scale(1.05) rotate(3deg)",
                        opacity: "0",
                        filter: "blur(3px)",
                    },
                },
            },
            animation: {
                flyAcross: "flyAcross 6s ease-in-out infinite",
            },
        },
    },

    plugins: [forms],
};