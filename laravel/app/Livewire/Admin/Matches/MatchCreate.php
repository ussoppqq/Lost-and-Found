<?php

namespace App\Livewire\Admin\Matches;

use App\Models\MatchedItem;
use App\Models\Report;
use Livewire\Component;
use Illuminate\Support\Str;

class MatchCreate extends Component
{
    public $lostReportId = '';
    public $foundReportId = '';
    public $matchNotes = '';
    
    public $lostReports = [];
    public $foundReports = [];

    protected $rules = [
        'lostReportId' => 'required|exists:reports,report_id',
        'foundReportId' => 'required|exists:reports,report_id|different:lostReportId',
        'matchNotes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'lostReportId.required' => 'Please select a lost item report',
        'foundReportId.required' => 'Please select a found item report',
        'foundReportId.different' => 'Lost and Found reports must be different',
    ];

    public function mount()
    {
        $this->loadReports();
    }

    public function loadReports()
    {
        // Load LOST reports that haven't been matched with eager loading category
        $this->lostReports = Report::with('category')
            ->where('report_type', 'LOST')
            ->whereIn('report_status', ['OPEN', 'STORED'])
            ->whereDoesntHave('matchesAsLost', function($query) {
                $query->where('match_status', 'CONFIRMED');
            })
            ->orderBy('report_datetime', 'desc')
            ->get();

        // Load FOUND reports that haven't been matched with eager loading category
        $this->foundReports = Report::with('category')
            ->where('report_type', 'FOUND')
            ->whereIn('report_status', ['OPEN', 'STORED'])
            ->whereDoesntHave('matchesAsFound', function($query) {
                $query->where('match_status', 'CONFIRMED');
            })
            ->orderBy('report_datetime', 'desc')
            ->get();
    }

    public function createMatch()
    {
        $this->validate();

        try {
            // Check if match already exists
            $existingMatch = MatchedItem::where('lost_report_id', $this->lostReportId)
                ->where('found_report_id', $this->foundReportId)
                ->first();

            if ($existingMatch) {
                $this->addError('foundReportId', 'A match between these reports already exists!');
                return;
            }

            MatchedItem::create([
                'match_id' => Str::uuid(),
                'lost_report_id' => $this->lostReportId,
                'found_report_id' => $this->foundReportId,
                'match_status' => 'PENDING',
                'confidence_score' => null,
                'match_notes' => $this->matchNotes,
                'matched_by' => auth()->id(),
                'matched_at' => now(),
            ]);

            session()->flash('success', 'Match created successfully!');
            
            // Dispatch event to parent to close modal and refresh
            $this->dispatch('match-created');
            
            // Reset form
            $this->reset(['lostReportId', 'foundReportId', 'matchNotes']);
            $this->loadReports();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create match: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.matches.match-create');
    }
}