<?php

namespace Khludev\KuLaraPanel\Traits;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;

trait Controller
{
    protected function filter($filter, $object)
    {
        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value != '' && $value != null) {
                    if (is_array($value)) {
                        $object->whereIn($key,array_values($value));
                    } else {
                        if (preg_match('/_id$/i', $key)) {
                            $object->where($key,$value);
                        } elseif (preg_match('/_at$/i', $key)) {
                            $object->whereBetween($key,[Carbon::parse($value),Carbon::parse($value)->addDay()]);
                        } elseif (preg_match('/_at_range$/i', $key)) {
                            $value = explode(' - ', $value);
                            $object->whereBetween(str_replace('_range', '', $key),[Carbon::parse($value[0]),Carbon::parse($value[1])->addDay()]);
                        } elseif (preg_match('/_date_range$/i', $key)) {
                            $value = explode(' - ', $value);
                            $object->whereBetween(str_replace('_date_range', '', $key),[Carbon::parse($value[0]),Carbon::parse($value[1])->addDay()]);
                        } else {
                            $object->where($key,'like','%'.$value.'%');
                        }
                    }
                }
            }
            request()->flashOnly('filter');
            session()->put(compact('filter'));
        }
        return $object;
    }
    protected function exporting($data,$filename = '')
    {
        $filename = $filename == ''? time():$filename;
        return (new FastExcel($data))->download($filename.'.xls');
    }
    protected function initSeo($model_name,$model_id)
    {
        $seotool = app(config('kulara.models.seotool'))
                    ->query()->where('model',$model_name)
                    ->where('model_id',$model_id)->first();
        $model = app($model_name)->find($model_id);
        SEOMeta::setTitle($seotool->title);
        SEOMeta::setDescription($seotool->description);
        SEOMeta::addMeta($seotool->jsonld_type.':published_time', (\Carbon\Carbon::parse($model->created_at))->toW3CString(), 'property');
        SEOMeta::addMeta($seotool->jsonld_type.':modified_time', (\Carbon\Carbon::parse($model->created_at))->toW3CString(), 'property');
        SEOMeta::addKeyword($seotool->keywords);
        SEOMeta::setCanonical($seotool->canonical);
        if (isset($model->tags) && is_array($model->tags)) {
            foreach ($model->tags as $tag) {
                SEOMeta::addMeta($seotool->jsonld_type.':tags', $tag, 'tag');
            }
        }
        if (isset($seotool->metas) && is_array($seotool->metas)) {
            foreach ($seotool->metas as $meta) {
                SEOMeta::addMeta($seotool->jsonld_type.':metas', $meta, 'meta');
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
                OpenGraph::addProperty('Property',$prop);
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
}
