<?php

namespace Bites\Identity\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IdentityController extends Controller
{
    public function saveFace(Request $request)
    {
        $request->validate(['descriptor' => 'required|array']);
        auth()->user()->auth->update(['face_descriptor' => json_encode($request->descriptor)]);
        return response()->json(['success' => true]);
    }
}