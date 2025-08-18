<?php

namespace App\Observers;

use App\Models\Gallery;
use App\Services\FileUploadService;
use App\Services\JsonFileUploadService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GalleryObserver
{
    protected $fileUploadService;
    protected $jsonFileUploadService;
    
    // Flag untuk prevent double execution
    protected static $isProcessingFiles = false;

    public function __construct(
        FileUploadService $fileUploadService,
        JsonFileUploadService $jsonFileUploadService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->jsonFileUploadService = $jsonFileUploadService;
    }

    /**
     * Handle the Gallery "created" event.
     */
    public function created(Gallery $gallery): void
    {
        // Prevent double execution
        if (self::$isProcessingFiles) {
            return;
        }

        if (request()->hasFile('files') || (request()->has('files') && is_array(request('files')))) {
            self::$isProcessingFiles = true;
            
            try {
                $uploadedFiles = $this->handleFileUploads();
                
                if (empty($uploadedFiles)) {
                    throw new \Exception('Gagal mengupload file. Pastikan file valid.');
                }
                
                // UPDATE TANPA TRIGGER OBSERVER LAGI
                $gallery->updateQuietly(['files' => $uploadedFiles]);
                
                Log::info("Files uploaded on create", [
                    'gallery_id' => $gallery->id,
                    'files_count' => count($uploadedFiles)
                ]);
                
            } catch (\Exception $e) {
                Log::error('File upload error on create: ' . $e->getMessage());
                throw $e;
            } finally {
                self::$isProcessingFiles = false;
            }
        }
    }

    /**
     * Handle file updates with proper logic to avoid duplicate uploads
     */
    public function updating(Gallery $gallery)
    {
        // Prevent double execution
        if (self::$isProcessingFiles) {
            return;
        }

        // Cek apakah ada perubahan pada files
        if (!request()->has('files')) {
            return; // Tidak ada files dalam request, skip
        }

        self::$isProcessingFiles = true;
        
        try {
            $requestFiles = request('files', []);
            $oldFiles = $gallery->getOriginal('files') ?? [];

            Log::info("Processing file update", [
                'gallery_id' => $gallery->id,
                'old_files_count' => count($oldFiles),
                'request_files_count' => count($requestFiles)
            ]);

            // Jika files kosong, hapus semua file lama
            if (empty($requestFiles)) {
                if (!empty($oldFiles)) {
                    $this->jsonFileUploadService->deleteMultipleFiles($oldFiles);
                }
                $gallery->files = [];
                return;
            }

            // Pisahkan files menjadi existing dan new files
            $existingFiles = [];
            $newFilesData = [];

            foreach ($requestFiles as $fileData) {
                if (isset($fileData['file_path']) && !isset($fileData['base64Data'])) {
                    // File existing (sudah ada di storage)
                    $existingFiles[] = $fileData;
                } elseif (isset($fileData['base64Data']) && isset($fileData['file'])) {
                    // File baru dengan base64 data
                    $newFilesData[] = $fileData;
                }
            }

            Log::info("File categorization", [
                'existing_files' => count($existingFiles),
                'new_files' => count($newFilesData)
            ]);

            // Upload file-file baru saja
            $newUploadedFiles = [];
            if (!empty($newFilesData)) {
                $newUploadedFiles = $this->jsonFileUploadService->handleJsonFileUploads(
                    $newFilesData,
                    'gallery'
                );

                if (count($newUploadedFiles) !== count($newFilesData)) {
                    throw new \Exception('Gagal mengupload beberapa file baru.');
                }
            }

            // Gabungkan existing files dengan newly uploaded files
            $finalFiles = array_merge($existingFiles, $newUploadedFiles);

            // Tentukan file mana yang harus dihapus
            $filesToDelete = $this->getFilesToDelete($oldFiles, $finalFiles);
            
            // Hapus file yang tidak digunakan lagi
            if (!empty($filesToDelete)) {
                $this->jsonFileUploadService->deleteMultipleFiles($filesToDelete);
                Log::info('Deleted unused files', [
                    'gallery_id' => $gallery->id,
                    'deleted_count' => count($filesToDelete)
                ]);
            }

            // Update files
            $gallery->files = $finalFiles;
            
            Log::info("File update completed", [
                'gallery_id' => $gallery->id,
                'final_files_count' => count($finalFiles)
            ]);

        } catch (\Exception $e) {
            Log::error('File update error: ' . $e->getMessage());
            throw $e;
        } finally {
            self::$isProcessingFiles = false;
        }
    }

    /**
     * Handle the Gallery "updated" event.
     */
    public function updated(Gallery $gallery): void
    {
        //
    }

    /**
     * Handle file deletion before gallery is deleted
     */
    public function deleting(Gallery $gallery)
    {
        if ($gallery->cover_image && Storage::disk('public')->exists(str_replace('storage/', '', $gallery->cover_image))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $gallery->cover_image));
        }

        // Prevent double execution
        if (self::$isProcessingFiles) {
            return;
        }



        if (!empty($gallery->files)) {
            self::$isProcessingFiles = true;
            
            try {
                // Refresh model to get latest data
                $gallery->refresh();
                
                Log::info("Attempting to delete files for gallery deletion", [
                    'gallery_id' => $gallery->id,
                    'files_count' => count($gallery->files)
                ]);
                
                $this->jsonFileUploadService->deleteMultipleFiles($gallery->files);
                
                Log::info("Files deleted successfully for gallery ID: {$gallery->id}");
                
            } catch (\Exception $e) {
                Log::error("Failed to delete files for gallery ID: {$gallery->id}. Error: " . $e->getMessage());
                // Uncomment jika ingin gagal delete file menggagalkan delete gallery
                // throw new \Exception('Gagal menghapus file terkait: ' . $e->getMessage());
            } finally {
                self::$isProcessingFiles = false;
            }
        }
    }

    /**
     * Handle the Gallery "deleted" event.
     */
    public function deleted(Gallery $gallery): void
    {
        //
    }

    /**
     * Handle the Gallery "restored" event.
     */
    public function restored(Gallery $gallery): void
    {
        //
    }

    /**
     * Handle the Gallery "force deleted" event.
     */
    public function forceDeleted(Gallery $gallery): void
    {
        //
    }

    /**
     * Handle file uploads untuk create
     */
    private function handleFileUploads(): array
    {
        $uploadedFiles = [];

        try {
            // Check if files are UploadedFile objects or JSON data
            if (request()->hasFile('files')) {
                // Traditional file upload
                $uploadedFiles = $this->fileUploadService->handleMultipleUploads(
                    request()->file('files'),
                    'gallery'
                );
                
                Log::info('Traditional file upload completed', [
                    'uploaded_count' => count($uploadedFiles)
                ]);
                
            } elseif (request()->has('files') && is_array(request('files'))) {
                // JSON file data upload
                $uploadedFiles = $this->jsonFileUploadService->handleJsonFileUploads(
                    request('files'),
                    'gallery'
                );
                
                Log::info('JSON file upload completed', [
                    'uploaded_count' => count($uploadedFiles)
                ]);
            }

            return $uploadedFiles;

        } catch (\Exception $e) {
            Log::error('File upload error in observer: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Determine which files should be deleted
     */
    private function getFilesToDelete(array $oldFiles, array $newFiles): array
    {
        $newFilePaths = [];
        
        // Ambil semua file_path dari file baru
        foreach ($newFiles as $newFile) {
            if (isset($newFile['file_path'])) {
                $newFilePaths[] = $newFile['file_path'];
            }
        }

        $filesToDelete = [];
        
        // Cari file lama yang tidak ada lagi di file baru
        foreach ($oldFiles as $oldFile) {
            if (isset($oldFile['file_path']) && !in_array($oldFile['file_path'], $newFilePaths)) {
                $filesToDelete[] = $oldFile;
            }
        }

        return $filesToDelete;
    }
}