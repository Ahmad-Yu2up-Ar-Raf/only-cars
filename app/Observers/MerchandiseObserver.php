<?php

namespace App\Observers;

use App\Models\Merchandise;
use App\Services\FileUploadService;
use App\Services\JsonFileUploadService;
use Illuminate\Support\Facades\Log;

class MerchandiseObserver
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
     * Handle the Merchandise "created" event.
     */
    public function created(Merchandise $merchandise): void
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
                $merchandise->updateQuietly(['files' => $uploadedFiles]);
                
                Log::info("Files uploaded on create", [
                    'merchandise_id' => $merchandise->id,
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
    public function updating(Merchandise $merchandise)
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
            $oldFiles = $merchandise->getOriginal('files') ?? [];

            Log::info("Processing file update", [
                'merchandise_id' => $merchandise->id,
                'old_files_count' => count($oldFiles),
                'request_files_count' => count($requestFiles)
            ]);

            // Jika files kosong, hapus semua file lama
            if (empty($requestFiles)) {
                if (!empty($oldFiles)) {
                    $this->jsonFileUploadService->deleteMultipleFiles($oldFiles);
                }
                $merchandise->files = [];
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
                    'merchandise'
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
                    'merchandise_id' => $merchandise->id,
                    'deleted_count' => count($filesToDelete)
                ]);
            }

            // Update files
            $merchandise->files = $finalFiles;
            
            Log::info("File update completed", [
                'merchandise_id' => $merchandise->id,
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
     * Handle the Merchandise "updated" event.
     */
    public function updated(Merchandise $merchandise): void
    {
        //
    }

    /**
     * Handle file deletion before merchandise is deleted
     */
    public function deleting(Merchandise $merchandise)
    {
        // Prevent double execution
        if (self::$isProcessingFiles) {
            return;
        }

        if (!empty($merchandise->files)) {
            self::$isProcessingFiles = true;
            
            try {
                // Refresh model to get latest data
                $merchandise->refresh();
                
                Log::info("Attempting to delete files for merchandise deletion", [
                    'merchandise_id' => $merchandise->id,
                    'files_count' => count($merchandise->files)
                ]);
                
                $this->jsonFileUploadService->deleteMultipleFiles($merchandise->files);
                
                Log::info("Files deleted successfully for merchandise ID: {$merchandise->id}");
                
            } catch (\Exception $e) {
                Log::error("Failed to delete files for merchandise ID: {$merchandise->id}. Error: " . $e->getMessage());
                // Uncomment jika ingin gagal delete file menggagalkan delete merchandise
                // throw new \Exception('Gagal menghapus file terkait: ' . $e->getMessage());
            } finally {
                self::$isProcessingFiles = false;
            }
        }
    }

    /**
     * Handle the Merchandise "deleted" event.
     */
    public function deleted(Merchandise $merchandise): void
    {
        //
    }

    /**
     * Handle the Merchandise "restored" event.
     */
    public function restored(Merchandise $merchandise): void
    {
        //
    }

    /**
     * Handle the Merchandise "force deleted" event.
     */
    public function forceDeleted(Merchandise $merchandise): void
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
                    'merchandise'
                );
                
                Log::info('Traditional file upload completed', [
                    'uploaded_count' => count($uploadedFiles)
                ]);
                
            } elseif (request()->has('files') && is_array(request('files'))) {
                // JSON file data upload
                $uploadedFiles = $this->jsonFileUploadService->handleJsonFileUploads(
                    request('files'),
                    'merchandise'
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