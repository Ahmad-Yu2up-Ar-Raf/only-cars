import { GallerySchema } from "@/lib/validations/validations";
import { Filters } from "@/types";


interface PaginatedData {
    data:  GallerySchema[];
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}


type PageProps = {
    pagination: PaginatedData;
 
    gallery: GallerySchema[]
    filters: Filters,
      flash?: {
        success?: string;
        error?: string;
      };
}

export default function Dashboard({ }: PageProps) {
    return (
  <>
  </>
    );
}
