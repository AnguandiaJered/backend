<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approvisionnement;
use App\Traits\JsonResponseTrait;
use App\Models\Produit;
use App\Models\User;
use Auth;
use DB;

class ApprovisionController extends Controller
{
    use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $approvise = Approvisionnement::with(['product','user'])->orderBy('id','desc')->paginate(5);
        return $this->sendData($approvise);
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
            'produit_id' => 'required',
            'quantite' => 'required',
            'prix' => 'required',
            'fournisseur_id' => 'required',
        ]);

        try {
           
            DB::transaction(function () use ($request) {
            $approvise = new Approvisionnement; 

            $approvise->produit_id = $request->input('produit_id');
            $approvise->quantite = $request->input('quantite');
            $approvise->prix = $request->input('prix');
            $approvise->fournisseur_id = $request->input('fournisseur_id');
            $approvise->author_id = Auth::user()->id;
            $approvise->save();

            // Mettre à jour le stock du produit

            $product = Produit::findOrFail($request->produit_id);
            $product->increment('quantite', $request->quantite);

        });
            return $this->sendResponse($approvise, 'Enregistrement de l\'approvisionnement réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $approvise = Approvisionnement::findOrFail($id); //
        return $this->sendData($approvise);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $approvise = Approvisionnement::findOrFail($id); //
        return $this->sendData($approvise);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'produit_id' => 'sometimes|integer|required_with:quantity',
            'quantite' => 'sometimes|integer|min:1',
            'prix' => 'sometimes|numeric|min:0',
            'fournisseur_id' => 'sometimes|string'
        ]);

        try {
         DB::transaction(function () use ($request, $id) {
            // Récupérer l'ancien approvisionnement
            $oldSupply = DB::table('approvisionnements')->find($id);

            if ($oldSupply) {
                // Décrémenter l'ancienne quantité du produit
                if ($oldSupply->produit_id && $oldSupply->quantite) {
                    DB::table('produits')
                        ->where('id', $oldSupply->produit_id)
                        ->decrement('quantite', $oldSupply->quantite);
                }

                // Mettre à jour l'approvisionnement
                // DB::update(
                //     "UPDATE approvisionnements SET produit_id = COALESCE(NULLIF(?, 0), produit_id), quantite = COALESCE(NULLIF(?, 0), quantite),
                //     prix = COALESCE(NULLIF(?, 0), prix), fournisseur_id = ?, author_id = ? WHERE id = ?",
                //     [$request->produit_id,$request->quantite,$request->prix,$request->fournisseur_id,Auth::user()->id,$id]
                // );

                $approvise = Approvisionnement::findOrFail($id);

                $approvise->produit_id = $request->input('produit_id');
                $approvise->quantite = $request->input('quantite');
                $approvise->prix = $request->input('prix');
                $approvise->fournisseur_id = $request->input('fournisseur_id');
                $approvise->author_id = Auth::user()->id;
                $approvise->save();

                // Récupérer le nouvel approvisionnement pour avoir les valeurs mises à jour
                $newSupply = DB::table('approvisionnements')->find($id);

                // Incrémenter la nouvelle quantité du produit
                if ($newSupply->produit_id && $newSupply->quantite) {
                    DB::table('produits')
                        ->where('id', $newSupply->produit_id)
                        ->increment('quantite', $newSupply->quantite);
                }
            }
        });

            return $this->sendResponse($approvise, 'Modification de l\'approvisionnement réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec de modification');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Approvisionnement::find($id)->delete();
        return $this->sendResponse('Suppression de l\'approvisionnement réussi');
    }
}
