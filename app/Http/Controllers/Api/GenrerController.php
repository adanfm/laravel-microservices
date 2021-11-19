<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenrerRequest;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenrerController extends Controller
{
    public function index(Request $request) // ?only_trashed
    {
        if ($request->has('only_trashed')) {
            return Genre::onlyTrashed()->get();
        }
        return Genre::all();
    }

    public function store(GenrerRequest $request)
    {
        return Genre::create($request->validated());
    }

    public function show(Genre $gender)
    {
        return $gender;
    }

    public function update(GenrerRequest $request, Genre $gender)
    {
        $gender->update($request->validated());
        return $gender;
    }

    public function destroy(Genre $gender)
    {
        $gender->delete();
        return response()->noContent();
    }
}
