import { AppContent } from '@/components/ui/core/layout/app-content';
import { AppHeader } from '@/components/ui/core/layout/app-header';
import { AppShell } from '@/components/ui/core/layout/app-shell';
import { type BreadcrumbItem } from '@/types';
import type { PropsWithChildren } from 'react';

export default function AppHeaderLayout({ children }: PropsWithChildren<{ }>) {
    return (
        <AppShell>
            <AppHeader  />
            <AppContent>{children}</AppContent>
        </AppShell>
    );
}
