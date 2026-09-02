<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JarvisController extends Controller
{
    /**
     * Show the Jarvis main page
     */
    public function index()
    {
        return view('frontend.index');
    }

    public function chatPage()
    {
        return view('frontend.chat');
    }

    /**
     * Chat with Jarvis using Groq AI (free, fast)
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = $request->input('message');
        $history = $request->input('history', []);

        // Store conversation in session
        $conversation = $request->session()->get('conversation', []);
        $conversation[] = ['role' => 'user', 'content' => $message];

        // Keep only last 20 messages to avoid token limits
        if (count($conversation) > 20) {
            $conversation = array_slice($conversation, -20);
        }

        $request->session()->put('conversation', $conversation);

        // Try Groq API (free, fast)
        $groqKey = config('services.groq.api_key') ?? env('GROQ_API_KEY') ?? $_ENV['GROQ_API_KEY'] ?? getenv('GROQ_API_KEY');
        $groqReply = $this->tryGroqChat($conversation, $groqKey);
        if ($groqReply) {
            // Store AI reply in session
            $conversation[] = ['role' => 'assistant', 'content' => $groqReply];
            $request->session()->put('conversation', $conversation);

            return response()->json([
                'success' => true,
                'reply' => $groqReply,
                'source' => 'groq'
            ]);
        }

        // Fallback to local smart responses
        return response()->json([
            'success' => true,
            'reply' => $this->getLocalResponse($message),
            'source' => 'local'
        ]);
    }

    /**
     * Get weather information
     */
    public function weather(Request $request)
    {
        $city = $request->input('city', 'Khulna');
        $apiKey = config('services.weather.api_key', env('WEATHER_API_KEY'));

        if ($apiKey) {
            try {
                $response = Http::timeout(10)->get("https://api.openweathermap.org/data/2.5/weather", [
                    'q' => $city,
                    'appid' => $apiKey,
                    'units' => 'metric',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return response()->json([
                        'success' => true,
                        'city' => $data['name'],
                        'country' => $data['sys']['country'] ?? '',
                        'temp' => round($data['main']['temp']),
                        'feels_like' => round($data['main']['feels_like']),
                        'humidity' => $data['main']['humidity'],
                        'description' => $data['weather'][0]['description'],
                        'icon' => $data['weather'][0]['icon'],
                        'wind_speed' => $data['wind']['speed'],
                    ]);
                }
            } catch (\Exception $e) {
                // Fall through to local weather
            }
        }

        // Local/fake weather data
        return response()->json([
            'success' => true,
            'city' => $city,
            'country' => 'BD',
            'temp' => 32,
            'feels_like' => 36,
            'humidity' => 78,
            'description' => 'partly cloudy',
            'icon' => '02d',
            'wind_speed' => 5.2,
        ]);
    }

    /**
     * Get system information
     */
    public function systemInfo()
    {
        $info = [
            'hostname' => gethostname() ?? 'Unknown',
            'os' => PHP_OS,
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'uptime' => $this->getUptime(),
            'cpu' => $this->getCpuInfo(),
            'memory' => $this->getMemoryInfo(),
            'disk' => $this->getDiskInfo(),
            'laravel_version' => app()->version(),
        ];

        return response()->json([
            'success' => true,
            'data' => $info
        ]);
    }

    /**
     * Web search
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:500',
        ]);

        $query = $request->input('query');
        $encodedQuery = urlencode($query);

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => [
                [
                    'title' => "Search results for: {$query}",
                    'url' => "https://www.google.com/search?q={$encodedQuery}",
                    'snippet' => "Click to search on Google for '{$query}'",
                ],
            ],
            'google_url' => "https://www.google.com/search?q={$encodedQuery}",
            'youtube_url' => "https://www.youtube.com/results?search_query={$encodedQuery}",
            'github_url' => "https://github.com/search?q={$encodedQuery}",
        ]);
    }

    /**
     * Open application by name
     */
    public function openApp(Request $request)
    {
        $request->validate([
            'app' => 'required|string|max:100',
        ]);

        $app = strtolower($request->input('app'));

        $apps = [
            'notepad' => ['windows' => 'notepad.exe', 'linux' => 'gedit', 'mac' => 'open -a TextEdit'],
            'calculator' => ['windows' => 'calc.exe', 'linux' => 'gnome-calculator', 'mac' => 'open -a Calculator'],
            'chrome' => ['windows' => 'start chrome', 'linux' => 'google-chrome', 'mac' => 'open -a Google Chrome'],
            'firefox' => ['windows' => 'start firefox', 'linux' => 'firefox', 'mac' => 'open -a Firefox'],
            'cmd' => ['windows' => 'cmd.exe', 'linux' => 'gnome-terminal', 'mac' => 'open -a Terminal'],
            'terminal' => ['windows' => 'cmd.exe', 'linux' => 'gnome-terminal', 'mac' => 'open -a Terminal'],
            'explorer' => ['windows' => 'explorer.exe', 'linux' => 'nautilus', 'mac' => 'open .'],
            'file manager' => ['windows' => 'explorer.exe', 'linux' => 'nautilus', 'mac' => 'open .'],
            'vscode' => ['windows' => 'start code', 'linux' => 'code', 'mac' => 'open -a "Visual Studio Code"'],
            'visual studio code' => ['windows' => 'start code', 'linux' => 'code', 'mac' => 'open -a "Visual Studio Code"'],
            'paint' => ['windows' => 'mspaint.exe', 'linux' => 'gimp', 'mac' => 'open -a Preview'],
            'spotify' => ['windows' => 'start spotify', 'linux' => 'spotify', 'mac' => 'open -a Spotify'],
            'discord' => ['windows' => 'start discord', 'linux' => 'discord', 'mac' => 'open -a Discord'],
            'slack' => ['windows' => 'start slack', 'linux' => 'slack', 'mac' => 'open -a Slack'],
        ];

        if (isset($apps[$app])) {
            $os = PHP_OS_FAMILY === 'Windows' ? 'windows' : (PHP_OS_FAMILY === 'Darwin' ? 'mac' : 'linux');
            $command = $apps[$app][$os] ?? null;

            if ($command) {
                if (PHP_OS_FAMILY === 'Windows') {
                    $fullCommand = 'start "" ' . $command;
                } else {
                    $fullCommand = $command . ' > /dev/null 2>&1 &';
                }

                exec($fullCommand);
                return response()->json([
                    'success' => true,
                    'message' => "Opening {$app}, sir.",
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => "I'm sorry, sir. I don't recognize the application '{$app}'. Available apps: " . implode(', ', array_keys($apps)),
        ]);
    }

    // ========== PRIVATE HELPER METHODS ==========

    private function getJarvisSystemPrompt()
    {
        return 'You are JARVIS (Just A Rather Very Intelligent System), an advanced AI assistant created by Tony Stark. You are helpful, witty, and slightly sarcastic like the real JARVIS from Iron Man. Respond in a mix of English and casual style. Keep responses concise but friendly. You can help with: weather, system info, web search, launching apps, and general conversation.';
    }

    private function tryGroqChat($conversation, $apiKey)
    {
        if (!$apiKey) return null;

        try {
            // Build messages array with system prompt + full conversation history
            $messages = array_merge(
                [['role' => 'system', 'content' => $this->getJarvisSystemPrompt()]],
                $conversation
            );

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'openai/gpt-oss-120b',
                'messages' => $messages,
                'max_tokens' => 20000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content', null);
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    private function getLocalResponse($message)
    {
        $message = strtolower(trim($message));

        $responses = [
            'hello' => "Good day, sir. How may I assist you today?",
            'hi' => "Hello, sir. What can I do for you?",
            'how are you' => "All systems operational, sir. Running at optimal efficiency.",
            'who are you' => "I am JARVIS — Just A Rather Very Intelligent System. Your personal AI assistant, sir.",
            'what can you do' => "I can chat with you, check weather, monitor system stats, search the web, and launch applications. All at your command, sir.",
            'time' => "The current time is " . now()->format('h:i A') . ", sir.",
            'date' => "Today is " . now()->format('l, F j, Y') . ", sir.",
            'thank you' => "At your service, sir. Always.",
            'thanks' => "My pleasure, sir. That's what I'm here for.",
            'help' => "I can help you with:\n• 💬 Chat & Conversation\n• 🌤️ Weather Information\n• 💻 System Information\n• 🔍 Web Search\n• 🚀 Launch Applications\n\nJust ask me anything, sir!",
            'weather' => "I'll check the weather for you, sir. You can ask me about any city's weather.",
        ];

        foreach ($responses as $key => $response) {
            if (str_contains($message, $key)) {
                return $response;
            }
        }

        return "I understand, sir. Please set your GROQ_API_KEY in .env file for AI-powered responses.";
    }

    private function getUptime()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('systeminfo | find "System Boot Time"');
            return $output ?? 'N/A';
        }
        $output = shell_exec('uptime -p 2>/dev/null || uptime');
        return trim($output ?? 'N/A');
    }

    private function getCpuInfo()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('wmic cpu get name /value 2>nul');
            if (preg_match('/Name=(.+)/', $output, $matches)) {
                return trim($matches[1]);
            }
        } else {
            $output = shell_exec('cat /proc/cpuinfo | grep "model name" | head -1 2>/dev/null');
            if (preg_match('/model name\s*:\s*(.+)/', $output, $matches)) {
                return trim($matches[1]);
            }
        }
        return 'Unknown CPU';
    }

    private function getMemoryInfo()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $total = shell_exec('wmic OS get TotalVisibleMemorySize /value 2>nul');
            $free = shell_exec('wmic OS get FreePhysicalMemory /value 2>nul');
            preg_match('/TotalVisibleMemorySize=(\d+)/', $total, $totalMatch);
            preg_match('/FreePhysicalMemory=(\d+)/', $free, $freeMatch);
            $totalMB = isset($totalMatch[1]) ? round($totalMatch[1] / 1024) : 0;
            $freeMB = isset($freeMatch[1]) ? round($freeMatch[1] / 1024) : 0;
            return [
                'total' => $totalMB . ' MB',
                'free' => $freeMB . ' MB',
                'used' => ($totalMB - $freeMB) . ' MB',
                'percent' => $totalMB > 0 ? round((($totalMB - $freeMB) / $totalMB) * 100) : 0,
            ];
        } else {
            $memInfo = shell_exec('free -m 2>/dev/null');
            if (preg_match('/Mem:\s+(\d+)\s+(\d+)\s+(\d+)/', $memInfo, $matches)) {
                return [
                    'total' => $matches[1] . ' MB',
                    'used' => $matches[2] . ' MB',
                    'free' => $matches[3] . ' MB',
                    'percent' => round(($matches[2] / $matches[1]) * 100),
                ];
            }
        }
        return ['total' => 'N/A', 'used' => 'N/A', 'free' => 'N/A', 'percent' => 0];
    }

    private function getDiskInfo()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('wmic logicaldisk where "DeviceID=\'C:\'" get Size,FreeSpace /value 2>nul');
            preg_match('/FreeSpace=(\d+)/', $output, $freeMatch);
            preg_match('/Size=(\d+)/', $output, $sizeMatch);
            $totalGB = isset($sizeMatch[1]) ? round($sizeMatch[1] / (1024 * 1024 * 1024), 1) : 0;
            $freeGB = isset($freeMatch[1]) ? round($freeMatch[1] / (1024 * 1024 * 1024), 1) : 0;
            return [
                'total' => $totalGB . ' GB',
                'free' => $freeGB . ' GB',
                'used' => ($totalGB - $freeGB) . ' GB',
                'percent' => $totalGB > 0 ? round((($totalGB - $freeGB) / $totalGB) * 100) : 0,
            ];
        } else {
            $output = shell_exec('df -h / 2>/dev/null | tail -1');
            if (preg_match('/\s+(\d+)G\s+(\d+)G\s+(\d+)G\s+(\d+)%/', $output, $matches)) {
                return [
                    'total' => $matches[1] . ' GB',
                    'used' => $matches[2] . ' GB',
                    'free' => $matches[3] . ' GB',
                    'percent' => (int)$matches[4],
                ];
            }
        }
        return ['total' => 'N/A', 'used' => 'N/A', 'free' => 'N/A', 'percent' => 0];
    }
}
