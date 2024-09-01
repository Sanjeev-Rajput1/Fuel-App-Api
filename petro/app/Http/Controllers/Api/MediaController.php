<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\DeliveryInfo;
use App\Models\media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    
    public function index()
    {
        $media = media::all();

      
        return response()->json([
            'status' => true,
            'data' => $media,
        ], 200);
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
        
        $validateUser = Validator::make($request->all(), [
            'image' => 'required',
        ], [
            'image.required' => 'Please select Media.',
            
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }

        $img = $request->file('image');
        $ext = $img->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;
        $img->move(public_path('/public/media'), $imageName);
      


        $mediaUrl = url('/media/' . $imageName);
        $userId = Auth::id();
        $post = Media::create([
            'name' => $imageName,
            'path' => '/media/' . $imageName,
            'url' => $mediaUrl,
            'user_id' => $userId,
        ]);

        return response()->json([
            'status' => true,
            
            'media' => $post,
        ], 200);
    }
  
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $media = media::find($id);
        if (!$media) {
            return response()->json([
                'status' => false,
                'message' => 'Media not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'path' => 'sometimes|string|max:255',
            'url' => 'sometimes|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $media->update($request->only('name', 'path', 'url'));

        return response()->json([
            'status' => true,
            'message' => 'Media updated successfully',
            'data' => $media,
        ], 200);
    }
    
    public function show(string $id)
    {
        $media = media::find($id);
        if (!$media) {
            return response()->json([
                'status' => false,
                'message' => 'Media not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'media'=> $media,
        ], 200);
    }

    
    public function destroy(string $id)
    {
        
        $media = media::find($id);
        if (!$media) {
            return response()->json([
                'status' => false,
                'message' => 'Media not found',
            ], 404);
        }
       
        $media->delete();

        return response()->json([
            'status' => true,
            'message' => 'Media deleted successfully',
        ], 200);
    }


    public function showByUrl(Request $request)
    {
        // $url = $request->query('url');

        $validateUser = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }

        $media =  media::where('url', $request->input('url'))->first();

        if ($media) {
            return response()->json([
                'status' => true,
                'media' => $media,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Image not found.',
            ], 404);
        }
    }

}

