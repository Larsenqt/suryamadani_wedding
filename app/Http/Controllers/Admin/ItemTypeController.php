<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    public function index()
    {
        $types = ItemType::latest()->paginate(10);
        return view('admin.item_types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.item_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:item_types,name'
        ]);

        ItemType::create($request->all());

        return redirect()->route('admin.item-types.index')
            ->with('success', 'Jenis item berhasil ditambahkan');
    }

    public function edit($id)
    {
        $type = ItemType::findOrFail($id);
        return view('admin.item_types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $type = ItemType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:item_types,name,' . $id
        ]);

        $type->update($request->all());

        return redirect()->route('admin.item-types.index')
            ->with('success', 'Jenis item berhasil diupdate');
    }

    public function destroy($id)
    {
        $type = ItemType::findOrFail($id);
        
        // Cek apakah jenis item masih memiliki item terkait
        if ($type->items()->count() > 0) {
            return back()->with('error', 'Jenis item tidak dapat dihapus karena masih memiliki item terkait!');
        }
        
        $type->delete();

        return back()->with('success', 'Jenis item berhasil dihapus');
    }
}