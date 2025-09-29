<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ForumAdminController extends Controller
{
    // Liste des topics
    public function index()
    {
        $topics = Topic::latest()->get();
        return view('admin.GestionForum.topics.index', compact('topics'));
    }

    // Formulaire de création
    public function create()
    {
        return view('admin.GestionForum.topics.create');
    }

    // Sauvegarde d’un nouveau topic
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Topic::create($validated);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic créé avec succès.');
    }

    // Display the specified topic

        public function show(Topic $topic)
    {
        return view('admin.GestionForum.topics.show', compact('topic'));
    }

    // Formulaire d’édition
    public function edit(Topic $topic)
    {
        return view('admin.GestionForum.topics.edit', compact('topic'));
    }

    // Mise à jour d’un topic
    public function update(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $topic->update($validated);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic modifié avec succès.');
    }

    // Suppression
    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic supprimé avec succès.');
    }
}
