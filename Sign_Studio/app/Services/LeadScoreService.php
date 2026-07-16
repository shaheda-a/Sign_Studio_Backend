<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadScore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadScoreService
{
    /**
     * Get criteria-based scores.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getScores(array $filters = [], int $perPage = 15): mixed
    {
        $query = LeadScore::query()->with(['lead', 'scoringUser']);

        if (!empty($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'desc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create criteria score and update cumulative lead score.
     *
     * @param array $data
     * @return LeadScore
     */
    public function createScore(array $data): LeadScore
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = Auth::id();
            if (empty($data['scored_by'])) {
                $data['scored_by'] = Auth::id();
            }

            $score = LeadScore::create($data);

            $lead = Lead::findOrFail($score->lead_id);
            $totalScore = LeadScore::where('lead_id', $lead->id)->sum('score');
            $lead->update(['lead_score' => $totalScore]);

            return $score;
        });
    }

    /**
     * Get score by ID.
     *
     * @param int $id
     * @return LeadScore
     */
    public function getScoreById(int $id): LeadScore
    {
        return LeadScore::with(['lead', 'scoringUser'])->findOrFail($id);
    }

    /**
     * Delete score criteria and recalculate lead score.
     *
     * @param int $id
     * @return bool
     */
    public function deleteScore(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $score = LeadScore::findOrFail($id);
            $leadId = $score->lead_id;
            $score->delete();

            $lead = Lead::findOrFail($leadId);
            $totalScore = LeadScore::where('lead_id', $lead->id)->sum('score');
            $lead->update(['lead_score' => $totalScore]);

            return true;
        });
    }
}
