<?php

namespace App\Http\Composers;

use App\Enums\Status;
use App\Models\Language;
use App\Models\BackendMenu;
use Illuminate\Support\Facades\Request;

class BackendMenuComposer
{
    static $blockNodes = [];

    static function backendMenu()
    {

        $backendMenus = BackendMenu::where(['status' => 1])->get()->toArray();
        $myMenu = '';
        $nodes  = static::menuTree($backendMenus, static::$blockNodes);

        return $nodes;
    }

    static function backendLanguage() 
    {
        return Language::where('status', Status::ACTIVE)->get();
    }

    static function menuTree(array $nodes, array $blockNodes = null)
    {
        $tree = [];
        foreach ($nodes as $id => $node) {
            if (isset($node['link']) && !isset($blockNodes[$node['link']])) {

                if (in_array($node['link'], $blockNodes)) {
                    continue;
                }

                if (($node['link'] != '#') && !blank(auth()->user()) && !auth()->user()->can($node['link'])) {
                    continue;
                }

                if ($node['parent_id'] == 0) {
                    $tree[$node['id']] = $node;
                } else {
                    if (!isset($tree[$node['parent_id']]['child'])) {
                        $tree[$node['parent_id']]['child'] = [];
                    }
                    $tree[$node['parent_id']]['child'][$node['id']] = $node;
                }
            }
        }
        return $tree;
    }

    static function classicFrontendMenu(array $nodes, string &$menu)
    {
        foreach ($nodes as $node) {
            if (isset($node['link'])) {

                $f              = 0;
                $dropdown       = 'nav-item dropdown ';
                $dropdownToggle = 'has-dropdown';
                $active         = '';

                if ($node['link'] == '#' && !isset($node['child'])) {
                    continue;
                }

                if (isset($node['child'])) {
                    $f          = 1;
                    $childArray = collect($node['child'])->pluck('link')->toArray();

                    $segmentLink = Request::segment(2);

                    if (in_array($segmentLink, $childArray)) {
                        $active = 'active';
                    }
                }

                if (Request::segment(2) == $node['link']) {
                    $active = 'active';
                }

                $icon = explode(",", $node['icon']);
                $iconList = 'fa-home';
                if (isset($icon[1])) {
                    $iconList = $icon[1];
                } else if (isset($icon[0])) {
                    $iconList = $icon[0];
                }

                $menu .= '<li class="' . ($f ? $dropdown : '') . $active . '">';
                $menu .= '<a class="nav-link ' . ($f ? $dropdownToggle : '') . '" href="' . url('admin/' . $node['link']) . '" >' .
                    '<i class="' . ($iconList) . '"></i> <span>' . (trans('menu.' . $node['name'])) . '</span></a>';

                if ($f) {
                    $menu .= '<ul class="dropdown-menu">';
                    static::classicFrontendMenu($node['child'], $menu);
                    $menu .= "</ul>";
                }
                $menu .= "</li>";
            }
        }
    }

    static function defaultFrontendMenu(array $nodes, string &$menu)
    {

        foreach ($nodes as $node) {
            if (isset($node['link'])) {

                $f              = 0;
                $dropdown       = 'nav-item dropdown ';
                $dropdownToggle = 'dropdown-toggle';
                $active         = '';

                if ($node['link'] == '#' && !isset($node['child'])) {
                    continue;
                }

                if (isset($node['child'])) {
                    $f          = 1;
                    $childArray = collect($node['child'])->pluck('link')->toArray();

                    $segmentLink = Request::segment(2);

                    if (in_array($segmentLink, $childArray)) {
                        $active = 'active';
                    }
                }

                if (Request::segment(2) == $node['link']) {
                    $active = 'active';
                }

                $icon = explode(",", $node['icon']);
                $iconList = 'fa-home';
                if (isset($icon[1])) {
                    $iconList = $icon[1];
                } else if (isset($icon[0])) {
                    $iconList = $icon[0];
                }

                $menu .= '<li class="' . ($f ? $dropdown : '') . $active . '">';
                $menu .= '<a class="nav-link ' . ($f ? $dropdownToggle : '') . '" href="' . url('admin/' . $node['link']) . '" ' . ($f ? 'data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false"' : '') . ' >' .
                    '<span class="nav-link-icon d-md-none d-lg-inline-block">
                   <i class="' . ($iconList) . ' icon"></i></span> <span class="nav-link-title">' . (trans('menu.' . $node['name'])) . '</span></a>';

                if ($f) {
                    $menu .= '<ul class="dropdown-menu">';
                    static::defaultFrontendMenu($node['child'], $menu);
                    $menu .= "</ul>";
                }
                $menu .= "</li>";
            }
        }
    }
}
