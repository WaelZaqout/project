<?php

namespace App\Services\Project;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateProjectStatusService
{
    public function execute(Project $project, string $newStatus): Project
    {
        if ($project->status === $newStatus) {
            return $project;
        }

        if (! $project->canChangeStatus($newStatus)) {
            throw ValidationException::withMessages([
                'status' => 'Invalid project status transition.',
            ]);
        }

        return DB::transaction(function () use ($project, $newStatus) {

            foreach (Project::stageResetMap()[$newStatus] ?? [] as $column) {
                $project->{$column} = null;
            }

            $project->status = $newStatus;

            match ($newStatus) {
                Project::STATUS_PENDING   => $project->reviewed_at ??= now(),
                Project::STATUS_APPROVED  => $project->pre_approved_at ??= now(),
                Project::STATUS_FUNDING   => $project->open_for_investment_at ??= now(),
                Project::STATUS_ACTIVE    => $project->funded_at ??= now(),
                Project::STATUS_COMPLETED => $project->repayment_started_at ??= now(),
                default => null,
            };

            $project->save();

            return $project;
        });
    }
}
