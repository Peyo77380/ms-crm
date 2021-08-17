<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;

use App\Http\Controllers\Controller;

use App\Models\Sondages;

use App\Http\Requests\V1\Sondages\SondagesStoreRequest;

use App\Http\Requests\V1\Sondages\SondagesUpdateRequest;

class SondagesController extends Controller
{
    use ApiResponder;

    function getById($id)
    {
        return $this->jsonById($id, Sondages::find($id));
    }

    function get()
    {
        $tasksList = Sondages::paginate(10);
        return $this->jsonSuccess($tasksList);
    }


    /**
     * create sondage
     */
    function store(SondagesStoreRequest $request)
    {
        $sondage = Sondages::create($request->all());
        if ($sondage) {
            return $this->jsonSuccess($sondage);
        }
        return $this->jsonError('Survey already exist', 409);
    }

    /**
     * update sondage
     */
    public function update(SondagesUpdateRequest $request, $id)
    {
        $sondage = Sondages::find($id);

        if (!$sondage) {
            return $this->jsonError('Something is wrong, please check datas - Code B320', 409);
        }

        $updatedSondage = $sondage->update($request->all());

        if(!$updatedSondage) {
            return $this->jsonError('Could not update this item - Code R311', 502);
        }

        return $this->jsonSuccess($updatedSondage);

    }

    /**
     * delete sondage
     */
    function delete($id)
    {
        $sondage = Sondages::find($id);
        if($sondage){
            $sondage->status=99;
            return $this->jsonSuccessNoDatas("The survey '$sondage->name' has been archived");
        }
    }

}
