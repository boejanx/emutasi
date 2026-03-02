<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemplateSk;
use Illuminate\Support\Facades\Storage;

class TemplateSkController extends Controller
{
    public function index()
    {
        $templates = TemplateSk::all();
        return view('admin.template_sk.index', compact('templates'));
    }

    public function create()
    {
        // For modal, maybe we don't need create
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'file_template' => 'required|file|mimes:docx',
            'is_active' => 'boolean'
        ]);

        $path = $request->file('file_template')->store('template_sks', 'public');

        // Set others to inactive if this is active and we want only one active (optional)
        // For now, let's keep it simple. If checking, we can do it here.

        TemplateSk::create([
            'nama_template' => $request->nama_template,
            'file_path' => $path,
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);

        return redirect()->route('admin.template-sk.index')->with('success', 'Template SK berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        // Modal edit
    }

    public function update(Request $request, string $id)
    {
        $template = TemplateSk::findOrFail($id);

        $request->validate([
            'nama_template' => 'required|string|max:255',
            'file_template' => 'nullable|file|mimes:docx',
        ]);

        $data = [
            'nama_template' => $request->nama_template,
            'is_active' => $request->has('is_active') ? 1 : 0
        ];

        if ($request->hasFile('file_template')) {
            if (Storage::disk('public')->exists($template->file_path)) {
                Storage::disk('public')->delete($template->file_path);
            }
            $data['file_path'] = $request->file('file_template')->store('template_sks', 'public');
        }

        $template->update($data);

        return redirect()->route('admin.template-sk.index')->with('success', 'Template SK berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $template = TemplateSk::findOrFail($id);
        if (Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }
        $template->delete();

        return redirect()->route('admin.template-sk.index')->with('success', 'Template SK berhasil dihapus.');
    }
}
