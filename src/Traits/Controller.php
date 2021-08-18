<?php

namespace asimshazad\simplepanel\Traits;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use Spatie\MediaLibrary\Models\Media;

trait Controller
{
    protected function filter($filter, $object)
    {
        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value != '' && $value != null) {
                    if (is_array($value)) {
                        $object->whereIn($key, array_values($value));
                    } else {
                        if (preg_match('/_id$/i', $key)) {
                            $object->where($key, $value);
                        } elseif (preg_match('/_at$/i', $key)) {
                            $object->whereBetween($key, [Carbon::parse($value), Carbon::parse($value)->addDay()]);
                        } elseif (preg_match('/_at_range$/i', $key)) {
                            $value = explode(' - ', $value);
                            $object->whereBetween(str_replace('_range', '', $key), [Carbon::parse($value[0]), Carbon::parse($value[1])->addDay()]);
                        } elseif (preg_match('/_date_range$/i', $key)) {
                            $value = explode(' - ', $value);
                            $object->whereBetween(str_replace('_date_range', '', $key), [Carbon::parse($value[0]), Carbon::parse($value[1])->addDay()]);
                        } else {
                            $object->where($key, 'like', '%' . $value . '%');
                        }
                    }
                }
            }
            request()->flashOnly('filter');
            session()->put(compact('filter'));
        }
        return $object;
    }

    protected function exporting($data, $filename = '')
    {
        $filename = $filename == '' ? time() : $filename;
        return (new FastExcel($data))->download($filename . '.xls');
    }

    protected function initSeo($model_name, $model_id)
    {
        $seotool = app(config('asimshazad.models.seotool'))
            ->query()->where('model', $model_name)
            ->where('model_id', $model_id)->first();

        if (!$seotool)
            return false;

        $model = app($model_name)->find($model_id);
        SEOMeta::setTitle($seotool->title);
        SEOMeta::setDescription($seotool->description);
        SEOMeta::addMeta($seotool->jsonld_type . ':published_time', (\Carbon\Carbon::parse($model->created_at))->toW3CString(), 'property');
        SEOMeta::addMeta($seotool->jsonld_type . ':modified_time', (\Carbon\Carbon::parse($model->created_at))->toW3CString(), 'property');
        SEOMeta::addKeyword($seotool->keywords);
        SEOMeta::setCanonical($seotool->canonical);
        if (isset($model->tags) && is_array($model->tags)) {
            foreach ($model->tags as $tag) {
                SEOMeta::addMeta($seotool->jsonld_type . ':tags', $tag, 'tag');
            }
        }
        if (isset($seotool->metas) && is_array($seotool->metas)) {
            foreach ($seotool->metas as $meta) {
                SEOMeta::addMeta($seotool->jsonld_type . ':metas', $meta, 'meta');
            }
        }

        JsonLd::setTitle($seotool->jsonld_title);
        JsonLd::setDescription($seotool->jsonld_description);
        JsonLd::setType($seotool->jsonld_type);
        foreach ($seotool->jsonld_images as $image) {
            JsonLd::addImage(asset($image));
        }

        OpenGraph::setDescription($seotool->og_description);
        OpenGraph::setTitle($seotool->og_title);
        OpenGraph::setUrl('http://current.url.com');

        OpenGraph::setTitle($seotool->og_title)
            ->setDescription($seotool->og_description)
            ->setType($seotool->jsonld_type)
            ->setArticle($seotool->og_model);
        if (count($seotool->og_properties)) {
            foreach ($seotool->og_properties as $prop) {
                OpenGraph::addProperty('Property', $prop);
            }
        }
        if (count($seotool->og_images)) {
            foreach ($seotool->og_images as $image) {
                OpenGraph::addImage(asset($image));
            }
        }

        TwitterCard::setTitle($seotool->twitter_title);
        TwitterCard::setSite($seotool->twitter_site);

    }

    //Клеїсм водяний знак
    public function insertWatermark($image_path)
    {
        if (!$watermark = config('app.watermark_path'))
            return info('\'app.watermark_path\' : не вказано посилання на зображення');

        $image = Image::make($image_path)->insert($watermark, 'bottom-right', 10, 10);
        $image->save($image_path);
    }

    public function removeImage($img)
    {
        if ($static_page = Media::find($img)) {
            activity('Deleted Static Page Image: ' . $static_page->updated_by);

            return response()->json(['status' => $static_page->delete()]);

        } else
            return response()->json(['status' => 'no_valid id ' . $img]);
    }


    public function uploadImages($model_id = null)
    {
        if ($model_id === null)
            return response()->json(['uploads' => false]);

        $this->validate(request(), [
            "file" => "image|mimes:jpeg,png,jpg,svg|max:2048",
            "collect" => "string",
        ]);
        $modelMame = get_model_from_controller($this);
        $class = 'App\\' . $modelMame;
        $model = $class::find($model_id);
        $colection = request()->collect ?? $class;
        $model_string = class_to_url_str($modelMame);

        if (request()->hasFile('file')) {
            $img = $model
                ->addMedia(request()->file)
                ->usingFileName(uniqid() . '.' . request()->file->getClientOriginalExtension())
                ->toMediaCollection($colection);

            $response = dropImage($img, route("admin.{$model_string}s.remove_image", $img->id));

            return response()->json($response);

        }


    }

    public function getImages(Request $request)
    {
        $modelMame = get_model_from_controller($this);
        $class = 'App\\' . $modelMame;
        $model = $class::find($request->model_id);

        $media = $model->getDropZoneMedia($request->get('collect'));

        return response()->json([
            'images' => $media->dropzone->toArray(),
            'paginate' => $media->paginate,
        ]);
    }

    /*
 * Оновлення атрибутів зображення
 */
    public function saveImgAttributes()
    {
        if (!$dzImgAttrs = request()->get('dzImgAttrs'))
            return false;

        $images = Media::select('id', 'custom_properties')->whereIn('id', array_keys($dzImgAttrs))->get();

        $images->map(function ($img) use (&$dzImgAttrs) {
            if (isset($dzImgAttrs[$img->id]['alt'])) $img->setCustomProperty('alt', $dzImgAttrs[$img->id]['alt']);
            if (isset($dzImgAttrs[$img->id]['title'])) $img->setCustomProperty('title', $dzImgAttrs[$img->id]['title']);
            if (isset($dzImgAttrs[$img->id]['source'])) $img->setCustomProperty('source', $dzImgAttrs[$img->id]['source']);
            $img->save();
        });

        return response('200');

    }
}
