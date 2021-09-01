<?php

namespace Alvinhu\ChildSelect;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use function Couchbase\defaultDecoder;

class OptionsController extends Controller
{
    public function index(NovaRequest $request)
    {
        $attribute = $request->query('attribute');
        $parentValue = $request->query('parent');

        $resource = $request->newResource();
        $fields = $resource->updateFields($request);
        $field = $this->findFieldByAttribute($fields,$attribute);
        $options = $field->getOptions($parentValue);

        return $options;
    }

    public function findFieldByAttribute($field,$attribute){
        return array_filter($field,function ($field) use ($attribute) {
            if(gettype($field) == "array" && $field["component"] == "tabs"){
                array_filter($field["fields"],function ($field) use($attribute){
                    return isset($field->attribute) && $field->attribute = $attribute;
                });
            }else{
                return isset($field->attribute) &&
                    $field->attribute == $attribute;
            }
        });
    }
}
