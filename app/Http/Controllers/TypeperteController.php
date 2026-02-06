<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Models\Type_perte;
use DB;

class TypeperteController extends Controller
{
    use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typegas = Type_perte::orderBy('id','desc')->paginate(5);
        return $this->sendData($typegas);
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
            $typegas = new Type_perte;
            $typegas->name = $request->input('name');
            $typegas->save();

            return $this->sendResponse($typegas, 'Enregistrement de type perte réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $typegas = Type_perte::findOrFail($id);
        return $this->sendData($typegas);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $typegas = Type_perte::findOrFail($id);
        return $this->sendData($typegas);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>'sometimes|string',
        ]); //

        try {
            $typegas = Type_perte::findOrFail($id);

            $typegas->name = $request->input('name');
            $typegas->save();

            return $this->sendResponse($typegas, 'Modification de type perte réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec de modification', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Type_perte::find($id)->delete();
        return $this->sendResponse('Suppression de type perte réussi');
    }
}
