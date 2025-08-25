"use client";
import {
  Accordion,
  AccordionHeader,
  AccordionItem,
  AccordionPanel,
} from "@/components/ui/fragments/animations/accordion";
import { TimelineContent } from "@/components/ui/fragments/animations/timeline-animation";
import { ArrowUpRight, Plus } from "lucide-react";
import { useRef } from "react";
import VerticalCutReveal from "../../fragments/animations/vertical-cut-reveal";
import { Link } from "@inertiajs/react";
import { EventsSchema } from "@/lib/validations/validations";

type ExperienceItem = {
  company: string;
  year: string;
  title: string;
  position: string;
  imgSrc: string;
  tags: string[];
  answer: string;
};

// const experienceData: ExperienceItem[] = [
//   {
//     company: "ISSUE/N17",
//     year: "2020-2021",
//     title: "AI-Driven Design Systems",
//     position: "Design Director",
//     imgSrc:
//       "https://images.unsplash.com/photo-1751554933476-d029737d58b2?q=80&w=880&auto=format&fit=crop",
//     tags: ["AI", "Design", "Development"],
//     answer:
//       "Led the creation of AI-powered design workflows, integrating predictive analytics and automated prototyping to speed up the creative process.",
//   },
//   {
//     company: "ISSUE/N17",
//     year: "2019-2020",
//     title: "Creative Automation Platform",
//     position: "Senior Designer",
//     imgSrc:
//       "https://images.unsplash.com/photo-1752350434868-af7431a9f14b?q=80&w=880&auto=format&fit=crop",
//     tags: ["AI", "Design", "Development"],
//     answer:
//       "Designed and implemented automated creative tools, reducing repetitive tasks by 40% and enhancing cross-team collaboration.",
//   },
//   {
//     company: "ISSUE/N17",
//     year: "2018-2019",
//     title: "Product Experience Redesign",
//     position: "Product Designer",
//     imgSrc:
//       "https://images.unsplash.com/photo-1751704549146-6cae1f348143?q=80&w=880&auto=format&fit=crop",
//     tags: ["AI", "UX", "UI"],
//     answer:
//       "Redesigned the user experience for the company’s flagship platform, improving retention by 25% and integrating advanced personalization features.",
//   },
//   {
//     company: "ISSUE/N17",
//     year: "2017-2018",
//     title: "Smart Interface Guidelines",
//     position: "Design Lead",
//     imgSrc:
//       "https://images.unsplash.com/photo-1751704549146-6cae1f348143?q=80&w=880&auto=format&fit=crop",
//     tags: ["Design", "Strategy", "AI"],
//     answer:
//       "Developed scalable design guidelines for AI-integrated products, ensuring consistent branding and usability across platforms.",
//   },
//   {
//     company: "ISSUE/N17",
//     year: "2015-2017",
//     title: "Brand Identity Overhaul",
//     position: "Art Director",
//     imgSrc:
//       "https://images.unsplash.com/photo-1648348329481-93f85519a868?q=80&w=687&auto=format&fit=crop",
//     tags: ["Branding", "Design", "AI"],
//     answer:
//       "Directed a complete brand identity refresh, incorporating data-driven insights to guide visual storytelling.",
//   },
//   {
//     company: "ISSUE/N17",
//     year: "2013-2015",
//     title: "Cross-Platform UI Components",
//     position: "Senior Designer",
//     imgSrc:
//       "https://images.unsplash.com/photo-1654910971111-836ac0c213ae?q=80&w=678&auto=format&fit=crop",
//     tags: ["UI", "Design", "Development"],
//     answer:
//       "Created reusable UI components and frameworks adaptable for both web and mobile platforms, streamlining development cycles.",
//   },
//   {
//     company: "ISSUE/N17",
//     year: "2012-2013",
//     title: "Early Product Design Concepts",
//     position: "Designer",
//     imgSrc:
//       "https://images.unsplash.com/photo-1635244621620-ccadff2eb29d?q=80&w=880&auto=format&fit=crop",
//     tags: ["Design", "Prototyping", "UI"],
//     answer:
//       "Designed early prototypes for innovative digital products, laying the foundation for future AI-powered solutions.",
//   },
// ];


type compoentProps = {
  experienceData : EventsSchema[]
}

