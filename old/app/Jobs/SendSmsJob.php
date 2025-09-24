<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $message;
    protected $apiKey; 

    /**
     * Create a new job instance.
     *
     * @param string $phone
     * @param string $message
     * @return void
     */
    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
        $this->apiKey = 'xYz123#'; // Your real API key here
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Ongeza pause fupi kabla ya kutuma SMS, mfano sekunde 1
        // Hii inasaidia kuepuka 'Too Many Requests' kutoka API
        sleep(1); 

        $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
        $client = new Client();

        try {
            $response = $client->request('POST', $url, [
                'verify' => false,
                'form_params' => [
                    'msisdn'  => $this->phone,
                    'message' => $this->message,
                    'key'     => $this->apiKey,
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            Log::info("SMS sent via queue to {$this->phone}: " . $responseBody);
            // Hapa unaweza kuongeza logic ya kusasisha database yako
            // kuashiria SMS imetumwa kwa mafanikio.
            return true;

        } catch (GuzzleException $e) {
            Log::error("Failed to send SMS via queue to {$this->phone}: " . $e->getMessage());
            // Hii itaashiria job imefeli na Laravel itaijaribu tena kulingana na mipangilio ya queue yako
            $this->fail($e); 
            return false;
        }
    }
}