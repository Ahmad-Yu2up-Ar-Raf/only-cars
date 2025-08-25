'use client';
import React from 'react';
import { motion } from 'motion/react';

import { MoveUpRight } from 'lucide-react';
import SectionWrapper from '../core/provider/SectionWrapper';
import { MerchSchema } from '@/lib/validations/validations';
import VerticalCutReveal from './animations/vertical-cut-reveal';
interface ProjectsTypes {
  id: string;
  img: string;
  title: string;
  des: string;
}

type componentProps = {
  projects : MerchSchema[]
}

export default function index({ projects}: componentProps) {
  return (
    <>
   
      <SectionWrapper className=' py-30  space-y-20  overflow-hidden  '>
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
            Product 
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
            Merch*
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
      <div className="grid grid-cols-12 gap-4 px-10  m-auto">

        {projects.map((project, index) => {
          let colSpanClass = 'sm:col-span-6 col-span-12 ';
          if (index === 0) {
            colSpanClass = ' sm:col-span-5 col-span-12 ';
          } else if (index === 1) {
            colSpanClass = 'sm:col-span-7 col-span-12 ';
          } else if (index === projects.length - 2) {
            colSpanClass = 'sm:col-span-7 col-span-12 ';
          } else if (index === projects.length - 1) {
            colSpanClass = 'sm:col-span-5 col-span-12 ';
          }
          return (
            <>
              <motion.article
                key={index}
                initial={{ y: 50, opacity: 0 }}
                whileInView={{ y: 0, opacity: 1 }}
                transition={{ ease: 'easeOut' }}
                viewport={{ once: false }}
                className={` relative  ${colSpanClass} `}
              >
                <div className='w-auto h-full'>
                  <img
                    src={`${project?.image}`}
                    alt={'image'}
                    height={600}
                    width={1200}
                    className='h-full w-full object-cover rounded-xl'
                  />
                </div>
                <div className='absolute lg:bottom-2 bottom-0 text-black w-full p-4 flex justify-between items-center'>
                  <h3 className='lg:text-lg text-sm bg-black text-white rounded-xl p-2 px-4'>
                    {project.name}
                  </h3>
                  <div className='lg:w-12 w-10 lg:h-12 h-10 text-white grid place-content-center rounded-full bg-black'>
                    <MoveUpRight />
                  </div>
                </div>
              </motion.article>
            </>
          );
        })}
      </div>
      </SectionWrapper>
    </>
  );
}
