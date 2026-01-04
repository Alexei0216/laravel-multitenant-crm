import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'
import MainLayout from './Pages/layouts/MainLayout'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
        const page = pages[`./Pages/${name}.jsx`]

        if (!page.default.layout && !name.startsWith("Public/")) {
            page.default.layout = page => <MainLayout>{page}</MainLayout>;
        }

        return page;
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />)
    },
})  