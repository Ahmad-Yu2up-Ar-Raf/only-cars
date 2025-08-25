import { cn } from "@/lib/utils";

  export default function GradientText ({
    children,
    className,
  }: {
    children: React.ReactNode;
    className?: string;
  }) {
    return(
    <span
      className={cn(
        'from-primary dark:from-primary bg-gradient-to-r via-rose-400 to-rose-300 bg-clip-text text-transparent dark:via-rose-300 dark:to-red-400',
        className,
      )}
    >
      {children}
    </span>
  )}