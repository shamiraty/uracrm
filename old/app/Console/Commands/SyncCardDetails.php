<?php

namespace App\Console\Commands;

use App\Models\CardDetail;
use App\Services\PrimaryApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SyncCardDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:card-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes Card Details from the primary API to the local database.';

    protected $primaryApiService;

    public function __construct(PrimaryApiService $primaryApiService)
    {
        parent::__construct();
        $this->primaryApiService = $primaryApiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Card Details synchronization...');
        Log::info('Sync: Card Details synchronization started.');

        $response = $this->primaryApiService->get('card-details');

        if ($response && $response['status'] === 'success' && isset($response['data'])) {
            $apiCardDetails = collect($response['data']);
            $localCardDetails = CardDetail::all();

            $syncedCount = 0;
            $createdCount = 0;
            $updatedCount = 0;
            $deletedCount = 0;

            // 1. Insert new or update existing records
            foreach ($apiCardDetails as $apiDetail) {
                // Find local record by primary_id
                $localDetail = $localCardDetails->firstWhere('primary_id', $apiDetail['id']);

                // Prepare data for fillable fields. Exclude 'id' (primary ID of local table)
                // and timestamps, as they are managed by Laravel.
                $dataToSync = collect($apiDetail)->except(['id', 'created_at', 'updated_at'])->toArray();
                $dataToSync['primary_id'] = $apiDetail['id']; // Map remote 'id' to local 'primary_id'

                // Handle image URLs: These are relative paths, we need to download them
                // This is a simplified approach, consider caching/checking if image changed.
                if (!empty($apiDetail['picha_ya_kitambulisho'])) {
                    $remoteImageUrl = config('app.primary_api_base_url') . '/storage/uploads/' . $apiDetail['picha_ya_kitambulisho'];
                    $localImagePath = 'public/uploads/' . $apiDetail['picha_ya_kitambulisho'];
                    try {
                        // Only download if file doesn't exist locally or if it's new
                        if (!Storage::exists($localImagePath)) {
                             $imageContent = Http::get($remoteImageUrl)->throw()->body();
                             Storage::put($localImagePath, $imageContent);
                             Log::info("Downloaded picha_ya_kitambulisho: " . $apiDetail['picha_ya_kitambulisho']);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Failed to download kitambulisho image '{$apiDetail['picha_ya_kitambulisho']}': " . $e->getMessage());
                        // Keep the existing path if download fails, or set to null
                    }
                }
                if (!empty($apiDetail['picha_ya_sahihi_yako'])) {
                    $remoteImageUrl = config('app.primary_api_base_url') . '/storage/uploads/' . $apiDetail['picha_ya_sahihi_yako'];
                    $localImagePath = 'public/uploads/' . $apiDetail['picha_ya_sahihi_yako'];
                    try {
                        if (!Storage::exists($localImagePath)) {
                            $imageContent = Http::get($remoteImageUrl)->throw()->body();
                            Storage::put($localImagePath, $imageContent);
                            Log::info("Downloaded picha_ya_sahihi_yako: " . $apiDetail['picha_ya_sahihi_yako']);
                        }
                    } catch (\Exception $e) {
                         Log::warning("Failed to download sahihi image '{$apiDetail['picha_ya_sahihi_yako']}': " . $e->getMessage());
                    }
                }

                if ($localDetail) {
                    // Update existing record if there are changes
                    $updated = false;
                    foreach ($dataToSync as $key => $value) {
                        if ($localDetail->{$key} != $value) {
                            $localDetail->{$key} = $value;
                            $updated = true;
                        }
                    }
                    if ($updated) {
                         $localDetail->save();
                         $updatedCount++;
                         $this->info("Updated Card Detail: {$apiDetail['jina_kamili']} (ID: {$apiDetail['id']})");
                         Log::info("Sync: Updated Card Detail ID: {$apiDetail['id']}");
                    }
                } else {
                    // Create new record
                    CardDetail::create($dataToSync);
                    $createdCount++;
                    $this->info("Created new Card Detail: {$apiDetail['jina_kamili']} (ID: {$apiDetail['id']})");
                    Log::info("Sync: Created new Card Detail ID: {$apiDetail['id']}");
                }
                $syncedCount++;
            }

            // 2. Delete local records that no longer exist in the API
            foreach ($localCardDetails as $localDetail) {
                if (!$apiCardDetails->firstWhere('id', $localDetail->primary_id)) {
                    // Delete associated image files
                    if ($localDetail->picha_ya_kitambulisho && Storage::exists('public/uploads/' . $localDetail->picha_ya_kitambulisho)) {
                        Storage::delete('public/uploads/' . $localDetail->picha_ya_kitambulisho);
                    }
                    if ($localDetail->picha_ya_sahihi_yako && Storage::exists('public/uploads/' . $localDetail->picha_ya_sahihi_yako)) {
                        Storage::delete('public/uploads/' . $localDetail->picha_ya_sahihi_yako);
                    }
                    $localDetail->delete();
                    $deletedCount++;
                    $this->info("Deleted Card Detail: {$localDetail->jina_kamili} (Primary ID: {$localDetail->primary_id})");
                    Log::info("Sync: Deleted Card Detail Primary ID: {$localDetail->primary_id}");
                }
            }

            $this->info("Synchronization finished.");
            $this->info("Created: {$createdCount}, Updated: {$updatedCount}, Deleted: {$deletedCount}");
            Log::info("Sync: Card Details synchronization finished. Created: {$createdCount}, Updated: {$updatedCount}, Deleted: {$deletedCount}");

        } else {
            $this->error('Failed to fetch data from the primary API.');
            Log::error('Sync: Failed to fetch data from the primary API.');
        }
    }
}