<?php

namespace asimshazad\simplepanel\Traits;

trait Media
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
        $media->withPath(route(config('asimshazad.route_prefix') . "." . str_plural($model_string) . ".get_images", ['model_id' => $this->id, 'collect' => $collection_name]));

        $data->paginate = $media->toJson();
        $data->dropzone = collect($media->map(function ($item) use ($model_string) {
            return dropImage($item, route(config('asimshazad.route_prefix') . "." . str_plural($model_string) . ".remove_image", $item->id));
        }));

        return $data;

    }

    /*
     * replace image short code to image html tag
     */
    public function replShortWithImage($media, $html): string
    {
        $media->filter(function ($img) use (&$html) {
            $from['[img]' . $img->file_name . '[/img]'] = ' <img src="' . $img->getUrl() . '" alt="' . $img->getCustomProperty('alt') . '" title="' . $img->getCustomProperty('title') . '">';

            $html = preg_replace('/\[img\]' . $img->file_name . '\[\/img\]/', '', strtr($html, $from), -1, $count);

        });
        //delete empty short
        $html = preg_replace('/\[img\](.+)\[\/img\]/', '', $html);

        return $html;
    }

}
