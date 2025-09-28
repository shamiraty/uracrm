<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\PrimaryApiService;
use App\Models\CardDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use GuzzleHttp\Client; // Import GuzzleHttp Client
use Illuminate\Support\Facades\File; // ADD THIS LINE
use Illuminate\Support\Facades\Storage;
class CardDetailManagerController extends Controller
{
    protected $primaryApiService;

    public function __construct(PrimaryApiService $primaryApiService)
    {
        $this->primaryApiService = $primaryApiService;
    }

    /**
     * Display a listing of the card details from the local database.
     */
public function index(Request $request)
    {
        // Start with a base query for CardDetail model, eager load the 'member' relationship
        // This 'with('member')' is crucial for displaying ClientId efficiently in the view.
        $query = CardDetail::query()->with('member');

        // --- Apply Filters ---

        // Filter by status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Filter by registered_date range
        if ($request->filled('start_date')) {
            $query->whereDate('registered_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('registered_date', '<=', $request->input('end_date'));
        }

        // Filter by mkoa_wa_kipolisi (police region)
        if ($request->filled('mkoa_wa_kipolisi') && $request->input('mkoa_wa_kipolisi') !== 'all') {
            $query->where('mkoa_wa_kipolisi', $request->input('mkoa_wa_kipolisi'));
        }

        // IMPORTANT: Removed the direct 'join' from here.
        // The 'with('member')' takes care of loading the relationship.
        // If you need to FILTER by a member attribute, you'd use 'whereHas' or 'join' specifically for that filter.
        // For simply displaying, 'with' is enough and safer.

        // Get the filtered card details from the database
        // No explicit join here, 'with' handles the relationship loading for display.
        $cardDetails = $query->get();

        // Get unique 'mkoa_wa_kipolisi' values from the database
        // This query remains separate and unaffected by the main cardDetails query's relationships.
        $mikoaWaKipolisi = CardDetail::select('mkoa_wa_kipolisi')
                                     ->distinct()
                                     ->pluck('mkoa_wa_kipolisi')
                                     ->filter()
                                     ->sort()
                                     ->values();

        // Store current filter values to pass back to the view.
        $filters = $request->only(['status', 'start_date', 'end_date', 'mkoa_wa_kipolisi']);

        // Return the view with the filtered card details, unique regions, and current filter values
        return view('card_details.index', compact('cardDetails', 'mikoaWaKipolisi', 'filters'));
    }

    /**
     * Export filtered card details to a CSV file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        // Start with a base query for CardDetail model
        $query = CardDetail::query();

        // --- Apply Filters (same logic as index method to ensure consistency) ---

        // Filter by status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Filter by registered_date range
        if ($request->filled('start_date')) {
            $query->whereDate('registered_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('registered_date', '<=', $request->input('end_date'));
        }

        // Filter by mkoa_wa_kipolisi
        if ($request->filled('mkoa_wa_kipolisi') && $request->input('mkoa_wa_kipolisi') !== 'all') {
            $query->where('mkoa_wa_kipolisi', $request->input('mkoa_wa_kipolisi'));
        }

        // Get the filtered card details for export
        $cardDetails = $query->get();

        // Define HTTP headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv',
            // Set the filename for the downloaded CSV, including a timestamp for uniqueness
            'Content-Disposition' => 'attachment; filename="card_details_export_' . Carbon::now()->format('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // Define the callback function that will generate the CSV content
        $callback = function() use ($cardDetails) {
            // Open a file pointer to php://output, which allows streaming directly to the browser
            $file = fopen('php://output', 'w');

            // Define the CSV header row based on the requested fields
            fputcsv($file, [
                'jina_kamili',
                'barua_pepe',
                'namba_ya_simu',
                'jinsia',
                'pf_no',
                'cheo',
                'wilaya_ya_kipolisi',
                'mkoa_wa_kipolisi',
                'kituo_cha_kazi',
                'check_namba',
                'status',
                'registered_date',
            ]);

            // Loop through each card detail and write its data to the CSV file
            foreach ($cardDetails as $card) {
                fputcsv($file, [
                    $card->jina_kamili,
                    $card->barua_pepe,
                    $card->namba_ya_simu,
                    $card->jinsia,
                    $card->pf_no,
                    $card->cheo,
                    $card->wilaya_ya_kipolisi,
                    $card->mkoa_wa_kipolisi,
                    $card->kituo_cha_kazi,
                    $card->check_namba,
                    $card->status,
                    // Format the date for CSV output, or leave empty if null
                    $card->registered_date ? $card->registered_date->format('Y-m-d') : '',
                ]);
            }
            // Close the file pointer
            fclose($file);
        };

        // Return a StreamedResponse which will execute the callback and stream the CSV to the user
        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Display the specified card detail from the local database,
     * including resolving police region/district names by fetching lists from the API.
     */
    public function show($id)
    {
        $cardDetail = CardDetail::findOrFail($id);

        // Fetch all regions and districts from API to map IDs to names
        $regions = [];
        $districts = [];
        try {
            $regionsResponse = $this->primaryApiService->get('regions');
            if ($regionsResponse && isset($regionsResponse['data'])) {
                $regions = collect($regionsResponse['data'])->keyBy('id'); // Key by ID for easy lookup
            }
            $districtsResponse = $this->primaryApiService->get('districts');
            if ($districtsResponse && isset($districtsResponse['data'])) {
                $districts = collect($districtsResponse['data'])->keyBy('id'); // Key by ID for easy lookup
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch regions/districts from API for show page name resolution: ' . $e->getMessage());
        }

        // Resolve names using the fetched lists, falling back to 'N/A' if not found
        $policeDistrictName = $cardDetail->wilaya_ya_kipolisi ?? 'N/A';
        $policeRegionName = $cardDetail->mkoa_wa_kipolisi ?? 'N/A';


        return view('card_details.show', compact('cardDetail', 'policeDistrictName', 'policeRegionName'));
    }

    /**
     * Display the form to update a card detail's status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showStatusUpdateForm($id)
    {
        $cardDetail = CardDetail::findOrFail($id);
        // Added 'Delete' to the available statuses as requested
        $availableStatuses = ['Applied',  'Rejected', 'Printed', 'Issued', 'Received'];
        return view('card_details.status_update', compact('cardDetail', 'availableStatuses'));
    }

    /**
     * Update the specified card detail's status and optionally add a comment.
     * If status is 'Rejected' or 'Delete', it will initiate deletion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Applied,Processing,Rejected,Printed,Issued,Received,Delete',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cardDetail = CardDetail::findOrFail($id);
        $newStatus = $request->input('status');
        $apiId = $cardDetail->api_id;
        $fullName = $cardDetail->jina_kamili;
        $phoneNumber = $cardDetail->namba_ya_simu;

        DB::beginTransaction();
        try {
            // Determine SMS message based on status
            $smsMessage = '';
            switch ($newStatus) {
                case 'Rejected':
                    $smsMessage = "Ndugu {$fullName}, maombi yako ya kuomba kadi ya uanachama yamekataliwa. Taarifa ulizozisajili hazipo sahihi. Tafadhali hakiki picha ya kitambulisho (PassportSize) na picha ya sahihi (Signature) na kisha uombe tena.";
                    break;
                case 'Received':
                    $smsMessage = "Ndugu {$fullName}, maombi yako ya kuomba kadi ya uanachama yamepokelewa na yanafanyiwa kazi.";
                    break;
                case 'Issued':
                    $smsMessage = "Ndugu {$fullName}, maombi yako ya kuomba kadi ya uanachama, kadi ipo tayari. Tafadhari wasiliana na mwakilishi wa URA SASSOS kwenye himaya yako.";
                    break;
                case 'Printed':
                    $smsMessage = "Ndugu {$fullName}, kadi yako ya uanachama imeshachapishwa. Utajulishwa itakapowasili kwenye himaya yako.";
                    break;
                default:
                    // No specific SMS for other statuses like 'Applied', 'Processing', 'Delete'
                    break;
            }

            // Send SMS if a message is defined and phone number exists
            if (!empty($smsMessage) && !empty($phoneNumber)) {
                $this->sendEnquirySMS($phoneNumber, $smsMessage);
            }

            if ($newStatus === 'Rejected' || $newStatus === 'Delete') {
                // Delete logic for both local and API as requested

                // Log the reason for deletion
                Log::info("Attempting to delete card detail {$id} due to status change to '{$newStatus}'.");
                // Delete associated images from local storage
                if ($cardDetail->picha_ya_kitambulisho && file_exists(public_path('uploads/id_pictures/' . $cardDetail->picha_ya_kitambulisho))) {
                    unlink(public_path('uploads/id_pictures/' . $cardDetail->picha_ya_kitambulisho));
                }
                if ($cardDetail->picha_ya_sahihi_yako && file_exists(public_path('uploads/signature_pictures/' . $cardDetail->picha_ya_sahihi_yako))) {
                    unlink(public_path('uploads/signature_pictures/' . $cardDetail->picha_ya_sahihi_yako));
                }

                // Delete from primary API (tumia api_id)
                if ($apiId) {
                    $response = $this->primaryApiService->delete("card-details/{$apiId}");
                    if (!$response || (isset($response['status']) && $response['status'] == 'error')) {
                        Log::error("Failed to delete card detail {$apiId} via API during status update to '{$newStatus}'.", ['api_response' => $response]);
                        DB::rollBack(); // Rollback local delete if API fails to maintain consistency
                        return redirect()->back()->with('error', 'Failed to delete card detail in API: ' . ($response['message'] ?? 'Unknown API error. Local data rolled back.'));
                    }
                } else {
                    Log::warning("CardDetail ID {$id} has no api_id. Deleting locally only due to '{$newStatus}' status.");
                }

                // Delete the local record
                $cardDetail->delete();
                DB::commit();
                return redirect()->route('card-details.index')->with('success', "Card detail successfully deleted due to '{$newStatus}' status.");
            } else {
                // Original update logic for other statuses
                $cardDetail->status = $newStatus;
                $cardDetail->save();

                if ($apiId) {
                    $response = $this->primaryApiService->patch("card-details/{$apiId}", [
                        'status' => $newStatus,
                    ]);
                    if (!$response || (isset($response['status']) && $response['status'] == 'error')) {
                        Log::error("Failed to update card status via API for {$apiId}.", ['api_response' => $response]);
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Failed to update card status in API: ' . ($response['message'] ?? 'Unknown API error. Local data rolled back.'));
                    }
                } else {
                    Log::warning("CardDetail ID {$id} has no api_id. Skipping API status update.");
                }

                DB::commit();
                return redirect()->route('card-details.index')->with('success', 'Card status updated successfully (local & API)!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error during status update/deletion for {$id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred during status update/deletion: ' . $e->getMessage());
        }
    }

    /**
     * Private function to send SMS.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The SMS message to send.
     * @return mixed The response from the SMS API or null on failure.
     */
    private function sendEnquirySMS($phone, $message)
    {
        $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
        $apiKey = 'xYz123#';  // Use the non-encoded key as it worked in your script

        $client = new Client(); // Use the imported GuzzleHttp Client
        try {
            $response = $client->request('POST', $url, [
                'verify' => false,  // Keep SSL verification disabled as in your working script
                'form_params' => [
                    'msisdn' => $phone,
                    'message' => $message,
                    'key' => $apiKey,
                ]
            ]);
            $responseBody = $response->getBody()->getContents();
            \Log::info("SMS sent response: " . $responseBody);
            return $responseBody;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            \Log::error("Failed to send SMS: " . $e->getMessage());
            return null;
        }
    }


    /**
     * Sync card details from the primary API to the local database,
     * reporting on new, updated, and unchanged records.
     */
    public function syncFromApi()
    {
        Log::info('Attempting to sync card details from Primary API.');
        $response = $this->primaryApiService->get('card-details');

        if (!$response || !isset($response['data']) || (isset($response['status']) && $response['status'] === 'error')) {
            Log::error('Failed to sync card details from Primary API or invalid response format.', ['response' => $response]);
            return redirect()->route('card-details.index')->with('error', $response['message'] ?? 'Failed to sync card details from API. Check logs.');
        }

        $apiCardDetails = collect($response['data']);
        $newRecordsCount = 0;
        $unchangedCount = 0; // Renamed from updatedApiCount to reflect current "only add new" logic

        DB::beginTransaction();
        try {
            foreach ($apiCardDetails as $apiData) {
                $cardObject = (object) $apiData;

                if (empty($cardObject->trackingPIN)) {
                    Log::warning('Skipping API record with no Tracking PIN:', (array) $cardObject);
                    continue;
                }

                // Check if the record already exists locally based on trackingPIN
                // "chukua only new Data, based on (trackingPIN) old data zilizo kwenye API ziache"
                $existingCardDetail = CardDetail::where('trackingPIN', $cardObject->trackingPIN)->first();

                $idPictureName = $cardObject->picha_ya_kitambulisho ?? null;
                $signaturePictureName = $cardObject->picha_ya_sahihi_yako ?? null;

                // Handle ID Picture Download
                if ($idPictureName && isset($cardObject->picha_ya_kitambulisho_url)) {
                    try {
                        $client = new Client();
                        $response = $client->get($cardObject->picha_ya_kitambulisho_url, ['verify' => false]);

                        if ($response->getStatusCode() === 200) {
                            $imageContent = $response->getBody()->getContents();
                            $localPath = public_path('uploads/id_pictures/' . $idPictureName);
                            // Ensure the directory exists
                            File::isDirectory(dirname($localPath)) or File::makeDirectory(dirname($localPath), 0777, true, true);
                            File::put($localPath, $imageContent);
                            Log::info("Downloaded ID picture: " . $idPictureName);
                        } else {
                            Log::warning("Failed to download ID picture from API URL: " . $cardObject->picha_ya_kitambulisho_url . " Status: " . $response->getStatusCode());
                            // If download fails, ensure the filename is still stored if it was provided,
                            // otherwise, it might remain null if it was originally null or if an existing image should be kept.
                            if ($existingCardDetail) {
                                $idPictureName = $existingCardDetail->picha_ya_kitambulisho;
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Error downloading ID picture " . $idPictureName . ": " . $e->getMessage());
                        if ($existingCardDetail) {
                            $idPictureName = $existingCardDetail->picha_ya_kitambulisho;
                        }
                    }
                }

                // Handle Signature Picture Download
                if ($signaturePictureName && isset($cardObject->picha_ya_sahihi_yako_url)) {
                    try {
                        $client = new Client();
                        $response = $client->get($cardObject->picha_ya_sahihi_yako_url, ['verify' => false]);

                        if ($response->getStatusCode() === 200) {
                            $imageContent = $response->getBody()->getContents();
                            $localPath = public_path('uploads/signature_pictures/' . $signaturePictureName);
                            // Ensure the directory exists
                            File::isDirectory(dirname($localPath)) or File::makeDirectory(dirname($localPath), 0777, true, true);
                            File::put($localPath, $imageContent);
                            Log::info("Downloaded Signature picture: " . $signaturePictureName);
                        } else {
                            Log::warning("Failed to download Signature picture from API URL: " . $cardObject->picha_ya_sahihi_yako_url . " Status: " . $response->getStatusCode());
                            if ($existingCardDetail) {
                                $signaturePictureName = $existingCardDetail->picha_ya_sahihi_yako;
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Error downloading Signature picture " . $signaturePictureName . ": " . $e->getMessage());
                        if ($existingCardDetail) {
                            $signaturePictureName = $existingCardDetail->picha_ya_sahihi_yako;
                        }
                    }
                }


                // Prepare data for local creation
                $dataToStore = [
                    'api_id' => $cardObject->id ?? null,
                    'trackingPIN' => $cardObject->trackingPIN, // Ensure trackingPIN is always present
                    'jina_kamili' => $cardObject->jina_kamili ?? null,
                    'barua_pepe' => $cardObject->barua_pepe ?? null,
                    'namba_ya_simu' => $cardObject->namba_ya_simu ?? null,
                    'jinsia' => $cardObject->jinsia ?? null,
                    'hali_ndoa' => $cardObject->hali_ndoa ?? null,
                    'tarehe_ya_kuzaliwa' => isset($cardObject->tarehe_ya_kuzaliwa) ? Carbon::parse($cardObject->tarehe_ya_kuzaliwa)->format('Y-m-d') : null,
                    'pf_no' => $cardObject->pf_no ?? null,
                    'cheo' => $cardObject->cheo ?? null,
                    // These fields store IDs and will be resolved to names in the 'show' method
                    'wilaya_ya_kipolisi' => $cardObject->wilaya_ya_kipolisi ?? null,
                    'mkoa_wa_kipolisi' => $cardObject->mkoa_wa_kipolisi ?? null,
                    'kituo_cha_kazi' => $cardObject->kituo_cha_kazi ?? null,
                    'check_namba' => $cardObject->check_namba ?? null,
                    'mkataba_wa_ajira' => $cardObject->mkataba_wa_ajira ?? null,
                    'eneo_unaloishi' => $cardObject->eneo_unaloishi ?? null,
                    'status' => $cardObject->status ?? 'Applied',
                    'comment' => $cardObject->comment ?? null, // Retain comment for incoming sync data
                    'picha_ya_kitambulisho' => $idPictureName, // Use the resolved name after download attempt
                    'picha_ya_sahihi_yako'=> $signaturePictureName, // Use the resolved name after download attempt
                    'registered_date' => isset($cardObject->created_at) ? Carbon::parse($cardObject->created_at)->format('Y-m-d H:i:s') : Carbon::now()->format('Y-m-d H:i:s'),
                ];

                if ($existingCardDetail) {
                    // Update existing record
                    // Only update if there are changes to avoid unnecessary 'updated_at' bumps
                    $changes = false;
                    foreach ($dataToStore as $key => $value) {
                        if ($existingCardDetail->{$key} != $value) {
                            $existingCardDetail->{$key} = $value;
                            $changes = true;
                        }
                    }
                    if ($changes) {
                        $existingCardDetail->save();
                        // You might want to count updated records here if needed
                    } else {
                        $unchangedCount++;
                    }
                } else {
                    // Create new record
                    $cardDetail = CardDetail::create($dataToStore);
                    $newRecordsCount++;
                }
            }

            DB::commit();
            return redirect()->route('card-details.index')->with('success', "Sync complete! New records: {$newRecordsCount}, Unchanged: {$unchangedCount}.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error during API sync: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('card-details.index')->with('error', 'An error occurred during API sync: ' . $e->getMessage());
        }
    }

    /**
     * Delete a specific card detail locally and from the primary API.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cardDetail = CardDetail::findOrFail($id);
        $apiId = $cardDetail->api_id; // Get the API ID for deletion in primary API

        DB::beginTransaction();
        try {
            // Delete associated images from local storage
            if ($cardDetail->picha_ya_kitambulisho && file_exists(public_path('uploads/id_pictures/' . $cardDetail->picha_ya_kitambulisho))) {
                unlink(public_path('uploads/id_pictures/' . $cardDetail->picha_ya_kitambulisho));
            }
            if ($cardDetail->picha_ya_sahihi_yako && file_exists(public_path('uploads/signature_pictures/' . $cardDetail->picha_ya_sahihi_yako))) {
                unlink(public_path('uploads/signature_pictures/' . $cardDetail->picha_ya_sahihi_yako));
            }

            // Delete from primary API (tumia api_id)
            if ($apiId) {
                $response = $this->primaryApiService->delete("card-details/{$apiId}");

                if (!$response || (isset($response['status']) && $response['status'] == 'error')) {
                    Log::error("Failed to delete card detail {$apiId} via API.", ['api_response' => $response]);
                    DB::rollBack(); // Rollback local delete if API fails to maintain consistency
                    return redirect()->back()->with('error', 'Failed to delete card detail in API: ' . ($response['message'] ?? 'Unknown API error. Local data rolled back.'));
                }
            } else {
                Log::warning("CardDetail ID {$id} has no api_id. Deleting locally only.");
            }

            // Delete the local record
            $cardDetail->delete();

            DB::commit();
            return redirect()->route('card-details.index')->with('success', 'Card detail deleted successfully (local & API)!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting card detail {$id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred during deletion: ' . $e->getMessage());
        }
    }
}