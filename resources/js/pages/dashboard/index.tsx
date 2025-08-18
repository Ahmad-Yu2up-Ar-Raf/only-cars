import MainSection from "@/components/ui/core/dashboard/report/components/overview";
import Wrapper from "@/components/ui/core/dashboard/report/wrapper";
import { PageProps } from "@/types";

export default function Dashboard({ reports }: PageProps) {
  console.log(reports)
    return (
  <>
     <Wrapper>

         <MainSection reports={reports}  />
            
        </Wrapper>
  </>
    );
}
