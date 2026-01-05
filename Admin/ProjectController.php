<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Project\ProjectService;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\UpdateProjectStatusRequest;
use App\Services\Project\UpdateProjectStatusService;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::with('borrower')
            ->search($request->q)
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'rows' => view('admin.projects._rows', compact('projects'))->render(),
                'pagination' => $projects->links()->toHtml(),
            ]);
        }

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    public function store(StoreProjectRequest $request, ProjectService $service)
    {
        $service->create(
            $request->validated(),
            Auth::id()
        );

        return redirect()
            ->route('dashboard')
            ->with('success', __('Project submitted successfully.'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('admin.projects.index', compact('project'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project, ProjectService $service)
    {
        $service->update($project, $request->validated());

        return redirect()
            ->route('dashboard')
            ->with('success', __('Project updated successfully.'));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->deleteImages(); // حذف كل الصور
        $project->delete();

        return redirect()->route('projects.index')->with('toast', [
            'type' => 'success',
            'message' => 'تم حذف المشروع بنجاح'
        ]);
    }


    public function updateStatus(UpdateProjectStatusRequest $request, Project $project, UpdateProjectStatusService $service)
    {
        $service->execute(
            $project,
            $request->validated('status')
        );

        return back()->with('success', __('Project status updated successfully.'));
    }
}
