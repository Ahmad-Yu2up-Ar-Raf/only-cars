import { Head } from '@inertiajs/react';
import { AppearanceForm } from '@/components/ui/core/layout/appearance-tabs';
import HeadingSmall from '@/components/ui/core/layout/heading-small';

;

export default function Appearance() {
    return (
        <>
            <Head title="Appearance settings" />


                <div className="space-y-6">
                    <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
                
      <AppearanceForm />
                </div>
   
        </>
    );
}
