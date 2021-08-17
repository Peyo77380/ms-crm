<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    use ApiResponder;

    /**
     * default search returned (the lasts 20 users created) at first load on seacrh page
     */
    function defaultSearch()
    {
        $query = DB::table('users')
            ->orderBy('created_at', 'desc')
            ->limit(20);
        if ($query->get()) {
            return $this->jsonSuccess($query->get());
        };

        return $this->jsonError('No results found', 404);
    }

    /**
     * return results according to filters sent by the front
     */
    function search(Request $request)
    {
        $inputs = $request->input();

        if (isset($inputs['type'])) {

            switch ($inputs['type']) {

                case 'user':
                    $results = $this->_searchUsers($inputs);
                    if ($results) {
                        return $this->jsonSuccess($results);
                    }
                    return $this->jsonError('No results found', 404);

                case 'company':
                    $results = $this->_searchCompanies($inputs);
                    if ($results) {
                        return $this->jsonSuccess($results);
                    }
                    return $this->jsonError('No results found', 404);

                default:
                    return $this->jsonError('Please select type of search', 404);
            }
        }

        return $this->jsonError('Please select type of search', 404);
    }

    /**
     * create query according filters selected then return companies found
     */
    private function _searchCompanies($inputs)
    {
        $query = DB::table('companies');

        if (isset($inputs['key_word'])) {
            $key_word = $inputs['key_word'];
            $query->where('name', 'like', "%$key_word%");
        }

        if (isset($inputs['status'])) {
            $status = $inputs['status'];
            $query->where('status', '=', "%$status%");
        }

        if ($query->get()) {
            return  $query->get();
        };
    }

    /**
     * create query according filters selected then return users found
     */
    private function _searchUsers($inputs)
    {
        $query = DB::table('users');

        if (isset($inputs['status'])) {
            $status = $inputs['status'];
            $query->where('status', '=', "%$status%");
        }

        //TODO: faire en sorte que la partie key word soit entre parenthèse avec des or
        if (isset($inputs['key_word'])) {

            $key_word = $inputs['key_word'];
            //$query->where([     ['firstname', 'like', "%$key_word%"],['lastname', 'like', "%$key_word%"],['email', 'like', "%$key_word%"]]);
            $query->where($key_word, function ($query) use ($key_word) {
                $query->where('firstname', 'like', "%" . $key_word . "%")
                    ->orWhere('lastname', 'like', "%" . $key_word . "%")
                    ->orWhere('email', 'like', "%" . $key_word . "%");
            })
                ->get();
        }
        return $query->toSql();
        if ($query->get()) {
            return $query->get();
        };
    }
}
