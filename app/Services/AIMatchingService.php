<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AIMatchingService
{
    private $apiKey;
    private $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Find potential matches untuk lost report menggunakan AI
     */
    public function findPotentialMatches(Report $lostReport, int $limit = 5): array
    {
        // Get found reports yang belum matched
        $foundReports = Report::with('category')
            ->where('report_type', 'FOUND')
            ->whereIn('report_status', ['OPEN', 'STORED'])
            ->whereDoesntHave('matchesAsFound', function($query) {
                $query->where('match_status', 'CONFIRMED');
            })
            ->get();

        if ($foundReports->isEmpty()) {
            return [];
        }

        // Analyze dengan AI
        $matches = [];
        foreach ($foundReports as $foundReport) {
            $score = $this->calculateMatchScore($lostReport, $foundReport);
            
            if ($score['confidence'] >= 60) { // Threshold 60%
                $matches[] = [
                    'found_report' => $foundReport,
                    'confidence' => $score['confidence'],
                    'reasoning' => $score['reasoning'],
                    'similarities' => $score['similarities'],
                    'differences' => $score['differences'] ?? [],
                    'recommendation' => $score['recommendation'],
                ];
            }
        }

        // Sort by confidence desc
        usort($matches, fn($a, $b) => $b['confidence'] <=> $a['confidence']);

        return array_slice($matches, 0, $limit);
    }

    /**
     * Calculate match score antara lost dan found report
     */
    private function calculateMatchScore(Report $lostReport, Report $foundReport): array
    {
        try {
            $prompt = $this->buildMatchingPrompt($lostReport, $foundReport);
            
            // Call Gemini API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/models/gemini-1.5-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => $this->buildPromptParts($lostReport, $foundReport, $prompt)
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3, // Lower = more consistent
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error', ['response' => $response->json()]);
                return $this->fallbackMatching($lostReport, $foundReport);
            }

            $result = $response->json();
            $aiResponse = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

            return $this->parseAIResponse($aiResponse);

        } catch (\Exception $e) {
            Log::error('AI Matching Error', ['error' => $e->getMessage()]);
            return $this->fallbackMatching($lostReport, $foundReport);
        }
    }

    /**
     * Build prompt parts (text + images jika ada)
     */
    private function buildPromptParts(Report $lostReport, Report $foundReport, string $prompt): array
    {
        $parts = [['text' => $prompt]];

        // Add lost report image
        if ($lostReport->photo_url) {
            $imageData = $this->getImageBase64($lostReport->photo_url);
            if ($imageData) {
                $parts[] = [
                    'inline_data' => [
                        'mime_type' => 'image/jpeg',
                        'data' => $imageData
                    ]
                ];
            }
        }

        // Add found report image
        if ($foundReport->photo_url) {
            $imageData = $this->getImageBase64($foundReport->photo_url);
            if ($imageData) {
                $parts[] = [
                    'inline_data' => [
                        'mime_type' => 'image/jpeg',
                        'data' => $imageData
                    ]
                ];
            }
        }

        return $parts;
    }

    /**
     * Build matching prompt
     */
    private function buildMatchingPrompt(Report $lostReport, Report $foundReport): string
    {
        $lostCategory = $lostReport->category ? $lostReport->category->category_name : 'N/A';
        $foundCategory = $foundReport->category ? $foundReport->category->category_name : 'N/A';
        
        return <<<PROMPT
You are an AI expert in matching lost and found items. Analyze these two reports and determine if they are the same item.

LOST REPORT:
- Item Name: {$lostReport->item_name}
- Description: {$lostReport->report_description}
- Category: {$lostCategory}
- Location: {$lostReport->report_location}
- Date: {$lostReport->report_datetime->format('Y-m-d H:i')}

FOUND REPORT:
- Item Name: {$foundReport->item_name}
- Description: {$foundReport->report_description}
- Category: {$foundCategory}
- Location: {$foundReport->report_location}
- Date: {$foundReport->report_datetime->format('Y-m-d H:i')}

TASK:
Compare these reports considering:
1. Visual similarity (if images provided)
2. Item name and description match
3. Location proximity
4. Time proximity (lost before found)
5. Category match
6. Physical characteristics mentioned

OUTPUT FORMAT (JSON):
{
    "confidence": 85,
    "reasoning": "Both reports describe a black iPhone with similar distinctive features...",
    "similarities": [
        "Same brand and model",
        "Matching color",
        "Similar location"
    ],
    "differences": [
        "Time gap of 2 days"
    ],
    "recommendation": "STRONG_MATCH"
}

confidence: 0-100 (0=no match, 100=perfect match)
recommendation: NO_MATCH (<40), WEAK_MATCH (40-60), POSSIBLE_MATCH (60-80), STRONG_MATCH (>80)

Respond ONLY with valid JSON, no additional text.
PROMPT;
    }

    /**
     * Parse AI response JSON
     */
    private function parseAIResponse(string $response): array
    {
        // Remove markdown code blocks if present
        $response = preg_replace('/```json\s*|\s*```/', '', $response);
        $response = trim($response);

        try {
            $data = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response');
            }

            return [
                'confidence' => $data['confidence'] ?? 0,
                'reasoning' => $data['reasoning'] ?? 'No reasoning provided',
                'similarities' => $data['similarities'] ?? [],
                'differences' => $data['differences'] ?? [],
                'recommendation' => $data['recommendation'] ?? 'NO_MATCH',
            ];

        } catch (\Exception $e) {
            Log::error('AI Response Parse Error', ['response' => $response, 'error' => $e->getMessage()]);
            return [
                'confidence' => 0,
                'reasoning' => 'Failed to parse AI response',
                'similarities' => [],
                'differences' => [],
                'recommendation' => 'NO_MATCH',
            ];
        }
    }

    /**
     * Fallback matching (simple text similarity)
     */
    private function fallbackMatching(Report $lostReport, Report $foundReport): array
    {
        $score = 0;
        $similarities = [];

        // Item name similarity
        similar_text(
            strtolower($lostReport->item_name),
            strtolower($foundReport->item_name),
            $namePercent
        );
        if ($namePercent > 50) {
            $score += $namePercent * 0.4;
            $similarities[] = "Similar item names (" . round($namePercent) . "% match)";
        }

        // Category match
        if ($lostReport->category_id === $foundReport->category_id) {
            $score += 20;
            $similarities[] = "Same category";
        }

        // Description similarity
        $lostDesc = $lostReport->report_description ?? '';
        $foundDesc = $foundReport->report_description ?? '';
        
        if (!empty($lostDesc) && !empty($foundDesc)) {
            similar_text(
                strtolower($lostDesc),
                strtolower($foundDesc),
                $descPercent
            );
            if ($descPercent > 30) {
                $score += $descPercent * 0.3;
                $similarities[] = "Similar descriptions (" . round($descPercent) . "% match)";
            }
        }

        // Location proximity (simple string match)
        if (stripos($lostReport->report_location, $foundReport->report_location) !== false ||
            stripos($foundReport->report_location, $lostReport->report_location) !== false) {
            $score += 10;
            $similarities[] = "Nearby locations";
        }

        $finalScore = min(round($score), 100);
        
        return [
            'confidence' => $finalScore,
            'reasoning' => 'Fallback matching used (AI unavailable)',
            'similarities' => $similarities,
            'differences' => [],
            'recommendation' => $finalScore > 60 ? 'POSSIBLE_MATCH' : 'WEAK_MATCH',
        ];
    }

    /**
     * Get image as base64
     */
    private function getImageBase64(string $photoUrl): ?string
    {
        try {
            $path = str_replace('storage/', 'public/', $photoUrl);
            
            if (!Storage::exists($path)) {
                return null;
            }

            $imageData = Storage::get($path);
            return base64_encode($imageData);

        } catch (\Exception $e) {
            Log::error('Image Load Error', ['path' => $photoUrl, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Batch analyze multiple reports
     */
    public function batchAnalyze(array $lostReportIds): array
    {
        $results = [];

        foreach ($lostReportIds as $reportId) {
            $lostReport = Report::find($reportId);
            if ($lostReport && $lostReport->report_type === 'LOST') {
                $matches = $this->findPotentialMatches($lostReport, 3);
                
                if (!empty($matches)) {
                    $results[] = [
                        'lost_report' => $lostReport,
                        'potential_matches' => $matches,
                    ];
                }
            }
        }

        return $results;
    }
}