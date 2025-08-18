"use client"
import React from 'react'
import { SectionCards } from './section-card'

import { Bike,  Calendar,  Camera,  CarIcon,  CircleFadingArrowUp,  DoorOpen, Shirt, UsersRound } from "lucide-react";

import { DataCard, PageProps } from '@/types';
import { ChartPie } from './charts/chat-pie-donut-text';
import { ChartAreaInteractive } from './charts/chart-area-interactive';
import { ChartBarActive } from './charts/chart-bar-active';





function MainSection( {  reports  }: PageProps) {


const dataCards: DataCard[] = [ 
    {
      title: "Total Events",
      description: "This is total of your Events ",
      value: reports["totalEvents"],
      icon: Calendar,
      label: "Events"
    },

    {
      title: "Total Merchandise",
      description: "This is total of your Merchandise ",
      value: reports["totalMerchandise"],
      icon: Shirt,
      label: "Merchandise"
    },
    {
      title: "Merchandise Terjual",
      description: "This is total of your Merchandise  sold out ",
      value: reports["totalMerchandise"],
      icon: CircleFadingArrowUp,
      label: "Terjual"
    },
    {
      title: "Total Gallery",
      description: "This is total of your Gallery ",
      value: reports["totalGallery"],
      icon: Camera,
      label: "Gallery"
    },
    // {
    //   title: "Total Rooms",
    //   description: "This is total of your rooms ",
    //   value:  data['Total Ruangan'],
    //   icon: DoorOpen,
    //   label: "Rooms"
    // },
    // {
    //   title: "Total Events",
    //   description: "This is total of your employee ",
    //   value: data['Total Karyawan'],
    //   icon: BriefcaseBusiness,
    //   label: "Events"
    // },
  
    // {
    //   title: "Total Full Events",
    //   description: "This is total of your full Events ",
    //   value: data['Total Events Full'],
    //   icon: UsersRound,
    //   label: "Full Events"
    // },
  
  ];

  return (
 <>
        <section className='space-y-4'>
          <div className="@container/main flex flex-1 flex-col gap-4">
            <div className="flex flex-col gap-4 md:gap-6">
              <SectionCards 
               dataCards={dataCards}
              />
        
            </div>
            <div className="*:data-[slot=card]:from-primary/5 *:data-[slot=card]:to-card dark:*:data-[slot=card]:bg-card grid grid-cols-1 sm:grid-cols-2  *:data-[slot=card]:bg-gradient-to-t *:data-[slot=card]:shadow-xs gap-y-4 md:gap-x-4   @5xl/main:grid-cols-3">
               
               
    
                      <ChartAreaInteractive   className='    col-span-2    lg:col-span-3  'chartData={
             reports.countsByDate
              } />
                            {/* <ChartPie showFooter={false} title='Events Distribution - Gender' description='Current events count by status' footerDeskripcion={"Showing total events by the status distribution"}  className='    col-span-2 lg:col-span-1 ' data={reports.statusCount}  nameKey='Events'/> */}
               
                      <ChartPie showFooter className='    col-span-2 lg:col-span-1 ' title='Events Distribution - Status' footerDeskripcion={"Showing total events by the status distribution"} description='Current events count by status' data={reports.EventsstatusCount} nameKey='Events'/>
                    {/* <ChartBarActive className='    col-span-2 lg:col-span-1 '  data={reports.countsHighest
              }/> */}


<ChartPie showFooter className='    col-span-2 lg:col-span-1 ' title='Merchandise Distribution - Kategori Status' footerDeskripcion={"Showing total merchandise by the status"} description='Current merchandise count by status' data={reports.StatusMerchandiseCount} nameKey='Merchandise'/>
            {/* <ChartPie className='    col-span-2 lg:col-span-1 ' data={EmployesRoleCounts}/> */}
                 
</div>

          </div>
        </section>

 </>

  )
}

export default MainSection