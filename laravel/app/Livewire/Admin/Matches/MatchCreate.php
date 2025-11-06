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

    // Queries for search bars
    public $lostQuery = '';
    public $foundQuery = '';

    protected $rules = [
        'lostReportId'  => 'required|exists:reports,report_id',
        'foundReportId' => 'required|exists:reports,report_id|different:lostReportId',
        'matchNotes'    => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'lostReportId.required'   => 'Please select a lost item report',
        'foundReportId.required'  => 'Please select a found item report',
        'foundReportId.different' => 'Lost and Found reports must be different',
    ];

    public function mount()
    {
        // no-op: lists are computed on the fly via getters below
    }

    /** --------- Computed options (filtered) ---------- */
    public function getLostOptionsProperty()
    {
        $q = trim($this->lostQuery);

        $query = Report::with('category')
            ->where('report_type', 'LOST')
            ->whereIn('report_status', ['OPEN', 'STORED'])
            ->whereDoesntHave('matchesAsLost', function ($query) {
                $query->where('match_status', 'CONFIRMED');
            });

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $like = '%' . $q . '%';
                $sub->where('report_id', 'like', $like)
                    ->orWhere('report_number', 'like', $like)
                    ->orWhere('item_name', 'like', $like)
                    ->orWhere('report_location', 'like', $like)
                    // cari tanggal human readable, mis: 12 Nov 2025
                    ->orWhereRaw("DATE_FORMAT(report_datetime, '%d %b %Y %H:%i') like ?", [$like])
                    ->orWhereRaw("DATE_FORMAT(report_datetime, '%d %b %Y') like ?", [$like]);
            });
        }

        return $query->orderBy('report_number', 'desc')->limit(20)->get();
    }

    public function getFoundOptionsProperty()
    {
        $q = trim($this->foundQuery);

        $query = Report::with('category')
            ->where('report_type', 'FOUND')
            ->whereIn('report_status', ['OPEN', 'STORED'])
            ->whereDoesntHave('matchesAsFound', function ($query) {
                $query->where('match_status', 'CONFIRMED');
            });

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $like = '%' . $q . '%';
                $sub->where('report_id', 'like', $like)
                    ->orWhere('report_number', 'like', $like)
                    ->orWhere('item_name', 'like', $like)
                    ->orWhere('report_location', 'like', $like)
                    ->orWhereRaw("DATE_FORMAT(report_datetime, '%d %b %Y %H:%i') like ?", [$like])
                    ->orWhereRaw("DATE_FORMAT(report_datetime, '%d %b %Y') like ?", [$like]);
            });
        }

        return $query->orderBy('report_number', 'desc')->limit(20)->get();
    }

    /** --------- Select handlers from dropdown ---------- */
    public function selectLost(string $reportId)
    {
        $this->lostReportId = $reportId;
    }

    public function selectFound(string $reportId)
    {
        $this->foundReportId = $reportId;
    }

    public function createMatch()
    {
        $this->validate();

        // prevent duplicate
        $exists = MatchedItem::where('lost_report_id', $this->lostReportId)
            ->where('found_report_id', $this->foundReportId)
            ->exists();

        if ($exists) {
            $this->addError('foundReportId', 'A match between these reports already exists!');
            return;
        }

        MatchedItem::create([
            'match_id'        => Str::uuid(),
            'lost_report_id'  => $this->lostReportId,
            'found_report_id' => $this->foundReportId,
            'match_status'    => 'PENDING',
            'confidence_score'=> null,
            'match_notes'     => $this->matchNotes,
            'matched_by'      => auth()->id(),
            'matched_at'      => now(),
        ]);

        session()->flash('success', 'Match created successfully!');

        // reset form
        $this->reset(['lostReportId', 'foundReportId', 'matchNotes', 'lostQuery', 'foundQuery']);

        // notify parent if needed
        $this->dispatch('match-created');
    }

    public function render()
    {
        return view('livewire.admin.matches.match-create');
    }
}
