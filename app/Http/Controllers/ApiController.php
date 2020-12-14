<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Balls;
use App\BallsLogic;

class ApiController extends Controller
{


    public function createRun(Request $request): JsonResponse
    {
        $ballsLogic = new BallsLogic();
        $nrOfColors = $request->input('nrOfColors');
        $input =  json_decode($request->input('json'),true);
        $result = $ballsLogic->run($nrOfColors,$input);

        if ($result['hasError']) {
            return response()->json(
                [
                    "message" => $result['error']
                ],
                400
            );
        }

        $run = new Balls;
        $run->nr_of_colors = $nrOfColors;
        $run->distribution = json_encode($result['colors']);
        $run->groups = json_encode($result['groups']);
        $run->save();

        $result['id'] = $run->id;

        return response()->json(
            $result,
            201
        );
    }

    public function getRun($id): JsonResponse
    {
        if (Balls::where('id', $id)->exists()) {
            $ball = Balls::where('id', $id)->get()[0];
            $ball['distribution'] = json_decode($ball['distribution'], true);
            $ball['groups'] = json_decode($ball['groups'], true);
            response()->json($ball, 200);
        } else {
            return response()->json(
                [
                    "message" => "Not found"
                ],
                404
            );
        }
    }

}
