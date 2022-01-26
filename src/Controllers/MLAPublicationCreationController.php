<?php

namespace Aliraza371\MlPublicationCreation\Controllers;
use Aliraza371\MlPublicationCreation\Resources\CategorySelectResource;
use App\Category;
use App\Connectors\MercadolibreConnector;
use App\Publication;
use App\Services\CreateMlaProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * Created by PhpStorm.
 * User: aliraza
 * Date: 24/01/2022
 * Time: 11:27 AM
 */
class MLAPublicationCreationController
{

    public function getMLACategories()
    {
        return  CategorySelectResource::collection(Category::whereNull('parent_id')->whereNotNull('mla_category_id')->get());
    }

    public function getCategoryAttributes($categoryId)
    {
        $mercadolibreConnector = new MercadolibreConnector();

        return  $mercadolibreConnector->getCategoryAttributes($categoryId);
    }

    public function createMLPublication(Request $request)
    {
        $errors = [];
        $publication = Publication::find($request['publication_id']);
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'attributes.*.value_name' => 'required',
        ], [], [
            'category_id' => 'category',
            'attributes.*.value_name' => 'attribute'
        ]);

        if ($validator->fails())
        {
            foreach ($validator->errors()->all() as $error)
            {
                array_push($errors, $error);
            }
        }

        $errors = $this->verifyTheRequiredFieldsPublication($publication, $errors);

        if(count($errors ) == 0)
        {
            $data = $request->all();
            $createMlaProductService = new CreateMlaProductService();
            $createMlaProductService->createMlaProduct($publication, $data['attributes'],$data['category_id']);
        }

        return [
            'errors' => $errors
        ];
    }

    public function verifyTheRequiredFieldsPublication($publication, $errors)
    {

        $requiredFields = ['name', 'description', 'price'];
        foreach ($requiredFields as $requiredField)
        {
            if (!$publication[$requiredField])
            {
                array_push($errors, 'The '.$requiredField.' field must required, Please add that into publication to proceed');
            }
        }


        return $errors;
    }
}
