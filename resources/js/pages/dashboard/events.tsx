import { CreateEventsSheet } from "@/components/ui/core/dashboard/events/create-sheet-events";
import { EventsSchema } from "@/lib/validations/validations";
import { Filters } from "@/types";


interface PaginatedData {
    data:  EventsSchema[];
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
 
    events: EventsSchema[]
    filters: Filters,
      flash?: {
        success?: string;
        error?: string;
      };
}

export default function Dashboard({ }: PageProps) {
    return (
  <>
  <CreateEventsSheet/>
  </>
    );
}
