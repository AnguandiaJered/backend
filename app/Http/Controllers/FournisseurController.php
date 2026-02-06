<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Models\Fournisseur;

class FournisseurController extends Controller
{
    use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
      public function index()
    {
        $fournisseur = Fournisseur::orderBy('id','desc')->paginate(5);
        return $this->sendData($fournisseur);
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
            'name' => 'required|unique:clients,name',
            'sexe' => 'nullable|string',
            'adresse' => 'nullable|string',
            'phone' => 'nullable|string',
        ]); //
    
       try {
            $fournisseur = new Fournisseur;

            $fournisseur->name = $request->input('name');
            $fournisseur->sexe = $request->input('sexe');
            $fournisseur->adresse = $request->input('adresse');
            $fournisseur->phone = $request->input('phone');
            $fournisseur->save();

            return $this->sendResponse($fournisseur, 'Enregistrement de client réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fournisseur = Fournisseur::findOrFail($id); //
        return $this->sendData($fournisseur);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fournisseur = Fournisseur::findOrFail($id); //
        return $this->sendData($fournisseur);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>'sometimes|string',
            'sexe' => 'sometimes|string',
            'adresse' => 'sometimes|string',
            'phone' => 'sometimes|string',
        ]);
         try {
            $fournisseur = Fournisseur::findOrFail($id); //

            $fournisseur->name = $request->input('name');
            $fournisseur->sexe = $request->input('sexe');
            $fournisseur->adresse = $request->input('adresse');
            $fournisseur->phone = $request->input('phone');
            $fournisseur->save();

            return $this->sendResponse($fournisseur, 'Modification de fournisseur réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec de modification', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Fournisseur::find($id)->delete();
        return $this->sendResponse('Suppression de fournisseur réussi');
    }
}
