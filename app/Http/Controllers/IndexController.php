<?php

namespace App\Http\Controllers;

use PhpSpec\Exception\Exception;
use Response;
use Request;
use Validator;
use App\Interfaces\TwitterReachInterface;

class IndexController extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function getReach(TwitterReachInterface $twitterReach)
    {
        $url = Request::input('url');
        $validator = Validator::make(['url' => $url], ['url' => 'required|url']);

        if (!$validator->fails()) {
            try {
                return response()->json(array('reach' => $twitterReach->getTweetReach($url)));
            } catch (\Exception $e) {
                return Response::json(array('success' => false, 'error' => $e->getMessage()), 400);
            }
        } else {
            return Response::json(array('success' => false, 'error' => 'Please enter a valid url'), 400);
        }

    }

}