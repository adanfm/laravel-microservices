<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenrerRequest;
use App\Models\Genrer;
use Illuminate\Http\Request;

class GenrerController extends Controller
{
    public function index(Request $request) // ?only_trashed
    {
        if ($request->has('only_trashed')) {
            return Genrer::onlyTrashed()->get();
        }
        return Genrer::all();
    }

    public function store(GenrerRequest $request)
    {
        return Genrer::create($request->validated());
    }

    public function show(Genrer $gender)
    {
        return $gender;
    }

    public function update(GenrerRequest $request, Genrer $gender)
    {
        $gender->update($request->validated());
        return $gender;
    }

    public function destroy(Genrer $gender)
    {
        $gender->delete();
        return response()->noContent();
    }
}
