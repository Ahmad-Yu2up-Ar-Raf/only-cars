"use client"

import * as React from "react"
import { ChevronsUpDown, Command, Loader, LucideIcon, Plus } from "lucide-react"

import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuShortcut,
  DropdownMenuTrigger,
} from "@/components/ui/fragments/dropdown-menu"
import {
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  useSidebar,
} from "@/components/ui/fragments/sidebar"
import { Auth, Teams } from "@/types"
import { router } from "@inertiajs/react"
import { toast } from "sonner"

export function TeamSwitcher({
  teams,
  currentTeams
}: {
  teams: Auth["teams"],
  currentTeams: Auth["currentTeam"]
}) {
  const { isMobile } = useSidebar()


 const [isLoading, setIsLoading] = React.useState(false)
  const handleTeamSwitch = (teamId: number) => {
    if (isLoading || teamId === currentTeams?.id) return
    setIsLoading(true)
    toast.loading("Switch team...", {
      id: "switch-loading"
    });
    
    router.post(`dashboard/teams/${teamId}/switch`, {}, {
      onSuccess: () => {
        setIsLoading(false)
      toast.success("Switch team succed", {
      id: "switch-loading"
    });
      },
      onError: (errors) => {
        setIsLoading(false)
             toast.success("Switch team error", {
      id: "switch-loading"
    });
        console.error('Failed to switch team:', errors)
      }
    })
  }


  if (!currentTeams) {
    return null
  }

  return (
    <SidebarMenu>
      <SidebarMenuItem>
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <SidebarMenuButton
              size="lg"
              className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
            >
              <div className="bg-sidebar-primary text-accent flex aspect-square size-8 items-center justify-center rounded-lg">
              {currentTeams.logo ? 
              <currentTeams.logo className="size-4" />
                
            : 
            <Command  className="size-4"/>
            }  
              </div>
              <div className="grid flex-1 text-left text-sm leading-tight">
                <span className="truncate font-medium">{currentTeams.name}</span>
                <span className="truncate text-xs">{currentTeams.plan }</span>
              </div>
              <ChevronsUpDown className="ml-auto" />
            </SidebarMenuButton>
          </DropdownMenuTrigger>
          <DropdownMenuContent
            className="w-(--radix-dropdown-menu-trigger-width) min-w-56 rounded-lg"
            align="start"
            side={isMobile ? "bottom" : "right"}
            sideOffset={4}
          >
            <DropdownMenuLabel className="text-muted-foreground text-xs">
              Teams
            </DropdownMenuLabel>
            {teams.map((team, index) => (
              <DropdownMenuItem
              disabled={isLoading}
                key={team.name}
                 onClick={() => handleTeamSwitch(team.id)}
                className="gap-2 p-2"
              >
                <div className="flex size-6 items-center justify-center rounded-md border">
                     {currentTeams.logo ? 
              <currentTeams.logo className="size-3.5 shrink-0" />
                
            : 
            <Command className="size-3.5 shrink-0"/>
            }  
                </div>
                {team.name}
                <DropdownMenuShortcut>{isLoading ? <Loader className=" animate-spin" /> : (
`⌘${index + 1}`
                )}</DropdownMenuShortcut>
              </DropdownMenuItem>
            ))}
            <DropdownMenuSeparator />
            <DropdownMenuItem className="gap-2 p-2">
              <div className="flex size-6 items-center justify-center rounded-md border bg-transparent">
                <Plus className="size-4" />
              </div>
              <div className="text-muted-foreground font-medium">Add team</div>
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </SidebarMenuItem>
    </SidebarMenu>
  )
}
