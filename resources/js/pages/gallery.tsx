
import { GallerySchema } from '@/lib/validations/validations';
import { Filters, PaginatedData, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';


type PageProps = {
    pagination: PaginatedData;

    Gallery: GallerySchema[]
    filters: Filters,
      flash?: {
        success?: string;
        error?: string;
      };
}


export default function Gallery({  Gallery }: PageProps) {
   

    return (
        <>
            <Head title="Gallery">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
            </Head>

        </>
    );
}