export default function Experience7( { experienceData}: compoentProps) {
  const heroRef = useRef<HTMLDivElement>(null);
  const revealVariants = {
    visible: (i: number) => ({
      y: 0,
      opacity: 1,
      filter: "blur(0px)",
      transition: {
        delay: i * 0.1,
        duration: 0.5,
      },
    }),
    hidden: {
      filter: "blur(20px)",
      y: 40,
      opacity: 0,
    },
  };
  return (
    <div
      className="sm:p-10 p-6 mx-auto  min-h-screen w-full shadow-sm"
      ref={heroRef}
    >
     <article className="max-w-7xl mx-auto sm:flex justify-between items-end">
        <h1 className="xl:text-[10rem] lg:text-8xl md:text-7xl text-6xl text-accent-foreground pt-4 lg:-space-y-10 -space-y-4">
          <VerticalCutReveal
            splitBy="characters"
            staggerDuration={0.05}
            staggerFrom="first"
            transition={{
              type: "spring",
              stiffness: 200,
              damping: 21,
            }}
          >
            Work
          </VerticalCutReveal>
          <VerticalCutReveal
            splitBy="characters"
            staggerDuration={0.05}
            containerClassName="lg:pl-32 md:pl-16 pl-6 leading-[140%]"
            staggerFrom="first"
            transition={{
              type: "spring",
              stiffness: 200,
              damping: 21,
            }}
          >
            Stage*
          </VerticalCutReveal>
        </h1>
        <div className="sm:w-96 space-y-1.5 sm:pt-0 pt-4">
  <p className="text-sm font-semibold text-end">
    Upcoming & Live Events
  </p>
  <VerticalCutReveal
    splitBy="words"
    staggerDuration={0.1}
    staggerFrom="first"
    reverse={true}
    wordLevelClassName="text-xs lg:text-base text-justify"
    transition={{
      type: "spring",
      stiffness: 250,
      damping: 30,
      delay: 0,
    }}
  >
    Stay updated with our latest events, workshops, and live sessions. 
    Join us to connect, learn, and experience unforgettable moments 
    with our community.
  </VerticalCutReveal>
</div>

      </article>

      <div className="mt-3 max-w-7xl mx-auto">
        <Accordion>
          {experienceData.map((item, index) => (
            <AccordionItem
              key={index}
              value={`item-${index}`}
              className="mb-0 rounded-none bg-transparent w-full"
            >
              <TimelineContent
                as="div"
                animationNum={index}
                timelineRef={heroRef}
                customVariants={revealVariants}
              >
                <AccordionHeader
                  customIcon
                  className=" border-t-2 gap-4 border-accent-foreground hover:no-underline px-4 py-2 flex relative data-[active]:bg-accent-foreground text-accent-foreground  sm:text-base text-sm group-data-[active]:text-accent"
                >
                  <span className="font-medium sm:text-xl text-xs uppercase">
                  ISSUE/N{item.id}
                  </span>
                  <p className="sm:text-xl text-sm space-x-2 gap-10 sm:w-96 font-medium">
                    {item.title}
                  </p>
                  <span className="relative group-data-[active]:rotate-90 text-neutral-600 p-2 -translate-x-1 rounded-xl">
                    <Plus className="group-data-[active]:rotate-90 transition-all duration-300" />
                  </span>
                </AccordionHeader>
              </TimelineContent>
              <AccordionPanel
                className="space-y-4 w-full mx-auto bg-accent-foreground data-[active]:bg-accent-foreground px-0 "
                articleClassName="pt-2 px-0 bg-accent-foreground sm:p-10 p-4 rounded-lg"
              >
                <div className="gap-4 justify-between grid sm:grid-cols-2">
                  <div className="w-[80%] space-y-10">
                    <span className="flex flex-col space-y-2">
                      <span className="text-sm sm:text-base italic font-normal">
                        ({item.title})
                      </span>
                      <span className="sm:text-xl uppercase font-medium">
                        {item.location}
                      </span>
                    </span>
                    <p className="text-sm sm:text-base">{item.deskripsi}</p>
                    {/* <div className="flex gap-2 text-sm">
                      {item.tags.map((tag, index) => (
                        <span
                          key={index}
                          className="px-2 py-1 rounded-md bg-neutral-200 text-accent-foreground  border border-neutral-300"
                        >
                          {tag}
                        </span>
                      ))}
                    </div> */}
                  </div>
                  <div className="relative">
                    <img
                      src={`${item.cover_image}`}
                      alt={item.title}
                      className="w-full sm:h-96 h-64 object-cover rounded-md"
                    />
                    <Link href={`events/${item.id}`} className="absolute bottom-4 left-4 sm:w-20 sm:h-20 w-16 h-16 bg-white z-10 rounded-lg flex items-center justify-center">
                      <ArrowUpRight className="text-accent sm:h-12 sm:w-12 h-8 w-8" />
                    </Link>
                  </div>
                </div>
              </AccordionPanel>
            </AccordionItem>
          ))}
        </Accordion>
      </div>
    </div>
  );
}
