<?php

namespace App\Livewire\Admin\Matches;

use App\Models\Report;
use App\Models\MatchedItem;
use App\Services\AIMatchingService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AiMatchSuggestion extends Component
{
    use WithPagination;

    public $selectedLostReportId = null;
    public $aiSuggestions = [];
    public $isAnalyzing = false;
    public $showBatchAnalysis = false;
    public $batchResults = [];

    protected $aiService;

    public function boot(AIMatchingService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function mount()
    {
        // Load first lost report by default
        $companyId = auth()->user()->company_id;
        $firstLostReport = Report::where('company_id', $companyId)
            ->where('report_type', 'LOST')
            ->whereIn('report_status', ['OPEN', 'STORED'])
            ->first();

        if ($firstLostReport) {
            $this->selectedLostReportId = $firstLostReport->report_id;
        }
    }

    public function selectLostReport($reportId)
    {
        $this->selectedLostReportId = $reportId;
        $this->aiSuggestions = [];
    }

    public function analyzeMatches()
    {
        if (!$this->selectedLostReportId) {
            session()->flash('error', 'Please select a lost report first');
            return;
        }

        $this->isAnalyzing = true;
        $this->aiSuggestions = [];

        try {
            $lostReport = Report::findOrFail($this->selectedLostReportId);
            $this->aiSuggestions = $this->aiService->findPotentialMatches($lostReport, 5);

            if (empty($this->aiSuggestions)) {
                session()->flash('info', 'No potential matches found for this report');
            } else {
                session()->flash('success', 'AI analysis complete! Found ' . count($this->aiSuggestions) . ' potential matches');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'AI analysis failed: ' . $e->getMessage());
        } finally {
            $this->isAnalyzing = false;
        }
    }

    public function createMatchFromSuggestion($foundReportId, $aiData)
    {
        try {
            // Decode if string
            if (is_string($aiData)) {
                $aiData = json_decode($aiData, true);
            }

            // Get lost_report_id dari selected atau dari aiData
            $lostReportId = $this->selectedLostReportId;
            
            // Jika tidak ada selected (dari batch), cari dari context
            if (!$lostReportId && isset($aiData['lost_report_id'])) {
                $lostReportId = $aiData['lost_report_id'];
            }

            if (!$lostReportId) {
                session()->flash('error', 'Lost report ID not found!');
                return;
            }

            // Check if match already exists
            $existingMatch = MatchedItem::where('lost_report_id', $lostReportId)
                ->where('found_report_id', $foundReportId)
                ->first();

            if ($existingMatch) {
                session()->flash('error', 'Match already exists!');
                return;
            }

            // Create match with AI data
            $similarities = isset($aiData['similarities']) && is_array($aiData['similarities']) 
                ? implode("\n- ", $aiData['similarities']) 
                : '';

            MatchedItem::create([
                'match_id' => Str::uuid(),
                'company_id' => auth()->user()->company_id,
                'lost_report_id' => $lostReportId,
                'found_report_id' => $foundReportId,
                'match_status' => 'PENDING',
                'confidence_score' => $aiData['confidence'] ?? 0,
                'match_notes' => "AI Suggested Match\n\n" .
                    "Confidence: " . ($aiData['confidence'] ?? 0) . "%\n" .
                    "Reasoning: " . ($aiData['reasoning'] ?? 'N/A') . "\n\n" .
                    "Similarities:\n- " . $similarities,
                'matched_by' => auth()->id(),
                'matched_at' => now(),
            ]);

            session()->flash('success', 'Match created successfully from AI suggestion!');
            $this->dispatch('match-created');
            
            // Refresh suggestions if single analysis
            if ($this->selectedLostReportId) {
                $this->analyzeMatches();
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create match: ' . $e->getMessage());
        }
    }

    public function runBatchAnalysis()
    {
        $this->showBatchAnalysis = true;
        $this->batchResults = [];

        try {
            $companyId = auth()->user()->company_id;

            // Get all unmatched lost reports
            $lostReports = Report::where('company_id', $companyId)
                ->where('report_type', 'LOST')
                ->whereIn('report_status', ['OPEN', 'STORED'])
                ->whereDoesntHave('matchesAsLost', function($query) {
                    $query->where('match_status', 'CONFIRMED');
                })
                ->pluck('report_id')
                ->take(10) // Limit untuk avoid timeout
                ->toArray();

            if (empty($lostReports)) {
                session()->flash('info', 'No lost reports available for batch analysis');
                return;
            }

            $this->batchResults = $this->aiService->batchAnalyze($lostReports);

            session()->flash('success', 'Batch analysis complete! Analyzed ' . count($lostReports) . ' lost reports');

        } catch (\Exception $e) {
            session()->flash('error', 'Batch analysis failed: ' . $e->getMessage());
        }
    }

    public function closeBatchResults()
    {
        $this->batchResults = [];
        $this->showBatchAnalysis = false;
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        $lostReports = Report::with('category')
            ->where('company_id', $companyId)
            ->where('report_type', 'LOST')
            ->whereIn('report_status', ['OPEN', 'STORED'])
            ->whereDoesntHave('matchesAsLost', function($query) {
                $query->where('match_status', 'CONFIRMED');
            })
            ->orderBy('report_datetime', 'desc')
            ->paginate(10);

        // Fix untuk selected report
        $selectedReport = null;
        if ($this->selectedLostReportId) {
            $selectedReport = Report::where('company_id', $companyId)
                ->with('category')
                ->find($this->selectedLostReportId);
        }

        return view('livewire.admin.matches.ai-match-suggestion', [
            'lostReports' => $lostReports,
            'selectedReport' => $selectedReport,
        ])->layout('components.layouts.admin', [
            'title' => 'AI Match Suggestions',
            'pageTitle' => 'AI-Powered Match Suggestions',
            'pageDescription' => 'Let AI help you find potential matches'
        ]);
    }
}