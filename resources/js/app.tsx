// resources/js/app.tsx
import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';
import { configureEcho } from '@laravel/echo-react';
import { Toaster } from './components/ui/fragments/sonner';
import AppLayout from './layouts/app-layout';
import SettingsLayout from './layouts/settings/layout';

configureEcho({
    broadcaster: 'reverb',
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => {
        const page = resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx'));
        
        // Auto-apply dashboard layout untuk semua pages di dashboard
        if (name.startsWith('dashboard/') || name.startsWith('settings/') ) {
           page.then((module: any) => {
    if (!module.default.layout) {
        module.default.layout = (page: React.ReactNode) => {
            return <AppLayout>
                {name.startsWith('settings/') ? (
                    <SettingsLayout>
{page}
                    </SettingsLayout>
                ) : page}
                
            </AppLayout>;
        };
    }
});
        }
        
        return page;
    },
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <>
                <Toaster/>
                <App {...props} />
            </>
        );
    },
    progress: {
        color: 'var(--accent-foreground)',
    },
});

initializeTheme();