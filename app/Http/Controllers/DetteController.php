<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Dette;
use App\Models\User;
use DB;

class DetteController extends Controller
{
      use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dette = Dette::with(['product','remboursement','client','user'])->orderBy('id','desc')->paginate(5);
        return $this->sendData($dette);
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
            'client_id' => 'required',
            'produit' => 'required',
            'quantite' => 'required',
            'montant' => 'required',
        ]); //

        try {

            $dette = new Dette;

            $dette->client_id = $request->input('client_id');
            $dette->product = $request->input('produit');
            $dette->quantity = $request->input('quantite');
            $dette->montant = $request->input('montant');
            $dette->author_id = Auth::user()->id;
            $dette->save();

            return $this->sendResponse($client, 'Enregistrement de dette réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dette = Dette::findOrFail($id); //
        return $this->sendData($dette);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dette = Dette::findOrFail($id); //
        return $this->sendData($dette);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'client_id' => 'sometimes|integer',
            'product' => 'sometimes|string',
            'quantity' => 'sometimes|string',
            'montant' => 'sometimes|integer',
            'status' => 'sometimes|string',
        ]); //

         try {

            $dette = Dette::findOrFail($id); //

            $dette->client_id = $request->input('client_id');
            $dette->product = $request->input('produit');
            $dette->quantity = $request->input('quantite');
            $dette->montant = $request->input('montant');
            $dette->author_id = Auth::user()->id;
            $dette->save();

            return $this->sendResponse($client, 'Modification de dette réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec de modification', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Dette::find($id)->delete();
        return $this->sendResponse('Suppression de dette réussi');
    }
}
