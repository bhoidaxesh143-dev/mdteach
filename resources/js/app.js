import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import 'katex/dist/katex.min.css';
import { Ckeditor } from '@ckeditor/ckeditor5-vue'
import 'ckeditor5/ckeditor5.css'
import '../css/app.css'
import './bootstrap'
import 'ckeditor5/ckeditor5.css'
import 'katex/dist/katex.min.css'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),

    setup({ el, App, props, plugin }) {
        return createApp({
            render: () => h(App, props),
        })
            .use(plugin)
            .use(ZiggyVue)
            .component('ckeditor', Ckeditor)
            .mount(el);
    },

    progress: {
        color: '#4B5563',
    },
});
