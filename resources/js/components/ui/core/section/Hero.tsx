
import SectionWrapper from '../provider/SectionWrapper'

import { TextAnimate } from '../../fragments/animations/Text-Animate'
import Noise from '../../fragments/Noise'
function Hero() {

  return (
   
        <SectionWrapper className=" min-h-dvh content-end pb-10 px-8">
            
         <img
       
          src="https://images.unsplash.com/photo-1564436563878-6ea5756af2be?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          alt="Auth-Image"
     
          width={500}
          height={900}
          
          className="absolute opacity-20 inset-0 h-full w-full object-cover "
        />
            <div className=" gap-5 flex flex-col lg:flex-row md:items-end w-full md:justify-between">
<header>
 <h1 className=' font-montserrat font-light text-4xl lg:leading-17 lg:text-6xl'>
    
    <TextAnimate animation="blurInUp" by="character" once delay={1}>
         A DESIGN BUILT 

        </TextAnimate >

    <TextAnimate once animation="blurInDown" by="character" delay={1.5}>

FOR THE ELITE
     </TextAnimate>
   
  
 



 </h1>
</header>
 <TextAnimate animation="blurIn" by="character" delay={2} once className=' max-w-sm  font-light text-sm '>
    
  
    Step into the world of Lamborghini-a realm of unparalleled craftsmanship, cutting-edge innovation, and limitless performance.
   
    
    </TextAnimate>

            </div>
 <Noise
    patternSize={250}
    patternScaleX={1}
    patternScaleY={1}
    patternRefreshInterval={2}
    patternAlpha={15}
  />
        </SectionWrapper>
  )
}

export default Hero
