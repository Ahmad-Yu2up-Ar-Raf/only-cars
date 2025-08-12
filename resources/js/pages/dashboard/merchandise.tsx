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
 
    merchandise: MerchSchema[]
    filters: Filters,
      flash?: {
        success?: string;
        error?: string;
      };
}

export default function Dashboard({ merchandise }: PageProps) {
  console.log(merchandise)
    return (
  <>
  </>
    );
}
