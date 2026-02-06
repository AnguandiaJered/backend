<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Models\Remboursement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RemboursementController extends Controller
{
      use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paiement = Remboursement::with(['client','dette','user'])->orderBy('id','desc')->paginate(5);
        return $this->sendData($paiement);
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
            'dette_id' => 'required',
            'montant' => 'required',
            'status' => 'required',
        ]);//

        try {
            $paiement = new Remboursement;

            $paiement->client_id = $request->input('client_id');
            $paiement->dette_id = $request->input('dette_id');
            $paiement->montant = $request->input('montant');
            $paiement->status = $request->input('status');
            $paiement->author_id = Auth::user()->id;
            $paiement->save();

            return $this->sendResponse($paiement, 'Enregistrement de paiement réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paiement = Remboursement::findOrFail($id); //
        return $this->sendData($paiement);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paiement = Remboursement::findOrFail($id); //
        return $this->sendData($paiement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'client_id' => 'sometimes|integer',
            'dette_id' => 'sometimes|integer',
            'montant' => 'sometimes|integer',
            'status' => 'sometimes|string',
        ]);//
        try {
            $paiement = Remboursement::findOrFail($id); //

            $paiement->client_id = $request->input('client_id');
            $paiement->dette_id = $request->input('dette_id');
            $paiement->montant = $request->input('montant');
            $paiement->status = $request->input('status');
            $paiement->author_id = Auth::user()->id;
            $paiement->save();

            return $this->sendResponse($paiement, 'Modification de paiement réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec de modification', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Remboursement::find($id)->delete();
        return $this->sendResponse('Suppression de paiement réussi');
    }
}
