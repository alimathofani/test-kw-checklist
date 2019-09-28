<?php

namespace App\Transformers\Serializer;

use League\Fractal\Serializer\ArraySerializer;

class CreateTemplateSerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        if ($resourceKey) {
            return [$resourceKey => $data];
        }

        return $data;
    }

    public function item($resourceKey, array $data)
    {
        if ($resourceKey) {
            return ['data' => $data];
        }
        return $data;
    }
}