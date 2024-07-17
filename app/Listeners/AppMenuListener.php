<?php

namespace App\Listeners;

use App\Events\AppMenuEvent;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\Menu\Laravel\Html;

class AppMenuListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppMenuEvent $event): void
    {
        $menu = $event->menu;
        $menu->html('<span>Main</span>',['class' => 'menu-title']);

        $menu->add(
            Link::toRoute('dashboard', '<i class="la la-dashboard"></i> <span> ' . __('Dashboard') . '</span>')->setActive(route_is('dashboard'))
        );
        $activeClass = route_is(["app.chat"]) ? "active" : "";
        $menu
            ->submenu(
                Html::raw('<a href="#" class="' . $activeClass . '"><i class="la la-cube"></i><span> ' . __("Apps") . '</span><span class="menu-arrow"></span></a>'),
                Menu::new()
                    ->add(
                        Link::toRoute('app.chat', __('Chat'))->addClass(route_is(['app.chat']) ? 'active' : '')
                    )->addParentClass('submenu')
            );
        $menu->html('<span>Employees</span>', ['class' => 'menu-title']);
        $activeClass = route_is(['employees.index','employees.list','departments.index','designations.index','holidays.*']) ? "active" : "";
        $menu
            ->submenuIf(auth()->user()->can([
                    'view-employees','view-departments','view-designations','view-holidays'
                ]),
                Html::raw('<a href="#" class="' . $activeClass . '" class="noti-dot"><i class="la la-user"></i> <span> ' . __('Employees') . '</span><span class="menu-arrow"></span></a>'),
                Menu::new()
                    ->addParentClass('submenu')
                    ->addIfCan('view-employees',Link::toRoute('employees.index', __('Employees'))->addClass(route_is(['employees.index','employees.list']) ? 'active' : ''))
                    ->addIfCan('view-departments',Link::toRoute('departments.index', __('Departments'))->addClass(route_is('departments.index') ? 'active' : ''))
                    ->addIfCan('view-designations',Link::toRoute('designations.index', __('Designations'))->addClass(route_is('designations.index') ? 'active' : ''))
                    ->addIfCan('view-holidays',Link::toRoute('holidays.index', __('Holidays'))->addClass(route_is('holidays.*') ? 'active' : ''))
            );
        $menu->addIfCan(
            'view-clients',
            Link::toRoute('clients.index', '<i class="la la-group"></i> <span>' . __('Clients') . '</span>')->setActive(route_is('clients.*'))
        );
        $menu->addIfCan(
            'view-users',
            Link::toRoute('users.index', '<i class="la la-user-plus"></i> <span>' . __('Users') . '</span>')->setActive(route_is('users.index'))
        );
        $menu->addIfCan(
            'view-settings',
            Link::toRoute('settings.index', '<i class="la la-cog"></i> <span>' . __('Settings') . '</span>')->setActive(route_is('settings.index'))
        );
        $menu->add(
            Link::toRoute('assets.index', '<i class="la la-object-ungroup"></i> <span>' . __('Assets') . '</span>')->setActive(route_is('assets.index'))
        );

    }
}
