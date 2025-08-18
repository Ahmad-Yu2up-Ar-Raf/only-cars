import { MerchandiseDataTable } from "@/components/ui/core/dashboard/merch/table/MerchDataTable";
import { MerchSchema } from "@/lib/validations/validations";
import { Filters } from "@/types";


interface PaginatedData {
    data:  MerchSchema[];
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
 
    Merchandise: MerchSchema[]
    filters: Filters,
      flash?: {
        success?: string;
        error?: string;
      };
}

export default function Dashboard({ Merchandise, pagination, filters }: PageProps) {
  // console.log(merchandise)
    return (
  <>
   <header className="flex flex-col gap-0.5">
      <h2 className="text-3xl font-bold tracking-tight font-sans">Merchandise Management</h2>
      <p className="text-muted-foreground">Here is your merchandise list. Manage your merchandise here.</p>
    </header>
  
    <MerchandiseDataTable Merchandise={Merchandise} PaginatedData={pagination} filters={filters}/>
  </>
    );
}
