import { AppContent } from '@/components/ui/core/layout/app-content';
import { AppShell } from '@/components/ui/core/layout/app-shell';
import { AppSidebar } from '@/components/ui/core/layout/app-sidebar';
import { AppSidebarHeader } from '@/components/ui/core/layout/app-sidebar-header';
import { usePage } from '@inertiajs/react';

import { type PropsWithChildren } from 'react';




export default function AppSidebarLayout({ children,}: PropsWithChildren<{  }>) {

  const currentPath = usePage().url;
  const pathNames = currentPath.split('/').filter(path => path)[0]

    return (
        <AppShell variant={pathNames == "dashboard" ?  "sidebar" : "header"}>
            {pathNames == "dashboard" &&

            <AppSidebar />
            }
            <AppContent variant={pathNames == "dashboard" ?  "sidebar" : "header"} className="overflow-x-hidden">
            {pathNames == "dashboard" && 
                <AppSidebarHeader />
            }
                {children}
            </AppContent>
        </AppShell>
    );
}
