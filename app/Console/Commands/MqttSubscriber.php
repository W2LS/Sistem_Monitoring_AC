<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\AcLog;
use Illuminate\Support\Carbon;
use PhpMqtt\Client\Exceptions\MqttClientException;

#[Signature('mqtt:subscribe')]
#[Description('Subscribe to EMQX MQTT topic to receive AC monitoring logs from ESP32/Raspberry Pi')]
class MqttSubscriber extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $server   = env('MQTT_HOST', '127.0.0.1');
        $port     = (int) env('MQTT_PORT', 1883);
        $clientId = env('MQTT_CLIENT_ID', 'laravel_mqtt_client') . '_' . rand(1000, 9999);
        $username = env('MQTT_USERNAME');
        $password = env('MQTT_PASSWORD');
        $topic    = 'pindad/ac/logs';

        if ($username === 'null') $username = null;
        if ($password === 'null') $password = null;

        $this->info("Connecting to MQTT Broker at {$server}:{$port}...");

        while (true) {
            try {
                $mqtt = new MqttClient($server, $port, $clientId);

                $connectionSettings = (new ConnectionSettings)
                    ->setUsername($username)
                    ->setPassword($password)
                    ->setKeepAliveInterval(60)
                    ->setLastWillTopic('pindad/ac/status')
                    ->setLastWillMessage('offline')
                    ->setLastWillQualityOfService(1);

                $mqtt->connect($connectionSettings, true);
                $this->info("Connected successfully! Subscribing to topic: {$topic}");

                $mqtt->subscribe($topic, function (string $topic, string $message) {
                    $this->info("Received message on [{$topic}]: {$message}");

                    try {
                        $data = json_decode($message, true);

                        if (!$data) {
                            $this->error("Invalid JSON format received.");
                            return;
                        }

                        // Validate required keys
                        if (!isset($data['device_id']) || !isset($data['active_ac']) || !isset($data['current_ampere'])) {
                            $this->error("Missing required keys in payload.");
                            return;
                        }

                        // Parse or fallback recorded_at
                        $recordedAt = isset($data['recorded_at']) 
                            ? Carbon::parse($data['recorded_at']) 
                            : now();

                        // Insert into DB
                        $log = AcLog::create([
                            'device_id'      => $data['device_id'],
                            'active_ac'      => $data['active_ac'],
                            'current_ampere' => (float) $data['current_ampere'],
                            'recorded_at'    => $recordedAt,
                        ]);

                        $this->info("Saved log ID {$log->id} to database (Device: {$log->device_id}, Current: {$log->current_ampere} A)");

                    } catch (\Exception $e) {
                        $this->error("Error processing message: " . $e->getMessage());
                    }
                }, 0);

                // Start loop to stay connected and process incoming messages
                $mqtt->loop(true);

            } catch (MqttClientException $e) {
                $this->error("MQTT client error: " . $e->getMessage() . ". Reconnecting in 5 seconds...");
                sleep(5);
            } catch (\Exception $e) {
                $this->error("General error: " . $e->getMessage() . ". Reconnecting in 5 seconds...");
                sleep(5);
            }
        }
    }
}
