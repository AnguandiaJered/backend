<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Models\categorie;

class CategoryController extends Controller
{
    use JsonResponseTrait;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorie = Categorie::orderBy('id','desc')->paginate(5);
        return $this->sendData($categorie);
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
        $request->validate([
            'name' => 'required'
        ]);

        try {
            $categorie = new Categorie;

            $categorie->name = $request->input('name');
            $categorie->save();

            return $this->sendResponse($categorie, 'Enregistrement de categorie réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categorie = Categorie::findOrFail($id);
        return $this->sendData($categorie);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categorie = Categorie::findOrFail($id);
        return $this->sendData($categorie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        try {
            $categorie = Categorie::find($id);
            $categorie->name = $request->input('name');
            $categorie->save();

            return $this->sendResponse($categorie, 'Modification de categorie réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec de modification');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Categorie::find($id)->delete();
        return $this->sendResponse('Suppression de categorie réussi');
    }
}
