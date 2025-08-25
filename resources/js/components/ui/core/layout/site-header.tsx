"use client";
import React, { useState } from "react";
import {
  motion,
  AnimatePresence,
  useScroll,
  useMotionValueEvent,
} from "motion/react";
import { cn } from "@/lib/utils";
import { HoveredLink, Menu, MenuItem, ProductItem } from "@/components/ui/fragments/Navbar";
import { Link } from "@inertiajs/react";
const navItemss = [
    {
      name: "Home",
      link: "/",
 
    },
    {
      name: "About",
      link: "/about",
     
    },
    {
      name: "Contact",
      link: "/contact",
  
    },
  ];
export const FloatingNav = ({
  navItems = navItemss,
  className,
}: {
  navItems?: {
    name: string;
    link: string;
    
  }[];
  className?: string;
}) => {

    const { scrollYProgress } = useScroll();
    const [visible, setVisible] = useState(true);
    const [delay, setDelay] = useState(true);

    useMotionValueEvent(scrollYProgress, "change", (current) => {
      // Check if current is not undefined and is a number
      if (typeof current === "number") {
        const direction = current! - scrollYProgress.getPrevious()!;
      setDelay(false);
  
      if (scrollYProgress.get() > 0.05) {
        setVisible(false);
      } else {
        if (direction < 0) {
          setVisible(true);
        } else {
          setVisible(false);
        }
      }
        
      }
    });
  return (
    <AnimatePresence mode="wait">
      <motion.div
         initial={{
            opacity: 1,
            y: -100,
          }}
          animate={{
            y: visible ? 0 : -100,
            opacity: visible ? 1 : 0,
          }}
          transition={{
            duration: delay ?  0.4 : 0.2,
            delay: delay ? 3 : 0,
          }}
        className={cn(
          "flex max-w-7xl  fixed top-6 inset-x-0 mx-auto  rounded-full  ] z-[5000] pr-2 pl-8 py-2  items-center justify-between space-x-4",
          className
        )}
      >
        <Link href="/" className="   pl-4  ">

          <img
       
       src="/Rectangle 1.svg"
       alt="Auth-Image"
  
       width={500}
       height={900}
       
       className=" inset-0 h-full w-full object-cover "
     />
        </Link>

  <Navbar/>
      </motion.div>
    </AnimatePresence>
  );
};








function Navbar({ className }: { className?: string }) {
  const [active, setActive] = useState<string | null>(null);
  return (

      <Menu active={active} setActive={setActive}>
        <MenuItem setActive={setActive} active={active} item="Services">
          <div className="flex flex-col space-y-4 text-sm">
            <HoveredLink href="/">Home</HoveredLink>
            <HoveredLink href="/events">Events</HoveredLink>
            <HoveredLink href="/merchandise">Merchandise</HoveredLink>
            <HoveredLink href="/gallery">Gallery</HoveredLink>
            <HoveredLink href="/dashboard">Sign In</HoveredLink>

          </div>
        </MenuItem>
  
      </Menu>

  );
}
