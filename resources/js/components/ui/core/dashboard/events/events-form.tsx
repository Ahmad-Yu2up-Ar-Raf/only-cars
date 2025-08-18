"use client";


import * as React from "react";
import type { FieldPath, FieldValues, UseFormReturn } from "react-hook-form";

import {
  Form,
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/fragments/form";
import { Input } from "@/components/ui/fragments/input";

import { cn } from "@/lib/utils";




import FormFileUpload, { FileUploadRef } from "@/components/ui/fragments/file-uploud"
import { Textarea } from "@/components/ui/fragments/textarea";



import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue
} from "@/components/ui/fragments/select"
import { FileWithPreview } from "@/hooks/use-multiple-file-upload";
import PictureImageInput from "@/components/ui/fragments/picture-input";
import { EventsSchema } from "@/lib/validations/validations";
import { StatusEvents, visibility } from "@/config/enum-type";
import { SmartDatetimeInput } from "@/components/ui/fragments/smart-date-time";



interface TaskFormProps<T extends FieldValues, >
  extends Omit<React.ComponentPropsWithRef<"form">, "onSubmit"> {
  children: React.ReactNode;
  form: UseFormReturn<T>;
  onSubmit: (data: T) => void;
  isPending: boolean;
   curentEvent?: EventsSchema
  initialFiles?: FileWithPreview[] | undefined

  fileUploadRef: React.RefObject<FileUploadRef | null>
}

