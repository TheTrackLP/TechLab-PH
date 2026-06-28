import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { createPinia } from "pinia";
import { ZiggyVue } from "ziggy-js";

import "admin-lte/dist/css/adminlte.min.css";
import "admin-lte/dist/js/adminlte.min.js";
import "bootstrap-icons/font/bootstrap-icons.css";
import "@fortawesome/fontawesome-free/css/all.min.css";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .use(ZiggyVue)
            .mount(el);
    },
});
