<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Sortie;
use App\Models\Produit;
use App\Models\User;
use App\Models\Client;
use DB;

class SortieController extends Controller
{
    use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sortie = Sortie::with(['product','client','user'])->orderBy('id','desc')->paginate(5);
        return $this->sendData($sortie);
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
            'produit_id' => 'required',
            'quantite' => 'required',
            'prix' => 'required',
        ]);

        try {
            // Vérifier que la quantité demandée est disponible en stock
            $produit = Produit::findOrFail($request->produit_id);

            if ($request->quantite > $produit->quantity) {
                return redirect()->back()->withInput()->with([
                    'message' => 'La quantité demandée est supérieure à la quantité disponible en stock (Disponible: '.$produit->quantite.').',
                    'alert-type' => 'error',
                ]);
            }

            DB::transaction(function () use ($request) {
                $sortie = new Sortie(); //code...

                $sortie->client_id = $request->input('client_id');
                $sortie->produit_id = $request->input('produit_id');
                $sortie->quantite = $request->input('quantite');
                $sortie->prix = $request->input('prix');
                $sortie->author_id = Auth::user()->id;
                $sortie->save();

                // Mettre à jour le stock du produit

                $produit = Produit::findOrFail($request->produit_id);
                $produit->decrement('quantite', $request->quantite);
            });

            return $this->sendResponse($sortie, 'Enregistrement de vente réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sortie = Sortie::findOrFail($id); //
        return $this->sendData($sortie);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sortie = Sortie::findOrFail($id); //
        return $this->sendData($sortie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'client_id' => 'sometimes|integer',
            'product_id' => 'sometimes|integer|required_with:quantity',
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
        ]);

         try {
         // Vérifier que la quantité demandée est disponible en stock
        $product = Produit::findOrFail($request->product_id);

        if ($request->quantite > $product->quantite) {
            return redirect()->back()->withInput()->with([
                'message' => 'La quantité demandée est supérieure à la quantité disponible en stock (Disponible: '.$product->quantite.').',
                'alert-type' => 'error',
            ]);
        }

        DB::transaction(function () use ($request, $id) {
            // Récupérer l'ancienne sortie
            $oldSale = DB::table('sorties')->find($id);

            if ($oldSale) {
                // Retourner l'ancienne quantité au produit (car on va peut-être la modifier)
                if ($oldSale->produit_id && $oldSale->quantite) {
                    DB::table('products')
                        ->where('id', $oldSale->produit_id)
                        ->increment('quantite', $oldSale->quantite);
                }

                // Mettre à jour la sortie
                $sortie = Sortie::findOrFail($id); //

                $sortie->client_id = $request->input('client_id');
                $sortie->produit_id = $request->input('produit_id');
                $sortie->quantite = $request->input('quantite');
                $sortie->prix = $request->input('prix');
                $sortie->author_id = Auth::user()->id;
                $sortie->save();

                // Récupérer la nouvelle sortie pour avoir les valeurs mises à jour
                $newSale = DB::table('sorties')->find($id);

                // Déduire la nouvelle quantité du produit
                if ($newSale->produit_id && $newSale->quantite) {
                    DB::table('produits')
                        ->where('id', $newSale->produit_id)
                        ->decrement('quantite', $newSale->quantite);
                }
            }
        });
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
        Sortie::find($id)->delete();
        return $this->sendResponse('Suppression de Vente réussi');
    }
}
