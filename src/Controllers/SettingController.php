<?php

namespace asimshazad\simplepanel\Controllers;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Html\Builder;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Settings')->only(['createForm', 'create']);
        $this->middleware('can:Read Settings')->only(['index', 'read']);
        $this->middleware('can:Update Settings')->only(['updateForm', 'update']);
        $this->middleware(['can:Delete Settings'])->only('delete');
    }

    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $settings = app(config('asimshazad.models.setting'))->query();
            $datatable = datatables($settings)
                ->editColumn('value', function ($setting) {
                    if (is_array(settings($setting->key))) {
                        return prettyPrintJson(settings($setting->key));
                    }
                    return settings($setting->key);
                })
                ->editColumn('code', function ($setting) {
                    return 'settings(\''.$setting->key.'\');';
                })
                ->editColumn('actions', function ($setting) {
                    return view('asimshazad::settings.datatable.actions', compact('setting'));
                })
                ->rawColumns(['actions']);

            return $datatable->toJson();
        }

        $html = $builder->columns([
            ['title' => 'Key', 'data' => 'key'],
            ['title' => 'Value', 'data' => 'value'],
            ['title' => 'Code', 'data' => 'code'],
            ['title' => '', 'data' => 'actions', 'searchable' => false, 'orderable' => false],
        ]);
        $html->setTableAttribute('id', 'settings_datatable');

        return view('asimshazad::settings.index', compact('html'));
    }

    public function createForm()
    {
        return view('asimshazad::settings.create');
    }

    public function create()
    {
        $this->validate(request(), [
            'key' => 'required|unique:settings,key',
        ]);
        if (request('multipleTypes') == true) {
            $values = [];
            foreach (request('values') as $key => $value) {
                $index = request('indexes.'.$key);
                $values[$index] = $value;
            }
            request()->merge(['value' => $values]);
        }
        $setting = app(config('asimshazad.models.setting'))->create(request()->all());

        activity('Created Setting: ' . $setting->id, request()->all(), $setting);
        flash(['success', 'Setting created!']);

        if (request()->input('_submit') == 'redirect') {
            return response()->json(['redirect' => session()->pull('url.intended', route('admin.settings'))]);
        }
        else {
            return response()->json(['reload_page' => true]);
        }
    }

    public function read($id)
    {
        $setting = app(config('asimshazad.models.setting'))->find($id);
        return view('asimshazad::settings.read', compact('setting'));
    }

    public function updateForm($id)
    {
        $setting = app(config('asimshazad.models.setting'))->find($id);
        return view('asimshazad::settings.update', compact('setting'));
    }

    public function update($id)
    {
        $setting = app(config('asimshazad.models.setting'))->find($id);
        $this->validate(request(), [
            'key' => 'required',
        ]);
        if (is_array($setting->value)) {
            $values = [];
            foreach (request('values') as $key => $value) {
                $index = request('indexes.'.$key);
                $values[$index] = $value;
            }
            request()->merge(['value' => $values]);
        }

        activity('Updated Setting: ' . $setting->id, request()->all(), $setting);
        flash(['success', 'Setting updated!']);

        $setting->update(request()->all());

        if (request()->input('_submit') == 'redirect') {
            return response()->json(['redirect' => session()->pull('url.intended', route('admin.settings'))]);
        }
        else {
            return response()->json(['reload_page' => true]);
        }
    }

    public function delete($id)
    {
        $setting = app(config('asimshazad.models.setting'))->find($id);
        $setting->delete();

        activity('Deleted Setting: ' . $setting->id, $setting->toArray());
        $flash = ['success', 'Setting deleted!'];

        if (request()->input('_submit') == 'reload_datatables') {
            return response()->json([
                'flash' => $flash,
                'reload_datatables' => true,
            ]);
        }
        else {
            flash($flash);

            return redirect()->route('admin.settings');
        }
    }
}
