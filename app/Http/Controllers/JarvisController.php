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

        // Check if the user is asking about weather (before AI so we return live data)
        $weatherReply = $this->handleWeatherIntent($message);
        if ($weatherReply !== null) {
            $conversation[] = ['role' => 'assistant', 'content' => $weatherReply];
            $request->session()->put('conversation', $conversation);
            return response()->json([
                'success' => true,
                'reply' => $weatherReply,
                'source' => 'weather'
            ]);
        }

        // Check if the user is asking about time/date (Bangladesh timezone)
        $timeReply = $this->handleTimeIntent($message);
        if ($timeReply !== null) {
            $conversation[] = ['role' => 'assistant', 'content' => $timeReply];
            $request->session()->put('conversation', $conversation);
            return response()->json([
                'success' => true,
                'reply' => $timeReply,
                'source' => 'time'
            ]);
        }

        // Check if the user is asking to open an application
        $openReply = $this->handleOpenAppIntent($message);
        if ($openReply !== null) {
            $conversation[] = ['role' => 'assistant', 'content' => $openReply];
            $request->session()->put('conversation', $conversation);
            return response()->json([
                'success' => true,
                'reply' => $openReply,
                'source' => 'openapp'
            ]);
        }

        // Try OpenAI API
        $openaiKey = config('services.openai.api_key') ?? env('OPENAI_API_KEY') ?? $_ENV['OPENAI_API_KEY'] ?? getenv('OPENAI_API_KEY');
        $openaiReply = $this->tryOpenAIChat($conversation, $openaiKey);
        if ($openaiReply) {
            $conversation[] = ['role' => 'assistant', 'content' => $openaiReply];
            $request->session()->put('conversation', $conversation);
            return response()->json([
                'success' => true,
                'reply' => $openaiReply,
                'source' => 'openai'
            ]);
        }

        // Try Groq API (free, fast)
        $groqKey = config('services.groq.api_key') ?? env('GROQ_API_KEY') ?? $_ENV['GROQ_API_KEY'] ?? getenv('GROQ_API_KEY');
        $groqReply = $this->tryGroqChat($conversation, $groqKey);
        if ($groqReply) {
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

        $apps = $this->getAppList();

        if (isset($apps[$app])) {
            if ($this->launchApp($app)) {
                return response()->json([
                    'success' => true,
                    'message' => "Opening {$app}, sir.",
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "I'm sorry, sir. I couldn't launch '{$app}'.",
            ]);
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

    private function tryOpenAIChat($conversation, $apiKey)
    {
        if (!$apiKey) return null;

        try {
            $messages = array_merge(
                [['role' => 'system', 'content' => $this->getJarvisSystemPrompt()]],
                $conversation
            );

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
                'max_tokens' => 4096,
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

    private function tryGroqChat($conversation, $apiKey)
    {
        if (!$apiKey) return null;

        try {
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
                'max_tokens' => 4096,
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

    /**
     * Detect if the message asks for time or date, return Bangladesh time, or null.
     */
    private function handleTimeIntent($message)
    {
        $lower = mb_strtolower(trim($message), 'UTF-8');

        $timeKw = ['what time', "what's the time", 'what is the time', 'current time', 'time now', 'সময়', 'সময়', 'কয়টা', 'কয়টা', 'ঘড়িতে', 'ঘড়ি'];
        $dateKw = ['what date', "what's the date", 'what is the date', 'current date', "today's date", 'today date', 'তারিখ', 'কত তারিখ'];

        $askTime = false;
        foreach ($timeKw as $kw) {
            if (mb_strpos($lower, $kw) !== false) { $askTime = true; break; }
        }
        $askDate = false;
        foreach ($dateKw as $kw) {
            if (mb_strpos($lower, $kw) !== false) { $askDate = true; break; }
        }

        // "what time is it" & "what date" edge: "time" alone shouldn't trigger (e.g. "one more time")
        if (mb_strpos($lower, 'what time') === false
            && mb_strpos($lower, "what's the time") === false
            && mb_strpos($lower, 'current time') === false
            && preg_match('/\btime\b/', $lower) && preg_match('/\?$/', trim($lower))) {
            $askTime = true;
        }

        if (!$askTime && !$askDate) {
            return null;
        }

        $now = now();
        $bd = $now->setTimezone('Asia/Dhaka');

        if ($askTime && $askDate) {
            return "🕐 It is **{$bd->format('h:i A')}** on **{$bd->format('l, F j, Y')}**, Bangladesh Standard Time (GMT+6), sir.";
        }
        if ($askTime) {
            return "🕐 The current time in Bangladesh is **{$bd->format('h:i A')}** (GMT+6), sir.";
        }
        return "📅 Today's date is **{$bd->format('l, F j, Y')}** in Bangladesh, sir.";
    }

    /**
     * Detect an "open <app>" request and launch the app via terminal, or null.
     */
    private function handleOpenAppIntent($message)
    {
        $lower = mb_strtolower(trim($message), 'UTF-8');

        // Match: "open X", "open the X", "launch X", "start X", "খুলো X", "খুলুন X", "ওপেন X"
        if (!preg_match('/(?:^|\s)(?:open|launch|start|run|খোলো|খুলো|খুলুন|খুলে দাও|ওপেন|চালু কর)\s+(?:the\s+)?([a-zA-Z0-9 .\-_]{2,30})/iu', $lower, $m)) {
            return null;
        }

        $raw = mb_strtolower(trim($m[1]), 'UTF-8');
        if ($this->isStopword($raw)) {
            return null;
        }

        $apps = $this->getAppList();
        $appKey = null;
        foreach ($apps as $key => $cmd) {
            if ($raw === $key || str_contains($key, $raw) || str_contains($raw, $key)) {
                $appKey = $key;
                break;
            }
        }
        // Only intercept if we actually have a matching app; otherwise let the AI chat handle it
        if ($appKey === null) {
            return null;
        }

        $launched = $this->launchApp($appKey);
        return $launched
            ? "🚀 Launching **{$appKey}** for you now, sir."
            : "I'm sorry, sir. I could not launch **{$appKey}**.";
    }

    private function isStopword($w)
    {
        $stopwords = ['the', 'a', 'an', 'this', 'that', 'please', 'sir', 'now', 'it'];
        return in_array($w, $stopwords);
    }

    /**
     * Shared list of launchable apps (name => command per OS).
     */
    private function getAppList()
    {
        return [
            'notepad' => ['windows' => 'notepad.exe', 'linux' => 'gedit', 'mac' => 'open -a TextEdit'],
            'calculator' => ['windows' => 'calc.exe', 'linux' => 'gnome-calculator', 'mac' => 'open -a Calculator'],
            'chrome' => ['windows' => 'start chrome', 'linux' => 'google-chrome', 'mac' => 'open -a Google Chrome'],
            'firefox' => ['windows' => 'start firefox', 'linux' => 'firefox', 'mac' => 'open -a Firefox'],
            'edge' => ['windows' => 'start msedge', 'linux' => 'microsoft-edge', 'mac' => 'open -a Microsoft Edge'],
            'cmd' => ['windows' => 'cmd.exe', 'linux' => 'gnome-terminal', 'mac' => 'open -a Terminal'],
            'terminal' => ['windows' => 'cmd.exe', 'linux' => 'gnome-terminal', 'mac' => 'open -a Terminal'],
            'powershell' => ['windows' => 'powershell.exe', 'linux' => 'gnome-terminal', 'mac' => 'open -a Terminal'],
            'explorer' => ['windows' => 'explorer.exe', 'linux' => 'nautilus', 'mac' => 'open .'],
            'file manager' => ['windows' => 'explorer.exe', 'linux' => 'nautilus', 'mac' => 'open .'],
            'vscode' => ['windows' => 'start code', 'linux' => 'code', 'mac' => 'open -a "Visual Studio Code"'],
            'visual studio code' => ['windows' => 'start code', 'linux' => 'code', 'mac' => 'open -a "Visual Studio Code"'],
            'paint' => ['windows' => 'mspaint.exe', 'linux' => 'gimp', 'mac' => 'open -a Preview'],
            'spotify' => ['windows' => 'start spotify', 'linux' => 'spotify', 'mac' => 'open -a Spotify'],
            'discord' => ['windows' => 'start discord', 'linux' => 'discord', 'mac' => 'open -a Discord'],
            'slack' => ['windows' => 'start slack', 'linux' => 'slack', 'mac' => 'open -a Slack'],
            'word' => ['windows' => 'start winword', 'linux' => 'libreoffice --writer', 'mac' => 'open -a "Microsoft Word"'],
            'excel' => ['windows' => 'start excel', 'linux' => 'libreoffice --calc', 'mac' => 'open -a "Microsoft Excel"'],
            'powerpoint' => ['windows' => 'start powerpnt', 'linux' => 'libreoffice --impress', 'mac' => 'open -a "Microsoft PowerPoint"'],
            'ms word' => ['windows' => 'start winword', 'linux' => 'libreoffice --writer', 'mac' => 'open -a "Microsoft Word"'],
            'ms excel' => ['windows' => 'start excel', 'linux' => 'libreoffice --calc', 'mac' => 'open -a "Microsoft Excel"'],
            'ms powerpoint' => ['windows' => 'start powerpnt', 'linux' => 'libreoffice --impress', 'mac' => 'open -a "Microsoft PowerPoint"'],
            'task manager' => ['windows' => 'taskmgr.exe', 'linux' => 'gnome-system-monitor', 'mac' => 'open -a "Activity Monitor"'],
            'control panel' => ['windows' => 'control.exe', 'linux' => 'gnome-control-center', 'mac' => 'open -a "System Settings"'],
            'settings' => ['windows' => 'start ms-settings:', 'linux' => 'gnome-control-center', 'mac' => 'open -a "System Settings"'],
        ];
    }

    private function launchApp($app)
    {
        $config = $this->getAppList();
        if (!isset($config[$app])) return false;

        $os = PHP_OS_FAMILY === 'Windows' ? 'windows' : (PHP_OS_FAMILY === 'Darwin' ? 'mac' : 'linux');
        $command = $config[$app][$os] ?? null;
        if (!$command) return false;

        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $full = 'start "" ' . $command;
                $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
                $proc = proc_open($full, $descriptors, $pipes);
                if (is_resource($proc)) proc_close($proc);
            } else {
                exec($command . ' > /dev/null 2>&1 &');
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Detect if the message asks for weather and return live data, or null.
     */
    private function handleWeatherIntent($message)
    {
        $lower = mb_strtolower(trim($message), 'UTF-8');

        $enKeywords = ['weather', 'temperature', "today's temp", 'how hot', 'how cold', 'forecast'];
        $bnKeywords = ['আবহাওয়া', 'আবহাওয়া', 'তাপমাত্রা', 'গরম', 'ঠান্ডা', 'বৃষ্টি', 'রোদ', 'কেমন জলবায়ু'];

        $isWeather = false;
        foreach (array_merge($enKeywords, $bnKeywords) as $kw) {
            if (mb_strpos($lower, $kw) !== false) {
                $isWeather = true;
                break;
            }
        }
        if (!$isWeather) {
            return null;
        }

        // Try to extract a city name
        $city = $this->extractCity($message, $lower);
        if ($city === null) {
            return "I can check the weather for any city, sir. Just tell me the city name — for example, \"Weather in Dhaka\" or \"ঢাকার আবহাওয়া কী?\"";
        }

        $weather = $this->getWeatherData($city);
        if ($weather === null) {
            return "I'm sorry, sir. I couldn't retrieve the weather for {$city} at the moment.";
        }

        return $this->formatWeatherReply($city, $weather);
    }

    /**
     * Try to extract a city name from the message.
     */
    private function extractCity($message, $lower)
    {
        // English: "weather in X" / "X weather" / "weather of X"
        if (preg_match('/weather\s+(?:in|of|for|at)\s+([a-zA-Z ]+)/', $lower, $m)) {
            $c = trim($m[1]);
            if ($this->isPlausibleCity($c)) return $c;
        }
        if (preg_match('/temperature\s+(?:in|of|for|at)\s+([a-zA-Z ]+)/', $lower, $m)) {
            $c = trim($m[1]);
            if ($this->isPlausibleCity($c)) return $c;
        }
        // "X weather" (city before weather)
        if (preg_match('/([a-zA-Z ]+?)\s+weather(?: now|\?|$|,)/', $lower, $m)) {
            $c = trim($m[1]);
            if ($this->isPlausibleCity($c)) return $c;
        }

        // Bengali: "X এর আবহাওয়া" / "X-এর আবহাওয়া" / "X শহরের আবহাওয়া"
        if (preg_match('/^([\x{0980}-\x{09FF}\s]+?)\s*(?:র|ের|এর|\s+শহরের|\s+শহরে)?\s*(?:আবহাওয়া|আবহাওয়া)/u', $message, $m)) {
            $c = trim($m[1]);
            if (mb_strlen($c, 'UTF-8') <= 15 && $c !== '') return $c;
        }
        if (preg_match('/^([\x{0980}-\x{09FF}\s]+?)\s*(?:তমাত্রা|তাপমাত্রা)/u', $message, $m)) {
            $c = trim($m[1]);
            // strip trailing Bengali possessive markers: করা, র, এর, ে, য় (e.g. চট্টগ্রামের -> চট্টগ্রাম)
            $c = preg_replace('/(?:ের|এর|রে|য়|ে|র)$/u', '', $c);
            if (mb_strlen($c, 'UTF-8') <= 15 && $c !== '') return $c;
        }

        return null;
    }

    private function isPlausibleCity($c)
    {
        $stopwords = ['now', 'today', 'tomorrow', 'tonight', 'like', 'please', 'and', 'the', 'for', 'sir'];
        $words = preg_split('/\s+/', trim($c));
        foreach ($words as $w) {
            $w = trim(str_replace(['?', ',', '!'], '', $w));
            if (!$w || in_array($w, $stopwords)) return false;
        }
        return count($words) <= 4;
    }

    private function getWeatherData($city)
    {
        $apiKey = config('services.weather.api_key', env('WEATHER_API_KEY'));
        if (!$apiKey) {
            return null;
        }
        try {
            $response = Http::timeout(10)->get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'en',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'city' => $data['name'],
                    'country' => $data['sys']['country'] ?? '',
                    'temp' => round($data['main']['temp']),
                    'feels_like' => round($data['main']['feels_like']),
                    'temp_min' => round($data['main']['temp_min']),
                    'temp_max' => round($data['main']['temp_max']),
                    'humidity' => $data['main']['humidity'],
                    'pressure' => $data['main']['pressure'],
                    'description' => $data['weather'][0]['description'],
                    'wind_speed' => $data['wind']['speed'],
                    'icon' => $data['weather'][0]['icon'],
                ];
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

    private function formatWeatherReply($city, $w)
    {
        return "🌤️ **Weather update, sir.**\n\n"
            . "📍 **{$w['city']}**, {$w['country']}\n"
            . "🌡️ Temperature: **{$w['temp']}°C** (feels like {$w['feels_like']}°C)\n"
            . "📉 Low: {$w['temp_min']}°C · 📈 High: {$w['temp_max']}°C\n"
            . "💧 Humidity: {$w['humidity']}% · 💨 Wind: {$w['wind_speed']} m/s\n"
            . "☁️ Conditions: {$w['description']}";
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
