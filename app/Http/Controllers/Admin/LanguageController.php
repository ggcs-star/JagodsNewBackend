<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\BackendController;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;
use Setting;
use Yajra\Datatables\Datatables;

class LanguageController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Language';

        $this->middleware(['permission:language'])->only('index');
        $this->middleware(['permission:language_create'])->only('create', 'store');
        $this->middleware(['permission:language_edit'])->only('edit', 'update');
        $this->middleware(['permission:language_delete'])->only('destroy');
    }
    public function index(Request $request)
    {

        return $this->getLanguage($request);
    }


    public function create()
    {
        return view('admin.language.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageRequest $request)
    {
        $flag = null;

        $countries = Countries::all();
        foreach ($countries as  $countrie) {
            if (strtolower($countrie['iso_a2']) == strtolower($request->code)) {
                $flag = $countrie['extra']['emoji'];
            }
        }

        $language            = new Language;
        $language->name      = $request->name;
        $language->code      = $request->code;
        $language->flag_icon = $flag;
        $language->status    = $request->status;
        $language->save();
        return redirect()->route('admin.language.index')->withSuccess('Language Create Succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(language $language)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['language']  = Language::findOrFail($id);
        return view('admin.language.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageRequest $request, Language $language)
    {
        $flag = null;
        $countries = Countries::all();
        foreach ($countries as  $countrie) {
            if (strtolower($countrie['iso_a2']) == strtolower($request->code)) {
                $flag = $countrie['extra']['emoji'];
            }
        }

        $language->name      = $request->name;
        $language->code      = $request->code;
        $language->flag_icon = $flag;
        $language->status    = $request->status;
        $language->save();
        return redirect()->route('admin.language.index')->withSuccess('Language Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Language::findOrFail($id)->delete();
        return redirect(route('admin.language.index'))->withSuccess('The Data Deleted Successfully');
    }

    private function getLanguage(Request $request)
    {
        $defaultLanguage = Setting::get('locale');

        if (request()->ajax()) {

            if ($request->status) {
                $laguages = Language::where('status', $request->status)->get();
            } else {
                $laguages = Language::all();
            }

            $i            = 0;
            return Datatables::of($laguages)
                ->addColumn('action', function ($language) use ($defaultLanguage) {

                    $action_button ['edit'] = ['route' => route('admin.language.edit', $language),'permission' => 'language_edit'];
                    if ($language->code !== $defaultLanguage) {
                        $action_button['delete'] = ['route' => route('admin.language.destroy', $language),'permission' => 'language_delete'];
                    }

                    return action_button($action_button);
                })
                ->editColumn('language_name', function ($language) {
                    return $language->name;
                })
                ->editColumn('flag', function ($language) {
                    return $language->flag_icon == null ? '🇬🇧' : $language->flag_icon;
                })
                ->editColumn('code', function ($language) {
                    return strtoupper($language->code);
                })
                ->editColumn('status', function ($language) {
                    return $language->statusName;
                })
                ->rawColumns(['name', 'action'])
                ->escapeColumns([])
                ->make(true);
        }
        return view('admin.language.index', $this->data);
    }

    public function changeStatus($id, $status)
    {
        $language         = Language::findOrFail($id);
        $language->status = $status;
        $language->save();
        return redirect()->route('admin.language.index')->withSuccess('The Status Change successfully!');
    }
}
