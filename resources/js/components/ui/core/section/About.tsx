import React from 'react'
import SectionWrapper from '../provider/SectionWrapper'

function About() {
  return (
   <SectionWrapper className=" min-h-dvh  w-full   content-center  px-10"  >
    <div className=" flex flex-col lg:flex-row  w-full items-start justify-between   ">

      <img
       
          src="https://images.unsplash.com/photo-1722500352255-c398c0d12f2b?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          alt="Auth-Image"
     
          width={500}
          height={500}
          
          className="inset-0 lg:order-2   h-full max-h-100 w-full max-w-3xl  object-cover "
        />
         <p className=' max-w-[22.5em]  text-muted-foreground  line-clamp-6 lg:text-left  lg:order-1  text-justify  text-sm lg:text-base leading-6 '>
          
        
        Experience the thrill of precision engineering, where every detail serves a purpose and every drive becomes a statement. Lamborghini is more than a journey-it's a legacy of luxury and performance.
         
          
          </p>
    </div>
      
            
       
    </SectionWrapper>
  )
}

export default About
