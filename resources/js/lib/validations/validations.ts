
import { StatusEventsValue ,  StatusMerchandiseValue, visibilityValue} from "@/config/enum-type";
import * as z from "zod";


const imageSchema = z.union([
  // File object untuk upload baru
  z.instanceof(File)
    .refine((file) => file.size <= 2 * 1024 * 1024, {
      message: "File size must be less than 2MB",
    })
    .refine(
      (file) =>
        ["image/jpeg", "image/jpg", "image/png", "image/svg+xml", "image/gif"].includes(
          file.type
        ),
      {
        message: "Only JPEG, PNG, SVG, and GIF files are allowed",
      }
    ),
  // String untuk existing image path
  z.string().min(1, "Picture is required"),
])


export const FileMetadata = z.object({
  name:  z.coerce.string().min(1, "File name is required"),
  size: z.coerce.number().min(1, "File size is required"),
  type: z.coerce.string().min(1, "File type is required"),
});

export const FileWithPreview = z.object({
  file: FileMetadata,
  id: z.coerce.string().min(1, "File ID is required"),
  preview: z.coerce.string().optional(),
  base64Data: z.coerce.string().min(1, "File data is required") // Tambahkan base64Data sebagai required
})

export const eventsSchema = z.object({
    id: z.number().optional(),
  title: z.string().min(4, "Name is required"),
  cover_image: imageSchema,
  deskripsi: z.string().optional(),
  location: z.string().min(4, "Plat nomor is required"),
  capacity: z.coerce.number().min(2, "Harga is required").optional(),
  start_date: z.coerce.date(),
  visibility: z.enum(visibilityValue).optional(),
   end_date: z.coerce.date(),
  created_at: z.date().optional(),
  updated_at: z.date().optional(),


   status: z.enum(StatusEventsValue ).optional(),

  files: z
  .array(FileWithPreview)
  .optional(),
});



export const merchSchema = z.object({
    id: z.number().optional(),
  name: z.string().min(4, "Name is required"),
  image: imageSchema,
  deskripsi: z.string().optional(),
  price: z.coerce.number().min(0.001, "Harga is required"),
  quantity: z.coerce.number().min(2, "Harga is required"),

  visibility: z.enum(visibilityValue).optional(),

  created_at: z.coerce.date().optional(),
  updated_at: z.coerce.date().optional(),


   status: z.enum(StatusMerchandiseValue).optional(),

  files: z
  .array(FileWithPreview)
  .optional(),
});


export const gallerySchema = z.object({
    id: z.number().optional(),
    title: z.string().min(4, "Name is required"),

  cover_image: imageSchema,
  visibility: z.enum(visibilityValue).optional(),

  created_at: z.date().optional(),
  updated_at: z.date().optional(),



  files: z
  .array(FileWithPreview)
  .optional(),
});





export type EventsSchema = z.infer<typeof eventsSchema>;
export type FileSchema = z.infer<typeof FileWithPreview>;
export type MerchSchema = z.infer<typeof  merchSchema>;
export type GallerySchema = z.infer<typeof  gallerySchema>;