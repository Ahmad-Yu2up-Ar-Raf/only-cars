import { cn } from "@/lib/utils";
import { Separator } from "../../fragments/separator";

export default function Heading({ title, description, className }: { title: string; description?: string, className?: string }) {
    return (
        <>
        <header className={cn(" space-y-0.5", className)}>
            <h2 className="text-xl font-semibold tracking-tight">{title}</h2>
            {description && <p className="text-sm text-muted-foreground">{description}</p>}
        </header>
        <Separator/>
        </>
    );
}
