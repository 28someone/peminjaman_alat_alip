<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ActivityLogger;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ToolController extends Controller
{
    public function index(Request $request): View
    {
        $tools = Tool::with('category')
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q');
                $query->where(function ($innerQuery) use ($q) {
                    $innerQuery->where('name', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tools.index', compact('tools'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.tools.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);
        $validated['available_stock'] = $validated['total_stock'];

        Tool::create($validated);
        ActivityLogger::log('admin.tool.create', 'Admin menambah alat baru.');

        return redirect()->route('admin.tools.index')->with('success', 'Data alat berhasil ditambahkan.');
    }

    public function edit(Tool $tool): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, Tool $tool): RedirectResponse
    {
        $validated = $this->validateData($request, $tool);

        $borrowedStock = $tool->total_stock - $tool->available_stock;
        if ($validated['total_stock'] < $borrowedStock) {
            return back()->withErrors([
                'total_stock' => 'Total stok tidak boleh kurang dari stok yang sedang dipinjam.',
            ])->withInput();
        }

        $validated['available_stock'] = $validated['total_stock'] - $borrowedStock;
        $tool->update($validated);

        ActivityLogger::log('admin.tool.update', "Admin mengubah alat ID {$tool->id}.");

        return redirect()->route('admin.tools.index')->with('success', 'Data alat berhasil diperbarui.');
    }

    public function destroy(Tool $tool): RedirectResponse
    {
        if ($tool->loans()->exists()) {
            return back()->with('error', 'Alat tidak bisa dihapus karena memiliki riwayat peminjaman.');
        }

        $tool->delete();
        ActivityLogger::log('admin.tool.delete', "Admin menghapus alat ID {$tool->id}.");

        return redirect()->route('admin.tools.index')->with('success', 'Data alat berhasil dihapus.');
    }

    private function validateData(Request $request, ?Tool $tool = null): array
    {
        return $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tools', 'code')->ignore($tool?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'total_stock' => ['required', 'integer', 'min:0'],
            'condition' => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'description' => ['nullable', 'string'],
        ]);
    }
}
