<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\VirtualMoneyExchange;
use App\Models\VirtualMoney;
use App\Libs\VirtualMoneyLibs;
use App\Libs\registerLibs;
use App\Http\Requests\v1\VirtualMoney\ExchangeRequest;
class VirtualMoneyExchangeController extends Controller {

    use ApiResponder;


    /**
     * @OA\Get(
     * path="/money/exchange/company/{id}",
     * summary="List of all exchanges for one company",
     * description="Affichage de toutes les transactions sur le compte virtuel de cette compagnie",
     * tags={"VirtualMoneyExchange"},
     * @OA\Parameter(
     *          name="id",
     *          description="company id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return VirtualMoneyExchange object",
     *     @OA\JsonContent(ref="#/components/schemas/VirtualMoneyExchange")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="No exchange found"
     *      )
     * )
     */
    function getExchangeCompany ($id_company) {
        // on vérifie l'id passé
        $company = registerLibs::checkIdCompany($id_company);
        if ($company['error']) {
            return $this->jsonError("No company found");
        }
        // Cette compagnie a-t-elle un compte virtuel ?
        $id_count = VirtualMoneyLibs::getIdCount(1, $id_company);
        if (isset($id_count['error'])) {
            return $this->jsonError($id_count['message'], $id_count['status_code']);
        }
        // on récupère les transactions de ce compte
        $exchanges = VirtualMoneyExchange::where('virtual_money_id', $id_count)->get();
        if ($exchanges->isEmpty()) {
            return $this->jsonError("No transaction found");
        }
        return $exchanges;
    }


    /**
     * @OA\Get(
     * path="/money/exchange/user/{id}",
     * summary="List of all exchanges for one user",
     * description="Affichage de toutes les transactions sur le compte virtuel de cet utilisateur",
     * tags={"VirtualMoneyExchange"},
     * @OA\Parameter(
     *          name="id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return VirtualMoneyExchange object",
     *     @OA\JsonContent(ref="#/components/schemas/VirtualMoneyExchange")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="No exchange found"
     *      )
     * )
     */
    function getExchangeUser ($id_user) {
        // on vérifie l'id passé
        $user = registerLibs::checkIdUser($id_user);
        if ($user['error']) {
            return $this->jsonError("No user found");
        }
        // Ce user a-t-il un compte virtuel ?
        $id_count = VirtualMoneyLibs::getIdCount(2, $id_user);
        if (isset($id_count['error'])) {
            return $this->jsonError($id_count['message'], $id_count['status_code']);
        }
        // on récupère les transactions de ce compte
        $exchanges = VirtualMoneyExchange::where('virtual_money_id', $id_count)->get();
        if ($exchanges->isEmpty()) {
            return $this->jsonError("No transaction found");
        }
        return $exchanges;
    }

    /**
     * @OA\Get(
     * path="/money/exchange/list",
     * summary="Store a Virtual Money Exchange",
     * description="Affichage de toutes les transactions en monnaie virtuelle passées",
     * tags={"VirtualMoneyExchange"},
     *
     *  @OA\Response(
     *     response=200,
     *     description="return VirtualMoneyExchange object",
     *     @OA\JsonContent(ref="#/components/schemas/VirtualMoneyExchange")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="List exchange not found"
     *      )
     * )
     */
    function getList () {
        return $this->jsonGet ('No transactions found', VirtualMoneyExchange::all());
    }

    /**
     * @OA\Post(
     * path="/money/exchange",
     * summary="Store a Virtual Money Exchange",
     * description="Création d'une transaction sur un compte virtuel",
     * tags={"VirtualMoneyExchange"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/ExchangeRequest")
     *   ),
     *  @OA\Response(
     *     response=200,
     *     description="return VirtualMoneyExchange object",
     *     @OA\JsonContent(ref="#/components/schemas/VirtualMoneyExchange")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="Exchange not store"
     *      )
     * )
     */
    function store (ExchangeRequest $request) {
        //y a-t-il un compte correspondant à cet id ?
        $count = VirtualMoney::find($request->virtual_money_id);
        if (!$count) {
            return $this->jsonError('No account found');
        }
        try {
            //vérifier qu'il y a assez sur le compte
            if ($count->amount < $request->amount) {
                throw new \Exception("Not enough virtual money");
            }
            // s'il y a assez de monnaie virtuelle, on crée la transaction
            VirtualMoneyExchange::create($request->all());
            $count->amount -= $request->amount;
            $count->save();
            return $this->jsonSuccess ($count, "Account updated = $count->amount");
        }
        catch (\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }
}