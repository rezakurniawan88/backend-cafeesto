<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::latest()->get();
        return response()->json(['menus' => $menus]);
    }

    /**
     * Display a listing of the resource for dashboard.
     */
    public function indexDashboard()
    {
        $menus = Menu::latest()->paginate(5);
        return response()->json(['menus' => $menus]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "description" => "required|string",
            "price" => "required|integer",
            "image" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
            "category" => "required|string",
            "stock" => "required|integer"
        ]);

        $image = $request->file("image")->store('assets/menu', 'public');
        $imageUrl = Storage::url($image);

        $menus = Menu::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'image' => $imageUrl,
            'category' => $validated['category'],
            'stock' => $validated['stock'],
        ]);

        return response()->json(['menus' => $menus], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return response()->json(["menu" => $menu]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        // dd($request->all());
        $validated = $request->validate([
            "name" => "string",
            "description" => "string",
            "price" => "integer",
            "image" => "image|mimes:jpeg,png,jpg,gif|max:2048",
            "category" => "string",
            "stock" => "integer"
        ]);
        // Log::info('Validated Data:', $validated);

        if($request->hasFile("image")) {
            // Delete old image
            if($menu->image) {
                Storage::delete(str_replace("/storage", "public", $menu->image));
            }

            // Store image
            $image = $request->file("image")->store('assets/menu', 'public');
            $imageUrl = Storage::url($image);

            $menu->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'image' => $imageUrl,
                'category' => $validated['category'],
                'stock' => $validated['stock'],
            ]);
        } else {
            $result = $menu->update($validated);
        }

        return response()->json(['message' => 'Menu updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        if($menu->image) {
            $imagePath = str_replace("/storage", "public", $menu->image);
            Storage::delete($imagePath);
        }
        $menu->delete();

        return response()->json(['message' => 'Menu deleted successfully']);
    }
}
