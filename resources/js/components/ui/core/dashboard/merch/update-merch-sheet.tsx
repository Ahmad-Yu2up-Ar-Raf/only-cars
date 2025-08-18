"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import { Loader } from "lucide-react";
import * as React from "react";
import { useForm } from "react-hook-form";
import { toast } from "sonner";

import { Button } from "@/components/ui/fragments/button";
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from "@/components/ui/fragments/sheet";

import { MerchSchema, merchSchema ,   } from "@/lib/validations/validations";
import { TaskForm } from "./merch-form";
import { useIsMobile } from "@/hooks/use-mobile";
import {
  Drawer,
  DrawerClose,
  DrawerContent,
  DrawerDescription,
  DrawerFooter,
  DrawerHeader,
  DrawerTitle,
} from "@/components/ui/fragments/drawer";
import { FileUploadRef } from "@/components/ui/fragments/file-uploud";
import { FileWithPreview } from "@/hooks/use-file-upload";
import { router } from "@inertiajs/react";



interface UpdateTaskSheetProps
  extends React.ComponentPropsWithRef<typeof Sheet> {
  task: MerchSchema | null;
}

export function UpdateMerchandiseSheet({ task, ...props }: UpdateTaskSheetProps) {
  const [isPending, startTransition] = React.useTransition();
  const [isSubmitting, setIsSubmitting] = React.useState(false);
  const [loading, setLoading] = React.useState(false);
  
  const fileUploadRef = React.useRef<FileUploadRef>(null)
  const form = useForm<MerchSchema>({
    mode: "onSubmit",
    defaultValues: {

   name:task?.name,
   image: task?.image,
   deskripsi: task?.deskripsi,
   price:task?.price,
   quantity: task?.quantity,
 
   visibility: task?.visibility,
 

 
    status:task?.status,
 
   files: task?.files,
    },
    resolver: zodResolver(merchSchema),
  });


console.log(task)

  React.useEffect(() => {
    if (task) {
      form.reset({
   
   name:task?.name,
   image: task?.image,
   deskripsi: task?.deskripsi,
   price:task?.price,
   quantity: task?.quantity,
 
   visibility: task?.visibility,
 

 
    status:task?.status,
 
   files: task?.files,
      });
    }
  }, [task, form]);

  function onSubmit(input: MerchSchema) {
    if (!task) {
      toast.error("Task data not found");
      return;
    }
    setLoading(true);

    
    // Validasi apakah semua files memiliki base64Data (untuk file baru)
    const newFiles = input?.files!.filter(file => file.file instanceof File);
    const filesWithoutBase64 = newFiles.filter(file => !file.base64Data);
    
    if (filesWithoutBase64.length > 0) {
      toast.error("Some new files are missing data. Please try uploading again.");
      return;
    }




    setIsSubmitting(true);
    
    startTransition(async () => {
      try {
        // Prepare data dengan struktur yang benar
        const formData = {
          ...input,
          _method: "PUT",

        };

  

        router.post(route('dashboard.merchandise.update', task.id), formData, {
          preserveScroll: true,
          preserveState: true,
          forceFormData: true,
          onBefore: (visit) => {
            console.log('Update request about to start:', visit);
          },
          onStart: (visit) => {
            console.log('Update request started');
            toast.loading('Updating event data...', { id: 'update-toast' });
          },
          onSuccess: (page) => {
            console.log('Update success response:', page);
            setLoading(false);
            props.onOpenChange?.(false);
            toast.success('Merchandise updated successfully', { id: 'update-toast' });
            form.reset();
                console.log(`data created`, input)
          },
          onError: (errors) => {
            setLoading(false);
            console.error('Update form submission error:', errors);
            
            if (typeof errors === 'object' && errors !== null) {
              Object.entries(errors).forEach(([field, messages]) => {
                if (Array.isArray(messages)) {
                  messages.forEach((msg: string) => {
                    toast.error(`${field}: ${msg}`, { id: 'update-toast' });
                    form.setError(field as any, { 
                      type: 'manual',
                      message: msg
                    });
                  });
                } else if (typeof messages === 'string') {
                  toast.error(`${field}: ${messages}`, { id: 'update-toast' });
                  form.setError(field as any, { 
                    type: 'manual',
                    message: messages
                  });
                }
              });
            } else {
              toast.error('Failed to update event data', { id: 'update-toast' });
            }
          },
          onFinish: () => {
            setLoading(false);
            setIsSubmitting(false);
            console.log('Update request finished');
          }
        });
        
      } catch (error) {
        toast.error("Failed to update event", { id: "event-updated" });
        setIsSubmitting(false);
        setLoading(false);
      }
    });
  }


  const isDesktop = useIsMobile();

  if (!isDesktop) {
    return (
      <Sheet {...props} modal={true}>
        <SheetContent className="flex flex-col gap-6 overflow-y-scroll">
           <SheetHeader className="text-left sm:px-7 space-y-1 bg-background  z-50 sticky top-0   p-4 border-b  ">
                   <SheetTitle className=" text-lg">Update<Button type="button"   variant={"outline"} className=" ml-2  px-2.5 text-base">{task?.name}</Button> </SheetTitle>
                   <SheetDescription className=" sr-only">
                     Fill in the details below to update the task
                   </SheetDescription>
                 </SheetHeader>
          <TaskForm<MerchSchema> form={form} onSubmit={onSubmit} isPending={loading} initialFiles={task!.files ? task!.files   : undefined}  fileUploadRef={fileUploadRef}   >
            <SheetFooter className="gap-3 px-3 py-4 w-full flex-row justify-end  flex  border-t sm:space-x-0">
                      <SheetClose disabled={loading} asChild onClick={() => form.reset()}>
                        <Button  disabled={loading} type="button" className="  w-fit" size={"sm"} variant="outline">
                              {loading && <Loader className="animate-spin" />}
                          Cancel
                        </Button>
                      </SheetClose>
                      <Button disabled={loading} type="submit" className="w-fit dark:bg-primary  dark:text-primary-foreground  bg-primary text-primary-foreground " size={"sm"}>
                        {loading && <Loader className="animate-spin" />}
                        Update
                      </Button>
                    </SheetFooter>
          </TaskForm>
        </SheetContent>
      </Sheet>
    );
  }

  return(
      <Drawer  {...props} modal={true}>
  <DrawerContent className="flex flex-col  ">
    <DrawerHeader className="text-left sm:px-7 z-50  space-y-1 bg-background    p-4 border-b  ">
    <DrawerTitle className=" text-xl">Update<Button type="button"   variant={"outline"} className=" ml-2  px-2.5 text-base">{task?.name}</Button> </DrawerTitle>
          <DrawerDescription className=" text-sm">
                     Fill in the details below to update the task
                   </DrawerDescription>
    </DrawerHeader>
      <TaskForm<MerchSchema>  form={form} onSubmit={onSubmit} isPending={loading} initialFiles={task!.files ? task!.files   : undefined}  fileUploadRef={fileUploadRef}   >
    <DrawerFooter className="gap-3 px-3 py-4 w-full flex-row justify-end  flex  border-t sm:space-x-0">
         <DrawerClose disabled={loading} asChild onClick={() => form.reset()}>
                        <Button  disabled={loading} type="button" className="  w-fit" size={"sm"} variant="outline">
                              {loading && <Loader className="animate-spin" />}
                          Cancel
                        </Button>
                      </DrawerClose>
                      <Button disabled={loading} type="submit" className="w-fit dark:bg-primary  dark:text-primary-foreground  bg-primary text-primary-foreground " size={"sm"}>
                        {loading && <Loader className="animate-spin" />}
                        Update
                      </Button>
    </DrawerFooter>
      </TaskForm>
  </DrawerContent>
</Drawer>
  )
}