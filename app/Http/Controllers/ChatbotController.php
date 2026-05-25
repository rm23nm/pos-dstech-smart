<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');
        
        $freeKeysStr = env('GEMINI_API_KEYS_FREE', '');
        $paidKey = env('GEMINI_API_KEY_PAID', '');
        
        $freeKeys = array_filter(explode(',', $freeKeysStr));
        
        if (empty($freeKeys) && empty($paidKey)) {
            return response()->json([
                'success' => true,
                'reply' => "Mohon maaf, API Key Gemini belum dikonfigurasi di server."
            ]);
        }

        $context = $request->input('context', 'frontend');
        
        if ($context === 'backend') {
            $systemInstruction = env('GEMINI_PROMPT_BACKEND', 'Anda adalah AI Support Technical Assistant.');
        } else {
            $systemInstruction = env('GEMINI_PROMPT_FRONTEND', 'Anda adalah AI Customer Service DSMS POS.');
        }

        $client = new Client(['http_errors' => false]);
        $model = env('GEMINI_MODEL', 'gemini-2.5-flash'); 
        
        // Coba kunci gratis secara rotasi
        $currentIndex = Cache::get('gemini_free_key_index', 0);
        $attempts = 0;
        $maxAttempts = count($freeKeys);
        
        while ($attempts < $maxAttempts && !empty($freeKeys)) {
            $currentKey = $freeKeys[$currentIndex % count($freeKeys)];
            
            try {
                $reply = $this->callGeminiAPI($client, $model, $currentKey, $systemInstruction, $userMessage);
                if ($reply !== false) {
                    Cache::put('gemini_free_key_index', ($currentIndex + 1) % count($freeKeys));
                    return response()->json(['success' => true, 'reply' => $reply]);
                } else {
                    // Berarti 429 error, coba next key
                    $attempts++;
                    $currentIndex++;
                    continue;
                }
            } catch (\Exception $e) {
                // Fallback 1.5
                if (strpos($e->getMessage(), 'models/gemini-2.5-flash') !== false) {
                    try {
                        $reply = $this->callGeminiAPI($client, 'gemini-1.5-flash', $currentKey, $systemInstruction, $userMessage);
                        if ($reply !== false) {
                            Cache::put('gemini_free_key_index', ($currentIndex + 1) % count($freeKeys));
                            return response()->json(['success' => true, 'reply' => $reply]);
                        } else {
                            $attempts++;
                            $currentIndex++;
                            continue;
                        }
                    } catch (\Exception $e2) {
                        break;
                    }
                }
                break;
            }
        }
        
        // Jika semua kunci gratis habis / limit (return false), gunakan kunci berbayar
        if (!empty($paidKey)) {
            try {
                $reply = $this->callGeminiAPI($client, $model, $paidKey, $systemInstruction, $userMessage);
                if ($reply !== false) {
                    return response()->json(['success' => true, 'reply' => $reply]);
                }
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'models/gemini-2.5-flash') !== false) {
                    try {
                        $reply = $this->callGeminiAPI($client, 'gemini-1.5-flash', $paidKey, $systemInstruction, $userMessage);
                        if ($reply !== false) {
                            return response()->json(['success' => true, 'reply' => $reply]);
                        }
                    } catch (\Exception $e2) {}
                }
            }
        }

        return response()->json([
            'success' => false,
            'reply' => 'Sistem AI sedang sibuk atau seluruh kuota API telah habis. Silakan coba lagi beberapa saat kemudian.'
        ]);
    }

    private function callGeminiAPI($client, $model, $apiKey, $systemInstruction, $userMessage)
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'system_instruction' => ['parts' => [['text' => $systemInstruction]]],
                'contents' => [['role' => 'user', 'parts' => [['text' => $userMessage]]]],
                'generationConfig' => ['temperature' => 0.7, 'maxOutputTokens' => 800]
            ]
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        if ($statusCode == 429) { // Too Many Requests / Quota exhausted
            return false;
        }

        if ($statusCode != 200) {
            throw new \Exception("Gemini API Error: " . $body);
        }

        $result = json_decode($body, true);
        $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak dapat merespon saat ini.';
        
        $reply = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $reply);
        return nl2br($reply);
    }
}
