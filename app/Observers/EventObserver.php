<?php

namespace App\Observers;

use App\Models\Events;
use App\Services\FileUploadService;
use App\Services\JsonFileUploadService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EventObserver
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
     * Handle the Events "created" event.
     */
    public function created(Events $events): void
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
                $events->updateQuietly(['files' => $uploadedFiles]);
                
                Log::info("Files uploaded on create", [
                    'events_id' => $events->id,
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
    public function updating(Events $event)
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
            $oldFiles = $event->getOriginal('files') ?? [];

            Log::info("Processing file update", [
                'events_id' => $event->id,
                'old_files_count' => count($oldFiles),
                'request_files_count' => count($requestFiles)
            ]);

            // Jika files kosong, hapus semua file lama
            if (empty($requestFiles)) {
                if (!empty($oldFiles)) {
                    $this->jsonFileUploadService->deleteMultipleFiles($oldFiles);
                }
                $event->files = [];
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
                    'events'
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
                    'events_id' => $event->id,
                    'deleted_count' => count($filesToDelete)
                ]);
            }

            // Update files
            $event->files = $finalFiles;
            
            Log::info("File update completed", [
                'events_id' => $event->id,
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
     * Handle the Events "updated" event.
     */
    public function updated(Events $events): void
    {
        //
    }

    /**
     * Handle file deletion before events is deleted
     */
    public function deleting(Events $events)
    {
        // Prevent double execution
        if (self::$isProcessingFiles) {
            return;
        }


          if ($events->cover_image && Storage::disk('public')->exists(str_replace('storage/', '', $events->cover_image))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $events->cover_image));
        }

        if (!empty($events->files)) {
            self::$isProcessingFiles = true;
            
            try {
                // Refresh model to get latest data
                $events->refresh();
         
                Log::info("Attempting to delete files for events deletion", [
                    'events_id' => $events->id,
                    'files_count' => count($events->files)
                ]);
                
                $this->jsonFileUploadService->deleteMultipleFiles($events->files);
                
                Log::info("Files deleted successfully for events ID: {$events->id}");
                
            } catch (\Exception $e) {
                Log::error("Failed to delete files for events ID: {$events->id}. Error: " . $e->getMessage());
                // Uncomment jika ingin gagal delete file menggagalkan delete events
                // throw new \Exception('Gagal menghapus file terkait: ' . $e->getMessage());
            } finally {
                self::$isProcessingFiles = false;
            }
        }
    }

    /**
     * Handle the Events "deleted" event.
     */
    public function deleted(Events $events): void
    {
        //
    }

    /**
     * Handle the Events "restored" event.
     */
    public function restored(Events $events): void
    {
        //
    }

    /**
     * Handle the Events "force deleted" event.
     */
    public function forceDeleted(Events $events): void
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
                    'events'
                );
                
                Log::info('Traditional file upload completed', [
                    'uploaded_count' => count($uploadedFiles)
                ]);
                
            } elseif (request()->has('files') && is_array(request('files'))) {
                // JSON file data upload
                $uploadedFiles = $this->jsonFileUploadService->handleJsonFileUploads(
                    request('files'),
                    'events'
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