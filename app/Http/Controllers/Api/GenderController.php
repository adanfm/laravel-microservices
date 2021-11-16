<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenderRequest;
use App\Models\Gender;
use Illuminate\Http\Request;

class GenderController extends Controller
{
    public function index(Request $request) // ?only_trashed
    {
        if ($request->has('only_trashed')) {
            return Gender::onlyTrashed()->get();
        }
        return Gender::all();
    }

    public function store(GenderRequest $request)
    {
        return Gender::create($request->validated());
    }

    public function show(Gender $gender)
    {
        return $gender;
    }

    public function update(GenderRequest $request, Gender $gender)
    {
        $gender->update($request->validated());
        return $gender;
    }

    public function destroy(Gender $gender)
    {
        $gender->delete();
        return response()->noContent();
    }
}
