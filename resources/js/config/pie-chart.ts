import { ChartConfig } from "@/components/ui/fragments/chart";

export const chartConfig = {
  count: {
    label: "Motor Count",
  },
  cancelled: {
    label: "Cancelled",
    color: "var(--chart-1)",
  },
  finished: {
    label: "Finished", 
    color: "var(--chart-2)",
  },
  ongoing: {
    label: "Ongoing",
    color: "var(--chart-3)",
  },
  upcoming: {
    label: "Upcoming",
    color: "var(--chart-4)",
  },
  inactive: {
    label: "Inactive", 
    color: "var(--chart-5)",
  },
  available: {
    label: "Available",
    color: "var(--chart-3)",
  },
  sold: {
    label: "Sold",
    color: "var(--chart-4)",
  },
  notavailable: {
    label: "Not Available",
    color: "var(--chart-5)",
  },
  public: {
    label: "Public", 
    color: "var(--chart-2)",
  },
  private: {
    label: "Private",
    color: "var(--chart-3)",
  },

  other: {
    label: "Other",
    color: "var(--chart-5)",
  },

} satisfies ChartConfig