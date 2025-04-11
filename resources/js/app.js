import "../css/app.css";
import "./bootstrap";
import "primeicons/primeicons.css";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

import PrimeVue from "primevue/config";
import Aura from "@primevue/themes/aura";
import ToastService from "primevue/toastservice";
import ConfirmationService from "primevue/confirmationservice";
import { definePreset } from "@primevue/themes";

// Custom theme preset
// const MyPreset = definePreset(Aura, {
//     semantic: {
//         primary: {
//             50: "#f0f9ff",
//             100: "#e0f2fe",
//             200: "#bae6fd",
//             300: "#7dd3fc",
//             400: "#38bdf8",
//             500: "#0ea5e9",
//             600: "#0284c7",
//             700: "#0369a1",
//             800: "#075985",
//             900: "#0c4a6e",
//             950: "#082f49",
//         },
//     },
// });
const MyPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: "#fffbeb",
            100: "#fef3c7",
            200: "#fde68a",
            300: "#fcd34d",
            400: "#fbbf24",
            500: "#f59e0b",
            600: "#d97706",
            700: "#b45309",
            800: "#92400e",
            900: "#78350f",
            950: "#451a03",
        },
    },
});

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: MyPreset,
                    options: {
                        darkModeSelector: false,
                    },
                },
            })
            .use(ToastService)
            .use(ConfirmationService)
            .mount(el);
    },
    progress: {
        // color: "#4B5563",
        color: "#fbbf24",
    },
});
