import DashboardSkeleton from '@/components/ui/fragments/DashboardSkeleton';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';

import { Head, usePage } from '@inertiajs/react';
import { type ReactNode } from 'react';

interface AppLayoutProps {
    children?: ReactNode;
  
}




export default ({ children, ...props }: AppLayoutProps) => 
{
      const paths = usePage().url
  const pathNames = paths.split('/').filter(path => path)


 const currentPath = pathNames.length - 1 
    return(
    <AppLayoutTemplate  {...props}>
        <Head title={pathNames[currentPath]}/>
           <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">

        {children != null ? children: (
            <DashboardSkeleton/>
        )}
           </div>
    </AppLayoutTemplate>
);
}
