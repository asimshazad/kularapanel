<?php

namespace Khludev\KuLaraPanel\Controllers;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Html\Builder;
use Artesaos\SEOTools\Facades\SEOTools;

class SeotoolController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Seotools')->only(['createForm', 'create']);
        $this->middleware('can:Read Seotools')->only(['index', 'read']);
        $this->middleware('can:Update Seotools')->only(['updateForm', 'update']);
        $this->middleware(['can:Delete Seotools'])->only('delete');
    }

    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $seotools = app(config('lap.models.seotool'))->query()->with('creator', 'modifier');
            $datatable = datatables($seotools)
                ->setRowClass('sortable_row')
                ->editColumn('actions', function ($seotool) {
                    return view('lap::seotools.datatable.actions', compact('seotool'));
                })
                ->rawColumns(['actions'])
                ->filter(function ($query) use($seotools) {
                    $this->filter(request('filter'), $seotools);
                });

            return $datatable->toJson();
        }
        $dtid = 'seotools_datatable';
        $html = $builder->columns([
            ['title' => 'Model', 'data' => 'model'],
            ['title' => 'Model ID', 'data' => 'model_id'],
            ['title' => 'Title', 'data' => 'title'],
            ['title' => 'Title', 'data' => 'description'],
            ['title' => 'Canonical', 'data' => 'canonical'],
            ['title' => 'Created By', 'data' => 'creator.name'],
            ['title' => '', 'data' => 'actions', 'searchable' => false, 'orderable' => false],
        ]);
        $html->setTableAttribute('id', $dtid);
        $html->ajax([
            'data' => 'function (d) {
                d.filter = {};
                $(\'.filterInput\').each(function(index) {
                    d.filter[$(this).attr(\'name\')] = $(this).val();
                });
            }',
        ]);
        $html->parameters([
            'bFilter' => false,
            'dom' => '<"toolbar">lfrtip'
        ]);
        $export_url = '';
        $reorder_url = '';
        return view('lap::seotools.index', compact('html','dtid','export_url','reorder_url'));
    }

    public function createForm($model_id,$model_name)
    {
        return view('lap::seotools.create',compact('model_id','model_name'));
    }

    public function create($model_id,$model_name)
    {
        $model = app(urldecode($model_name))->find($model_id);
        $this->validate(request(), []);

        $og_images = [];
        if (request()->hasFile('og_images_file')) {
            foreach(request()->file('og_images_file') as $key => $file)
            {
                $og_images[] = str_replace('public', 'storage', request()->file('og_images_file.'.$key)->store('public/seo'));
            }
        }
        $jsonld_images = [];
        if (request()->hasFile('jsonld_images_file')) {
            foreach(request()->file('jsonld_images_file') as $key => $file)
            {
                $jsonld_images[] = str_replace('public', 'storage', request()->file('jsonld_images_file.'.$key)->store('public/seo'));
            }
        }

        request()->merge([
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'og_model' => $model->toArray(),
            'og_images' => $og_images,
            'jsonld_images' => $jsonld_images,
            'model' => $model_name,
            'model_id' => $model_id,
        ]);

        $seotool = app(config('lap.models.seotool'))->create(request()->all());

        activity('Created Seotool: ' . $seotool->id, request()->all(), $seotool);
        flash(['success', 'Seotool created!']);

        if (request()->input('_submit') == 'redirect') {
            return response()->json(['redirect' => session()->pull('url.intended', route('admin.seotools'))]);
        }
        else {
            return response()->json(['reload_page' => true]);
        }
    }

    public function read($id)
    {
        $seotool = app(config('lap.models.seotool'))->find($id);
        return view('lap::seotools.read', compact('seotool'));
    }

    public function updateForm($id)
    {
        $seotool = app(config('lap.models.seotool'))->find($id);
        return view('lap::seotools.update', compact('seotool'));
    }

    public function update($id)
    {
        $seotool = app(config('lap.models.seotool'))->find($id);
        $model = app($seotool->getOriginal('model'))->find($seotool->model_id);
        $this->validate(request(), []);

        $og_images = $seotool->og_images;
        if (request()->hasFile('og_images_file')) {
            foreach(request()->file('og_images_file') as $key => $file)
            {
                $og_images[] = str_replace('public', 'storage', request()->file('og_images_file.'.$key)->store('public/seo'));
            }
        }

        $jsonld_images = $seotool->jsonld_images;
        if (request()->hasFile('jsonld_images_file')) {
            foreach(request()->file('jsonld_images_file') as $key => $file)
            {
                $jsonld_images[] = str_replace('public', 'storage', request()->file('jsonld_images_file.'.$key)->store('public/seo'));
            }
        }

        request()->merge([
            'updated_by' => auth()->id(),
            'og_model' => $model->toArray(),
            'og_images' => $og_images,
            'jsonld_images' => $jsonld_images,
        ]);
        $seotool->update(request()->all());

        activity('Updated Seotool: ' . $seotool->id, request()->all(), $seotool);
        flash(['success', 'Seotool updated!']);

        if (request()->input('_submit') == 'redirect') {
            return response()->json(['redirect' => session()->pull('url.intended', route('admin.seotools'))]);
        }
        else {
            return response()->json(['reload_page' => true]);
        }
    }

    public function delete($id)
    {
        $seotool = app(config('lap.models.seotool'))->find($id);
        $seotool->delete();

        activity('Deleted Seotool: ' . $seotool->id, $seotool->toArray());
        $flash = ['success', 'Seotool deleted!'];

        if (request()->input('_submit') == 'reload_datatables') {
            return response()->json([
                'flash' => $flash,
                'reload_datatables' => true,
            ]);
        }
        else {
            flash($flash);

            return redirect()->route('admin.seotools');
        }
    }

    public function reorder()
    {
        $ids = collect(request()->get('ids'))->pluck('id');
        $seqs = collect(request()->get('seqs'))->pluck('seq');

        foreach ($ids as $i => $id) {
            $tag = app(config('lap.models.seotool'))->find($id);
            $tag->timestamps = false; // To disable update_at field
            $tag->update(['seq' => $seqs[$i]]);
        }
        return response()->json([
            'reload_datatables' => true,
        ]);
    }
}
