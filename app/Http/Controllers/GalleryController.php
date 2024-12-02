<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class GalleryController extends Controller
{

    // Method untuk API Gallery
    /**
     * API Endpoint: Menampilkan data gallery dalam format JSON.
     *
     * @OA\Get(
     *     path="/api/gallery",
     *     tags={"Gallery"},
     *     summary="Retrieve gallery data",
     *     description="Get all gallery posts with images.",
     *     operationId="getGallery",
     *     @OA\Response(
     *         response=200,
     *         description="List of galleries retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="picture_url", type="string", format="url"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function apiIndex()
    {
        $galleries = Post::where('picture', '!=', '')
                         ->whereNotNull('picture')
                         ->orderBy('created_at', 'desc')
                         ->get()
                         ->map(function ($post) {
                             return [
                                 'id' => $post->id,
                                 'title' => $post->title,
                                 'description' => $post->description,
                                 'picture_url' => url('storage/posts_image/' . $post->picture),
                                 'created_at' => $post->created_at,
                                 'updated_at' => $post->updated_at,
                             ];
                         });

        return response()->json($galleries, 200);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'id' => "posts",
            'menu' => "Gallery",
            'galleries' => Post::where('picture', '!=', '')
                            ->whereNotNull('picture')
                            ->orderBy('created_at', 'desc')
                            ->paginate(30)
        );
        return view('gallery.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);
    
        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
            $filenameSimpan = "{$basename}.{$extension}";
            $request->file('picture')->storeAs('posts_image', $filenameSimpan);
        } else {
            $filenameSimpan = 'noimage.png';
        }
    
        $post = new Post;
        $post->picture = $filenameSimpan;
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();
    
        return redirect('gallery')->with('success', 'Berhasil menambahkan data baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // This method can be implemented as needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery = Post::findOrFail($id); // Mencari galeri berdasarkan ID
        return view('gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $gallery = Post::findOrFail($id); // Mencari galeri berdasarkan ID
        $gallery->title = $request->input('title');
        $gallery->description = $request->input('description');

        // Jika ada file gambar baru yang diunggah, simpan dan ganti gambar yang lama
        if ($request->hasFile('picture')) {
            // Menghapus gambar lama
            if ($gallery->picture && file_exists(storage_path('app/public/posts_image/' . $gallery->picture))) {
                unlink(storage_path('app/public/posts_image/' . $gallery->picture));
            }

            // Menyimpan gambar baru
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
            $filenameSimpan = "{$basename}.{$extension}";
            $request->file('picture')->storeAs('posts_image', $filenameSimpan);

            $gallery->picture = $filenameSimpan;
        }

        $gallery->save();

        return redirect()->route('gallery.index')->with('success', 'Image updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Post::findOrFail($id);

        // Menghapus gambar dari storage jika ada
        if ($gallery->picture && file_exists(storage_path('app/public/posts_image/' . $gallery->picture))) {
            unlink(storage_path('app/public/posts_image/' . $gallery->picture));
        }

        $gallery->delete();

        return redirect()->route('gallery.index')->with('success', 'Image deleted successfully');
    }
}
