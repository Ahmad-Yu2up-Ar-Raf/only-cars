
import { EventDataTable } from "@/components/ui/core/dashboard/events/table/EventsDataTable";
import { EventsSchema } from "@/lib/validations/validations";
import { Filters, PaginatedData } from "@/types";




type PageProps = {
    pagination: PaginatedData;
 
    events: EventsSchema[]
    filters: Filters,
      flash?: {
        success?: string;
        error?: string;
      };
}

export default function Dashboard({ filters , events, pagination}: PageProps) {
    return (
  <>
    <header className="flex flex-col gap-0.5">
    <h2 className="text-3xl font-bold tracking-tight font-sans">Events Management</h2>
    <p className="text-muted-foreground">Here is your events list. Manage your events here.</p>
  </header>

  <EventDataTable Events={events} PaginatedData={pagination} filters={filters}/>
  </>
    );
}
