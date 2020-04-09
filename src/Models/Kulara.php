<?php

namespace Khludev\KuLaraPanel\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Spatie\MediaLibrary\Models\Media;

class Kulara extends Eloquent
{
    public function getDropZoneMedia($collection_name)
    {
        $modelMame = class_basename($this);
        $model_string = class_to_url_str($modelMame);

        $media = $this->media()->where('collection_name', $collection_name)
            ->where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->paginate(8);

        $data = collect();
        $media->withPath(route("admin.{$model_string}s.get_images", ['model_id' => $this->id, 'collect' => $collection_name]));

        $data->paginate = $media->toJson();
        $data->dropzone = collect($media->map(function ($item) use ($model_string) {
            return dropImage($item, route("admin.{$model_string}s.remove_image", $item->id));
        }));

        return $data;

    }


}
