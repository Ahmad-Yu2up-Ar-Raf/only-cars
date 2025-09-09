'use client';
// inspired by tom is loading
import React, { useRef, useState } from 'react';
import { motion, useInView } from 'motion/react';
import { GallerySchema } from '@/lib/validations/validations';
import SectionWrapper from '../core/provider/SectionWrapper';
import VerticalCutReveal from './animations/vertical-cut-reveal';


type ComponentProps = {
    items: GallerySchema[]
}

function UnsplashGrid({items }:ComponentProps) {
  const [selected, setSelected] = useState(null);

  return (
    <>
      <SectionWrapper className='container mx-auto p-0 py-20 '>
             <article className="max-w-7xl pb-3 px-5 border-b mx-auto sm:flex justify-between items-end">
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
            Events
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
            Gallery*
          </VerticalCutReveal>
        </h1>
        <div className="sm:w-96 space-y-1.5 sm:pt-0 pt-4">
  <p className="text-sm font-semibold text-end">
    New Product Comingg
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
    Explore our exclusive collection of merchandise designed for fans and creators alike. From stylish apparel to unique accessories, discover items that showcase your passion.
  </VerticalCutReveal>
</div>

      </article>
        <div className='columns-2 md:columns-3 2xl:columns-4 gap-4 p-10 '>
          <>
            {items.map((item, index) => (
              <ImageItem
                key={item.id}
                item={item}
                index={index}
                setSelected={setSelected}
              />
            ))}
          </>
        </div>
      </SectionWrapper>
    </>
  );
}
interface Item {
  id: number;
  url: string;
  title: string;
}

interface ImageItemProps {
  item: GallerySchema;
  index: number | string;
  setSelected: any;
}

function ImageItem({ item, index, setSelected }: ImageItemProps) {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true });

  return (
    <motion.figure
      whileTap={{ scale: 0.9 }}
      initial='hidden'
      animate={isInView && 'visible'}
      ref={ref}
      className="inline-block group w-full rounded-md  relative dark:bg-black bg-white overflow-hidden before:absolute before:top-0 before:content-[''] before:h-full before:w-full hover:before:bg-gradient-to-t dark:before:from-gray-900  before:from-gray-200/90 before:from-5% before:to-transparent before:to-90% cursor-pointer"
      onClick={() => setSelected(item)}
    >
      <motion.img
        layoutId={`card-${item.id}`}
        whileHover={{ scale: 1.025 }}
        src={`${item.cover_image}`}
        className='w-full bg-base-100 shadow-xl image-full cursor-pointer'
      />
      <div className='flex flex-wrap mt-2 absolute bottom-0 left-0 p-2 group-hover:opacity-100 opacity-0 font-semibold '>
        <h1>{item.title}</h1>
      </div>
    </motion.figure>
  );
}

export default UnsplashGrid;
