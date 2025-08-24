import About from '@/components/ui/core/section/About';
import Events from '@/components/ui/core/section/Events';
import Hero from '@/components/ui/core/section/Hero';
import { EventsSchema } from '@/lib/validations/validations';
import { Filters, PaginatedData, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';


type PageProps = {
    pagination: PaginatedData;
 
    events: EventsSchema[]
    filters: Filters,
      flash?: {
        success?: string;
        error?: string;
      };
}


export default function Welcome({  events }: PageProps) {
   

    return (
        <>
            <Head title="Welcome">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
            </Head>
      <Hero/>
      <About/>
      <Events Events={events}/>
        </>
    );
}
