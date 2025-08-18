import { GalleryDataTable } from "@/components/ui/core/dashboard/gallery/table/GalleryDataTable";
import { GallerySchema } from "@/lib/validations/validations";
import { Filters, PaginatedData } from "@/types";




type PageProps = {
    pagination: PaginatedData;
 
    Gallery: GallerySchema[]
    filters: Filters,
      flash?: {
        success?: string;
        error?: string;
      };
}

export default function Dashboard({  Gallery , pagination, filters}: PageProps) {
    return (
  <>
      <header className="flex flex-col gap-0.5">
    <h2 className="text-3xl font-bold tracking-tight font-sans">Gallery Management</h2>
    <p className="text-muted-foreground">Here is your gallery list. Manage your gallery here.</p>
  </header>

  <GalleryDataTable Gallery={Gallery} PaginatedData={pagination} filters={filters}/>
  </>
    );
}
