"use client";
import React from "react";
import { motion } from "framer-motion";
import {Link} from "@inertiajs/react";


const transition = {
  type: "spring",
  mass: 0.5,
  damping: 11.5,
  stiffness: 100,
  restDelta: 0.001,
  restSpeed: 0.001,
};

export const MenuItem = ({
  setActive,
  active,
  item,
  children,
}: {
  setActive: (item: string) => void;
  active: string | null;
  item: string;
  children?: React.ReactNode;
}) => {

const isHamburgerOpen = active == item

    console.log(active)
  return (
    <div onMouseEnter={() => setActive(item)} className="relative ">
      <motion.div
        transition={{ duration: 0.3 }}
        className={`hamburger-menu ${isHamburgerOpen ? "open" : ""} group h-full flex flex-col items-center justify-center cursor-pointer gap-[6px] order-2 md:order-none`}
      >
          
          <div
              className={`hamburger-line w-[35px] h-[1px] bg-current transition-[transform,opacity,margin] duration-300 ease-linear [transform-origin:50%_50%]  group-hover:opacity-75`}
            />
            <div
              className={`hamburger-line w-[35px] h-[1px] bg-current transition-[transform,opacity,margin] duration-300 ease-linear [transform-origin:50%_50%] group-hover:opacity-75`}
            />
            <div
              className={`hamburger-line w-[35px] h-[1px] bg-current transition-[transform,opacity,margin] duration-300 ease-linear [transform-origin:50%_50%]  group-hover:opacity-75`}
            />
   <span className=" sr-only">

        {item}
   </span>
      </motion.div>

      {active !== null && (
        <motion.div
          initial={{ opacity: 0, scale: 0.85, y: 10 }}
          animate={{ opacity: 1, scale: 1, y: 0 }}
          transition={transition}
        >
          {active === item && (
            <div className="absolute top-[calc(100%_+_0.2rem)] left-1/2 transform -translate-x-40 pt-4">
              <motion.div
                transition={transition}
                layoutId="active" // layoutId ensures smooth animation
                className="bg-accent-foreground/30 backdrop-blur-sm rounded-2xl overflow-hidden border border-black/[0.2] dark:border-white/[0.2] shadow-xl"
              >
                <motion.div
                  layout // layout ensures smooth animation
                  className="w-max h-full p-4 pr-10"
                >
                  {children}
                </motion.div>
              </motion.div>
            </div>
          )}
        </motion.div>
      )}
    </div>
  );
};

export const Menu = ({
  setActive,
  children,
  active
}: {
  setActive: (item: string | null) => void;
  children: React.ReactNode;
  active: string | null;
}) => {
    const isItActive = active != null
  return (
    <nav
       onMouseLeave={() =>setActive(null)}
      className="relative rounded-full  pr-7 flex justify-center space-x-4  py-6 "
    >
      {children}
    </nav>
  );
};

export const ProductItem = ({
  title,
  description,
  href,

}: {
  title: string;
  description: string;
  href: string;

}) => {
  return (
    <Link href={href} className="flex space-x-2">

      <div>
        <h4 className="text-xl font-bold mb-1 text-black dark:text-white">
          {title}
        </h4>
        <p className="text-neutral-700 text-sm max-w-[10rem] dark:text-neutral-300">
          {description}
        </p>
      </div>
    </Link>
  );
};

export const HoveredLink = ({ children, ...rest }: any) => {
  return (
    <Link
      {...rest}
      className="text-neutral-700 dark:text-neutral-200 hover:text-black text-base hover:underline underline-offset-4 "
    >
      {children}
    </Link>
  );
};