export function TaskForm<T extends FieldValues, >({
  form,
  onSubmit,

   fileUploadRef,
 curentEvent,
  children,
  initialFiles,
  isPending,
}: TaskFormProps<T>) {





   

  return (
    <Form {...form}>
      <form
        onSubmit={form.handleSubmit(onSubmit)}
        className="flex overflow-y-scroll pt-6 md:pt-0 md:overflow-y-clip flex-col gap-4 px-0"
      >
        <main className="space-y-6 mb-6">
          <section className="space-y-10 border-b pb-8 pt-2 px-4 sm:px-6" >


            
            <FormField
              control={form.control}
              name={"title" as FieldPath<T>}
              render={({ field }) => (
                <FormItem>
                  <FormLabel className={cn(isPending && "text-muted-foreground")}>Name</FormLabel>
                  <FormControl>
                    <Input 
                
                      placeholder={`Event title`}
                      type="text"
                      disabled={isPending}
                      {...field}
                    />
                  </FormControl>
                  <FormDescription className=" sr-only">{`Your Events title.`}</FormDescription>
                  <FormMessage />
                </FormItem>
              )}
            />




            
       
        

<FormField
  disabled={isPending}
  control={form.control}
  name={"cover_image" as FieldPath<T>}
  render={({ field, fieldState }) => (
    <FormItem>
      <FormLabel className={cn(isPending && "text-muted-foreground")}>
        Cover Image
      </FormLabel>
      <FormControl>
        <PictureImageInput
          disabled={isPending}
          value={field.value}
          onChange={(file) => {
            field.onChange(file)
          }}
          defaultValue={curentEvent?.cover_image || null}
          error={fieldState.error?.message}
        />
      </FormControl>
      <FormDescription  className=" sr-only">
        Upload gambar cover_image (SVG, PNG, JPG, GIF - max 2MB)
      </FormDescription>
      <FormMessage />
    </FormItem>
  )}
/>




<FormField
              control={form.control}
              name={"capacity" as FieldPath<T>}
              render={({ field }) => (
                <FormItem>
                  <FormLabel className={cn(isPending && "text-muted-foreground")}>Capacity</FormLabel>
                  <FormControl>
                    <Input 
                
                      placeholder={`Capactity name`}
                      type="number"
                      disabled={isPending}
                      {...field}
                    />
                  </FormControl>
                  <FormDescription className=" sr-only">{`Capacity`}</FormDescription>
                  <FormMessage />
                </FormItem>
              )}
            />





<FormField
                control={form.control}
                name={"location" as FieldPath<T>}
                render={({ field }) => (
                  <FormItem>
                    <FormLabel className={cn(isPending && "text-muted-foreground")}>Location</FormLabel>
                    <FormControl>
                      <Textarea
                        placeholder="Location"
                        className="resize-none"
                        disabled={isPending}
                        {...field}
                      />
                    </FormControl>
                    <FormDescription className="sr-only">{` deskripsion`}</FormDescription>
                    <FormMessage />
                  </FormItem>
                )}
              />




<FormField
              control={form.control}
          name={"start_date" as FieldPath<T>}
           defaultValue={curentEvent?.start_date as any || ""}
              render={({ field }) => (
              <FormItem>
                <FormLabel>What's the best time for you?</FormLabel>
                <FormControl>
                  <SmartDatetimeInput
                    value={field.value}
                      
                    onValueChange={field.onChange}
                    placeholder="e.g. Tomorrow morning 9am"
               
                  />
                </FormControl>
                <FormDescription  className=" sr-only">Please select the full time</FormDescription>
                <FormMessage />
              </FormItem>
              )}
            />
        
            <FormField
              control={form.control}
          name={"end_date" as FieldPath<T>}
           defaultValue={curentEvent?.end_date as any || ""}
              render={({ field }) => (
              <FormItem>
                <FormLabel>The event ended</FormLabel>
                <FormControl>
                  <SmartDatetimeInput
                    value={field.value}
                    onValueChange={field.onChange}
                     
                    placeholder="e.g. Tomorrow morning 9am"
         
                    
                  />
                </FormControl>
                <FormDescription className=" sr-only">Please select the full time</FormDescription>
                <FormMessage />
              </FormItem>
              )}
            />
          </section>
          
          <section className="space-y-10 px-4 sm:px-6">
            <header>
              <h1 className="text-lg font-semibold">Optional Fields</h1>
              <p className="text-sm text-muted-foreground">These are columns that do not need any value</p>
            </header>
            
            <section className="space-y-10">
         


            <FormField
          control={form.control}
          name={"files" as FieldPath<T>}
          render={( { field}) => (
            <FormItem className=" ">
              <FormLabel className="">Event Gallery</FormLabel>
              <FormControl>
                <FormFileUpload 
                  {...field}
                  initialFiles={initialFiles}
                  ref={fileUploadRef}
                  control={form.control}
                  name="files"
                  maxSizeMB={5}
                  maxFiles={6}
               
                  isLoading={isPending}
                  isPending={isPending}
                />
              </FormControl>
              <FormDescription className="sr-only">Upload files for your project</FormDescription>
              <FormMessage className=" sr-only" />
            </FormItem>
          )}
        />



            <FormField
                control={form.control}
                name={"deskripsi" as FieldPath<T>}
                render={({ field }) => (
                  <FormItem>
                    <FormLabel className={cn(isPending && "text-muted-foreground")}>Description</FormLabel>
                    <FormControl>
                      <Textarea
                        placeholder="description"
                        className="resize-none"
                        disabled={isPending}
                        {...field}
                      />
                    </FormControl>
                    <FormDescription className="sr-only">{`Event deskripsion`}</FormDescription>
                    <FormMessage />
                  </FormItem>
                )}
              />
     

{/* <MoneyInput
          form={form}
          disable={isPending}
          label="DP Minimum"
          name="dp_minimum"
          placeholder={`dp ${type}`}
        />

    */}









<FormField
          control={form.control}
          name={"status" as FieldPath<T>}
          render={({ field }) => (
            <FormItem>
              <FormLabel>Status</FormLabel>
              <Select onValueChange={field.onChange} defaultValue={field.value}>
                <FormControl>
                  <SelectTrigger>
                    <SelectValue placeholder="Select status" />
                  </SelectTrigger>
                </FormControl>
                <SelectContent>
                  {StatusEvents.map((item,i ) => (

                  <SelectItem key={i} value={item.value}>{item.label}</SelectItem>
                  ))}
                  
                </SelectContent>
              </Select>
                <FormDescription className=" sr-only">You can manage email addresses in your email settings.</FormDescription>
              <FormMessage />
            </FormItem>
          )}
        />

<FormField
          control={form.control}
          name={"visibility" as FieldPath<T>}
          render={({ field }) => (
            <FormItem>
              <FormLabel>Visibility</FormLabel>
              <Select onValueChange={field.onChange} defaultValue={field.value}>
                <FormControl>
                  <SelectTrigger>
                    <SelectValue placeholder="Select status" />
                  </SelectTrigger>
                </FormControl>
                <SelectContent>
                  {visibility.map((item,i ) => (

                  <SelectItem key={i} value={item.value}>{item.label}</SelectItem>
                  ))}
                  
                </SelectContent>
              </Select>
                <FormDescription className=" sr-only">You can manage email addresses in your email settings.</FormDescription>
              <FormMessage />
            </FormItem>
          )}
        />







            </section>
          </section>
        </main>
       
        {children}
      </form>
    </Form>
  );
}