<?php

namespace App\Services\Project;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ProjectService
{
    public function create(array $data, int $borrowerId): Project
    {
        return DB::transaction(function () use ($data, $borrowerId) {

            $data['borrower_id'] = $borrowerId;
            $data['status'] ??= 'pending';
            $data['slug'] = $this->generateSlug($data['title']);

            $image   = $data['image']   ?? null;
            $gallery = $data['gallery'] ?? [];

            unset($data['image'], $data['gallery']);

            $project = Project::create($data);

            if ($image instanceof UploadedFile) {
                $project->update([
                    'image' => $image->store('projects', 'public')
                ]);
            }

            foreach ($gallery as $file) {
                $project->images()->create([
                    'image' => $file->store('projects/gallery', 'public')
                ]);
            }

            Log::info('Project created', [
                'project_id' => $project->id,
                'borrower_id' => $borrowerId,
            ]);

            return $project;
        });
    }

    public function update(Project $project, array $data): Project
    {
        return DB::transaction(function () use ($project, $data) {

            if (isset($data['title']) && $data['title'] !== $project->title) {
                $data['slug'] = $this->generateSlug($data['title'], $project->id);
            }

            $image = $data['image'] ?? null;
            unset($data['image']);

            $project->update($data);

            if ($image instanceof UploadedFile) {
                $project->images()->delete();

                $project->images()->create([
                    'image' => $image->store('projects', 'public')
                ]);
            }

            return $project;
        });
    }

    private function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);

        $query = Project::where('slug', 'like', "{$slug}%");

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $count = $query->count();

        return $count ? "{$slug}-" . ($count + 1) : $slug;
    }
}
