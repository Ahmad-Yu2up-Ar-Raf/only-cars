import { SidebarProvider } from '@/components/ui/fragments/sidebar';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

import Footer4Col from './site-footer';



interface AppShellProps {
    children: React.ReactNode;
    variant?: 'header' | 'sidebar';
}

export function AppShell({ children, variant = 'header' }: AppShellProps) {
    const isOpen = usePage<SharedData>().props.sidebarOpen;

    if (variant === 'header') {
        return <div className="flex min-h-dvh w-full content-center flex-col">
         
            <main className='m-auto w-full min-h-dvh'>

            {children}

            </main>
            <Footer4Col/>
            
            </div>;
    }

    return <SidebarProvider defaultOpen={isOpen}>{children}</SidebarProvider>;
}
