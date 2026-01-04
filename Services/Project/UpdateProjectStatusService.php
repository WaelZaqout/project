<?php

namespace App\Services\Project;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateProjectStatusService
{
    public function execute(Project $project, string $newStatus): Project
    {
        if (! $project->canChangeStatus($newStatus)) {
            throw ValidationException::withMessages([
                'status' => __('Invalid project status transition.'),
            ]);
        }

        return DB::transaction(function () use ($project, $newStatus) {

            $this->resetFutureStages($project, $newStatus);

            $project->status = $newStatus;
            $this->setStageTimestamp($project, $newStatus);

            $project->save();

            return $project;
        });
    }

    private function resetFutureStages(Project $project, string $status): void
    {
        $map = [
            'draft' => [
                'reviewed_at',
                'pre_approved_at',
                'open_for_investment_at',
                'funded_at',
                'repayment_started_at',
            ],
            'pending' => [
                'pre_approved_at',
                'open_for_investment_at',
                'funded_at',
                'repayment_started_at',
            ],
            'approved' => [
                'open_for_investment_at',
                'funded_at',
                'repayment_started_at',
            ],
            'funding' => [
                'funded_at',
                'repayment_started_at',
            ],
            'active' => [
                'repayment_started_at',
            ],
        ];

        foreach ($map[$status] ?? [] as $column) {
            $project->{$column} = null;
        }
    }

    private function setStageTimestamp(Project $project, string $status): void
    {
        match ($status) {
            'pending'   => $project->reviewed_at ??= now(),
            'approved'  => $project->pre_approved_at ??= now(),
            'funding'   => $project->open_for_investment_at ??= now(),
            'active'    => $project->funded_at ??= now(),
            'completed' => $project->repayment_started_at ??= now(),
            default     => null,
        };
    }
}
