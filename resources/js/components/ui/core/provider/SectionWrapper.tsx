import { cn } from '@/lib/utils';
import React from 'react'

interface ProductCardsProps {
children: React.ReactNode
  className?: string;
   showBottom?: boolean
   showTop?: boolean
}

function SectionWrapper({ className , children, showBottom = false,  showTop = true  }: ProductCardsProps) {


  return (
    <section className={cn("relative mx-auto w-full   h-full  border-neutral-100 px-4 sm:px-6 md:px-8 dark:border-neutral-900", )}>
{showTop && (

<div className="absolute top-0 left-0  -z-0 h-px w-full bg-neutral-100 sm:top-6 md:top-0 dark:bg-neutral-800" />
)

}
    {showBottom &&

           <div className="absolute  left-0 z-0 h-px w-full bg-neutral-100 bottom-0 dark:bg-neutral-900" />
    }

      <div className=" max-w-7xl h-full m-auto w-full border-x-2 border-neutral-100 dark:border-neutral-800">

        <div className={cn("  relative   m-auto    mx-auto ", className)}>

{children}
        </div>
      </div>
  
        
    </section>
  );
}

export default SectionWrapper
