<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiRestaurantController extends Controller
{
    const CODE_SUCCESS = 200;
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMeal()
    {
        $meals = $this->getListMeal();

        return response()->json(['data' => $meals]);
    }

    /**
     * @param paramsMeal
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRestaurants($paramsMeal)
    {
        $result = [];
        $data = $this->getData();
        $meals = $this->getListMeal();

        
        foreach ($meals as $meal) {
            foreach ($data as $value) {
                if (in_array($meal, $value->availableMeals)) {
                    $result[$meal][] = $value->restaurant;
                }
            }
        }
        foreach ($result as $key => $value) {
            $temp = array_unique($value);
            $result[$key] = $temp;
        }

        return response()->json(['data' => $result[$paramsMeal]]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDishes(Request $request)
    {
        $data = $this->getData();
        $result = array_filter($data, function($item) use($request) {
            return $item->restaurant == $request->restaurant && in_array($request->meal, $item->availableMeals);
        });

        return response()->json(['data' => array_column($result, 'name')]);
    }

    public function create(Request $request)
    {
        Log::info('Created successfully', $request->all());
        return response()->json(['data' => $request->all()], self::CODE_SUCCESS);
    }

    private function getData()
    {
        $data = file_get_contents(base_path('database/dishes.json'));
        $dataResult = json_decode($data)->dishes;

        return $dataResult;
    }

    private function getListMeal()
    {
        $meals = [];
        $data = $this->getData();
        $arrMeals = array_column($data, 'availableMeals');

        foreach ($arrMeals as $value) {
            foreach ($value as $meal) {
                if (!in_array($meal, $meals)) $meals[] = $meal;
            }
        }

        return $meals;
    }
}
