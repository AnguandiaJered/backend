<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\PerteProduit;
use App\Models\Produit;
use App\Models\Type_perte;
use App\Models\User;
use DB;

class PerteProduitController extends Controller
{
    use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perteprod = PerteProduit::with(['product','typegas','user'])->orderBy('id','desc')->paginate(5);
        return $this->sendData($perteprod);
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
            'gaspillage_id' => 'required',
        ]); //

        try {
          DB::transaction(function () use ($request) {
            $perteprod = new PerteProduit(); //code...

            $perteprod->produit_id = $request->input('produit_id');
            $perteprod->quantite = $request->input('quantite');
            $perteprod->gaspillage_id = $request->input('gaspillage_id');
            $perteprod->author_id = Auth::user()->id;
            $perteprod->save();

            // Mettre à jour le stock du produit

            $product = Product::findOrFail($request->produit_id);
            $product->decrement('quantite', $request->quantite);
        });

            return $this->sendResponse($perteprod, 'Enregistrement de perte produit réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perteprod = PerteProduit::findOrFail($id); //
        return $this->sendData($perteprod);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $perteprod = PerteProduit::findOrFail($id); //
        return $this->sendData($perteprod);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'produit_id' => 'sometimes|integer|required_with:quantite',
            'quantite' => 'sometimes|integer|min:1',
            'gaspillage_id' => 'sometines|integer'
        ]);

        try {
      
            DB::transaction(function () use ($request, $id) {
                // Récupérer l'ancienne perte produit
                $oldSale = DB::table('perte_produits')->find($id);

                if ($oldSale) {
                    // Retourner l'ancienne quantité au produit (car on va peut-être la modifier)
                    if ($oldSale->produit_id && $oldSale->quantite) {
                        DB::table('produits')
                            ->where('id', $oldSale->produit_id)
                            ->increment('quantite', $oldSale->quantite);
                    }

                    // Mettre à jour la perte de produit
                    // DB::update("UPDATE perte_produits SET product_id = COALESCE(NULLIF(?, 0), product_id), quantity = COALESCE(NULLIF(?, 0), quantity),
                    //     gaspillage_id = COALESCE(NULLIF(?, 0), gaspillage_id), author_id = ? WHERE id = ?",
                    //     [$request->product_id,$request->quantity,$request->gaspillage_id,Auth::user()->id,$id]
                    // );

                    $perteprod = PerteProduit::findOrFail($id); //

                    $perteprod->produit_id = $request->input('produit_id');
                    $perteprod->quantite = $request->input('quantite');
                    $perteprod->gaspillage_id = $request->input('gaspillage_id');
                    $perteprod->author_id = Auth::user()->id;
                    $perteprod->save();

                    // Récupérer la nouvelle perte de produit pour avoir les valeurs mises à jour
                    $newSale = DB::table('perte_produits')->find($id);

                    // Déduire la nouvelle quantité du produit
                    if ($newSale->produit_id && $newSale->quantite) {
                        DB::table('products')
                            ->where('id', $newSale->produit_id)
                            ->decrement('quantite', $newSale->quantite);
                    }
                }
            });

            return $this->sendResponse($perteprod, 'Modification de perte produit réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PerteProduit::find($id)->delete();
        return $this->sendResponse('Suppression de perte produit réussi');
    }
}
