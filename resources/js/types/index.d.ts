import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
    teams: Teams[];
    currentTeam: Teams | null
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}
  
export interface ReportDataByDate { 
    date: string;
    events: number;
    merchandise: number;
    gallery: number;
}

export interface Reports {
    totalEvents: number
    totalMerchandise: number
    totalMerchandiseTerjual: number
   totalGallery: number
   
    EventsstatusCount: Record<string, number>
    StatusMerchandiseCount: Record<string, number>
    countsByDate: ReportDataByDate[]
    [key: string]: unknown; 
}


export type PageProps = {
    reports : Reports
 }


export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}


  export interface DataCard { 
    title: string;
    description: string;
    value: number;
    icon: LucideIcon;
   label?: string;
  }
  

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    current_team_id?: number | null;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

  export interface tabsLinktype{
    link: string
    name: string
  }


export interface Teams {
    id: number;
    name: string;
    plan? : string;
    logo?: LucideIcon
    owner_id: number
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}



 export interface ChartDataType {
    date: string;
    motor?: number;
    mess?: number;
    rooms?: number;
  }
  
export interface Filters {
    search?: string;
    
    status?: string[] | string;
    [key: string]: unknown;
}


export interface sidebarType {  items: {
    title: string
    url: string
    icon: LucideIcon
    isActive?: boolean
    items?: {
      title: string
      url: string
    }[]
  }[]
}


export interface PaginatedData {
    data:  EventsSchema[];
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}
