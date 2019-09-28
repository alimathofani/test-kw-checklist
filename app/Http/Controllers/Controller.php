<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use App\Transformers\Serializer\CustomSerializer;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $statusCodes = [
		'done' => 200,
		'created' => 201,
		'removed' => 204,
		'not_valid' => 400,
		'not_found' => 404,
		'conflict' => 409,
		'permissions' => 401
    ];
    
    private function getFractalManager()
    {
        $request = app(Request::class);
        $manager = new Manager();
        $manager->setSerializer(new CustomSerializer());
        if (!empty($request->query('include'))) {
            $manager->parseIncludes($request->query('include'));
        }
        return $manager;
    }

    public function item($data, $transformer, $serialize = null)
    {
        $manager = $this->getFractalManager();
        
        if ($serialize) {
            $manager->setSerializer($serialize);
        }else{
            $manager->setSerializer(new CustomSerializer($transformer->baseUrl()));
        }


        $resource = new Item($data, $transformer, $transformer->type);
        return $manager->createData($resource)->toArray();
    }

    public function collection($data, $transformer, $serialize = null)
    {
        $manager = $this->getFractalManager();

        if ($serialize) {
            $manager->setSerializer($serialize);
        }
        
        $resource = new Collection($data, $transformer, $transformer->type);
        return $manager->createData($resource)->toArray();
    }

    /**
     * @param LengthAwarePaginator $data
     * @param $transformer
     * @return array
     */
    public function paginate($data, $transformer, $serialize = null)
    {
        $manager = $this->getFractalManager();
        $resource = new Collection($data, $transformer, $transformer->type);
        
        $resource->setPaginator(new IlluminatePaginatorAdapter($data));
        
        if ($serialize) {
            $manager->setSerializer($serialize);
        }else{
            $manager->setSerializer(new CustomSerializer($transformer->baseUrl()));
        }

        $customs = $manager->createData($resource)->toArray();
        return $customs;
    }

    protected function respond($status, $data = [])
    {
        switch ($status) {
            case "not_found":
                return response()->json([
                    'status' => $this->statusCodes[$status],
                    'error' => "Not Found"
                ], $this->statusCodes[$status]);
                break;
            default:
            return response()->json($data, $this->statusCodes[$status]);
        }
    }
}
