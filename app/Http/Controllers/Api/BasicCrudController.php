<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

abstract class BasicCrudController extends Controller
{
    protected abstract function model();
    protected abstract function rulesStore();
    protected abstract function rulesUpdate();

    public function index(Request $request) // ?only_trashed
    {
        if ($request->has('only_trashed')) {
            return $this->model()::onlyTrashed()->get();
        }
        return $this->model()::all();
    }

    public function store(Request $request)
    {
        $validateData = $this->validate($request, $this->rulesStore());
        $obj = $this->model()::create($validateData);
        $obj->refresh();
        return $obj;
    }

    protected function findOrFail($id)
    {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();
        return $this->model()::where($keyName, $id)->firstOrFail();
    }

    public function show($id)
    {
        $obj = $this->findOrFail($id);
        return $obj;
    }

    public function update(Request $request, $id)
    {
        $obj = $this->findOrFail($id);
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $obj->update($validatedData);
        return $obj;
    }

    public function destroy($id)
    {
        $obj = $this->findOrFail($id);
        $obj->delete();
        return response()->noContent();
    }
}
