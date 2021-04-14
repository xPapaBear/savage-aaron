<?php

namespace App\Http\Controllers;

use App\Models\Multiplier;
use Illuminate\Http\Request;
use App\Http\Requests\SaveMultiplierRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MultiplierController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $shop = $this->checkAuth();

        $multiplier = Multiplier::latest()->first();

        return response()->json([
            'success' => true,
            'message' => __('messages.success'),
            'data' => $multiplier
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(SaveMultiplierRequest $request, $id)
    {
        try {
            $shop = Auth::user();

            \DB::beginTransaction();
            logger("start saving");

            $multiplier = Multiplier::find($id);

            if ( $multiplier->value === $request->get('value') ) {
                $multiplier->update($request->validated());
            } else {
                $multiplier = Multiplier::create($request->validated());
            }

            $shopThemes = $this->getShopThemes($shop);
            
            // add snippet
            logger("start adding snippet");
            $this->addSnippet($shopThemes, $shop, $multiplier->value);
            logger("end saving snippet");

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.save_success'),
                'data' => $multiplier
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            logger($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('messages.save_failed'),
            ]);
        }
    }

    /**
     * Get Multiplier History
     */
    public function getHistory( Request $request ) {
        $shop = $this->checkAuth();

        $multipliers = Multiplier::orderBy( 'id', 'DESC' )->get();

        return response()->json([
            'success' => true,
            'message' => __('messages.success'),
            'data' => $multipliers
        ]);
    }

    public function addSnippet($storeThemes, $shop, $multiplierValue, $multiplierLabel = 'Entries'){
        logger(json_encode($storeThemes));

        foreach ($storeThemes as $theme) {

            // add snippet
            try {
                $snippet = (string) '
                    {%- comment -%}Please do not edit this file. Any modification can be lost as it is automatically updated by Savage Club{%- endcomment -%}
                ';
                $snippet .= (string) "
                    <div class='entry-points' data-multiplier='$multiplierValue' data-label='$multiplierLabel'>
                        {{ $multiplierValue | times: amount }}
                    </div>
                ";

                $add_snippet = $shop->api()->request(
                    'PUT',
                    '/admin/api/themes/'.$theme->id.'/assets.json',
                    ['asset' => ['key' => 'snippets/entry-points.liquid', 'value' => $snippet] ]
                );
            } catch(\GuzzleHttp\Exception\ClientException $e){
                logger('add addSnippet throws client exception');
                logger($e->getMessage() . " " . $e->getTraceAsString());

            } catch(\Exception $e){
                logger('add addSnippet throws client exception');
                logger($e->getMessage() . " " . $e->getTraceAsString());
            }
        }
    }
}
