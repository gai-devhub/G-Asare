<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('key')->paginate(10);
        return view('admin.pages.settings', compact('settings'));
    }

    public function blogSettings()
    {
        $settings = Setting::orderBy('key')->get();
        return view('admin.pages.blog-settings', compact('settings'));
    }

    public function bulkUpdate(Request $request)
    {
        $stringKeys = [
            'site_title', 'site_description', 'meta_title', 'meta_description', 'meta_keywords', 
            'blog_hero_title', 'blog_hero_description', 'blog_hero_image', 
            'blog_sidebar_title', 'blog_sidebar_description',
            'blog_sidebar_stat1_label', 'blog_sidebar_stat1_value',
            'blog_sidebar_stat2_label', 'blog_sidebar_stat2_value',
            'blog_sidebar_stat3_label', 'blog_sidebar_stat3_value',
            'blog_sidebar_stat4_label', 'blog_sidebar_stat4_value'
        ];
        foreach ($stringKeys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key), 'string');
            }
        }
        Setting::set('maintenance_mode', $request->boolean('maintenance_mode'), 'boolean');

        $videoKeys = ['blog_sidebar_video'];
        foreach ($videoKeys as $vKey) {
            if ($request->hasFile($vKey)) {
                $path = $request->file($vKey)->store('videos');
                Setting::set($vKey, \Storage::disk()->url($path), 'string');
            }
        }

        return back()->with('success', 'Settings saved.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'nullable|string',
            'type' => 'required|string|in:string,boolean,integer,float,json',
        ]);

        Setting::create($request->only(['key', 'value', 'type']));
        return back()->with('success', 'Setting added.');
    }

    public function update(Request $request, Setting $setting)
    {
        $setting->update($request->validate([
            'value' => 'nullable|string',
            'type' => 'required|string|in:string,boolean,integer,float,json',
        ]));

        return back()->with('success', 'Setting updated.');
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();
        return back()->with('success', 'Setting deleted.');
    }
}
