<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->paginate(9)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        abort_unless($project->is_active, 404);

        $relatedProjects = Project::query()
            ->where('id', '!=', $project->id)
            ->where('is_active', true)
            ->limit(6)
            ->get();

        return view('projects.show', compact('project', 'relatedProjects'));
    }
}
