"use client";

import VerticalCutReveal from "@/components/ui/fragments/animations/vertical-cut-reveal";
import { useMediaQuery } from "@/hooks/use-media-query";
import { ArrowUpRight } from "lucide-react";
import { useCallback, useEffect, useRef, useState } from "react";
import SectionWrapper from "../provider/SectionWrapper";
import { EventsSchema } from "@/lib/validations/validations";

interface ImageData {
  id: number;
  src: string;
  alt: string;
}

const images: ImageData[] = [
  {
    id: 1,
    src: "https://images.unsplash.com/photo-1682806816936-c3ac11f65112?q=80&w=1274&auto=format&fit=crop",
    alt: "Design lead",
  },
  {
    id: 2,
    src: "https://images.unsplash.com/photo-1681063762354-d542c03bbfc5?q=80&w=1274&auto=format&fit=crop",
    alt: "Art Director",
  },
  {
    id: 3,
    src: "https://images.unsplash.com/photo-1679640034489-a6db1f096b70?q=80&w=1274&auto=format&fit=crop",
    alt: "Product Designer",
  },
  {
    id: 4,
    src: "https://images.unsplash.com/photo-1679482451632-b2e126da7142?q=80&w=1274&auto=format&fit=crop",
    alt: "UX Designer",
  },
];

type componentProps = {
  Events: EventsSchema[]
}

export default function Experience3({ Events } : componentProps) {
  const isDesktop = useMediaQuery("(min-width: 768px)");
  const [activeImage, setActiveImage] = useState<EventsSchema | null>(null);
  const [cursorPosition, setCursorPosition] = useState({ x: 0, y: 0 });
  const [opacity, setOpacity] = useState(0);
  const [scale, setScale] = useState(0.8);
  const [isVisible, setIsVisible] = useState(false);
  const timeoutRef = useRef<NodeJS.Timeout | null>(null);
  const hideTimeoutRef = useRef<NodeJS.Timeout | null>(null);
  const requestRef = useRef<number | null>(null);
  const prevCursorPosition = useRef({ x: 0, y: 0 });

  const heroRef = useRef<HTMLDivElement>(null);

  const handleMouseMove = useCallback((e: MouseEvent) => {
    const { clientX, clientY } = e;
    const dx = clientX - prevCursorPosition.current.x;
    const dy = clientY - prevCursorPosition.current.y;

    // Apply easing to the cursor movement
    const easeAmount = 0.15;
    const newX = prevCursorPosition.current.x + dx * easeAmount;
    const newY = prevCursorPosition.current.y + dy * easeAmount;

    setCursorPosition({ x: newX, y: newY });
    prevCursorPosition.current = { x: newX, y: newY };
  }, []);

  useEffect(() => {
    const updateCursorPosition = (e: MouseEvent) => {
      if (requestRef.current) return;
      requestRef.current = requestAnimationFrame(() => {
        handleMouseMove(e);
        requestRef.current = null;
      });
    };

    window.addEventListener("mousemove", updateCursorPosition);
    return () => {
      window.removeEventListener("mousemove", updateCursorPosition);
      if (requestRef.current) cancelAnimationFrame(requestRef.current);
    };
  }, [handleMouseMove]);

  const handleImageHover = useCallback(
    (image: EventsSchema) => {
      if (hideTimeoutRef.current) {
        clearTimeout(hideTimeoutRef.current);
        hideTimeoutRef.current = null;
      }

      if (activeImage !== image) {
        setActiveImage(image);
        setIsVisible(true);

        if (timeoutRef.current) clearTimeout(timeoutRef.current);

        timeoutRef.current = setTimeout(() => {
          setOpacity(1);
          setScale(1);
        }, 100);
      } else if (!isVisible) {
        setIsVisible(true);
        setOpacity(1);
        setScale(1);
      }
    },
    [activeImage, isVisible],
  );

  const handleMouseLeave = useCallback(() => {
    if (timeoutRef.current) {
      clearTimeout(timeoutRef.current);
      timeoutRef.current = null;
    }

    setOpacity(0);
    setScale(0.8);

    hideTimeoutRef.current = setTimeout(() => {
      setIsVisible(false);
      setActiveImage(null);
    }, 400); // Match the transition duration
  }, []);

  useEffect(() => {
    return () => {
      if (timeoutRef.current) clearTimeout(timeoutRef.current);
      if (hideTimeoutRef.current) clearTimeout(hideTimeoutRef.current);
    };
  }, []);

  return (
    <SectionWrapper className="mx-auto space-y-10  min-h-dvh w-full shadow-sm py-10 px-8">
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
      <div
        ref={heroRef}
        className="relative w-full max-w-7xl mx-auto"
        onMouseLeave={handleMouseLeave}
      >
        {Events.map((image, index) => (
          <div
            key={index}
            className={`p-4 group cursor-pointer relative sm:flex border-t border-accent-foreground items-center justify-between transition-all duration-300 ease-out`}
            onMouseEnter={() => handleImageHover(image)}
          >
            {!isDesktop && (
              <img
                src={`${image?.cover_image}`}
                className="sm:w-32 sm:h-20 w-full grayscale-0 h-52 object-cover rounded-md"
                alt="mobileImg"
              />
            )}
            <div className="text-left sm:py-3 py-2">
              <h2
                className={`uppercase md:text-4xl sm:text-2xl text-xl font-medium leading-[100%] relative transition-all duration-300 ease-out ${
                  activeImage?.id === image?.id && opacity > 0.5
                    ? "mix-blend-difference z-20 bg-gray-300 text-accent-foreground px-2 italic"
                    : ""
                }`}
              >
                {image.title}
              </h2>
              <p className="text-sm font-normal line-clamp-1">{image.deskripsi}</p>
            </div>
            <button
              className={`sm:block hidden p-4 rounded-full transition-all duration-300 ease-out ${
                activeImage?.id === image?.id && opacity > 0.5
                  ? "mix-blend-difference z-20 bg-gray-300 text-accent-foreground"
                  : ""
              }`}
            >
              <ArrowUpRight className="w-12 h-12" />
            </button>
            <div
              className={`h-[2px] bg-accent-foreground absolute top-0 left-0 transition-all duration-500 ease-out ${
                activeImage?.id === image?.id && opacity > 0.3
                  ? "w-full"
                  : "w-0"
              }`}
            />
          </div>
        ))}
        {isDesktop && activeImage && isVisible && (
          <img
            src={`${activeImage?.cover_image}`}
            alt={activeImage.title}
            className="fixed dark:bg-gray-950 bg-white object-cover pointer-events-none z-10 w-[300px] h-[400px] rounded-lg"
            style={{
              left: `${cursorPosition.x}px`,
              top: `${cursorPosition.y}px`,
              transform: `translate(-50%, -50%) scale(${scale})`,
              opacity: opacity,
              transition:
                "opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)",
            }}
          />
        )}
      </div>
    </SectionWrapper>
  );
}
