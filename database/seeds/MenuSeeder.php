<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('left_menus')->insert([
            [
                "id" => 1,
                "label" => '{"en":"Dashboard","es":"Tablero"}',
                'link' => route('admin.home'),
                'parent_id' => 0,
                'slug' => 'dashboard',
                'sort_order' => 1,
                'description' => 'Dashboard - Admin',
                'route' => NULL,
                'icon_font' => 'fas fa-tachometer',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0,
            ],
            [
                "id" => 2,
                "label" => '{"en":"Dashboard","es":"Tablero"}',
                'link' => route('user.home'),
                'parent_id' => 0,
                'slug' => 'dashboard',
                'sort_order' => 1,
                'description' => 'Dashboard - User',
                'route' => NULL,
                'icon_font' => 'fas fa-tachometer',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0,
            ],
            [
                "id" => 3,
                "label" => '{"en":"Dashboard","es":"Tablero"}',
                'link' => route('employee.home'),
                'parent_id' => 0,
                'slug' => 'dashboard',
                'sort_order' => 1,
                'description' => 'Dashboard - Employee',
                'route' => NULL,
                'icon_font' => 'fas fa-tachometer',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0,
            ],
            [
                "id" => 4,
                "label" => '{"en":"Network","es":"Red"}',
                'link' => '#',
                'parent_id' => 0,
                'slug' => 'network',
                'sort_order' => 3,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-share-alt',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0,
            ],
            [
                "id" => 5,
                "label" => '{"en":"New Enrollee","es":"Nuevo miembro"}',
                'link' => route('admin.register'),
                'parent_id' => 4,
                'slug' => 'new-member',
                'sort_order' => 0,
                'description' => 'New member - Admin',
                'route' => NULL,
                'icon_font' => 'fa fa-user-plus',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0,
            ],
            [
                "id" => 7,
                "label" => '{"en":"Member Management","es":"Gesti\u00f3n de miembros"}',
                'link' => route('management.members', ['user' => 2]),
                'parent_id' => 0,
                'slug' => 'member-management',
                'sort_order' => 3,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-cube',
                'icon_image' => NULL,
                'routeParams' => '{"user":"2"}',
                'protected' => 0,
            ],
            [
                "id" => 8,
                "label" => '{"en":"Wallets","es":"Carteras"}',
                'link' => '#',
                'parent_id' => 0,
                'slug' => 'wallets',
                'sort_order' => 10,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fas fa-wallet',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0,
            ],
            [
                "id" => 9,
                "label" => '{"en":"E-mail","es":"E-mail"}',
                'link' => route('admin.mail'),
                'parent_id' => 0,
                'slug' => 'e-mail',
                'sort_order' => 6,
                'description' => 'E-mail - Admin',
                'route' => NULL,
                'icon_font' => 'fas fa-envelope-open-text',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 10,
                "label" => '{"en":"E-mail","es":"E-mail"}',
                'link' => route('user.mail'),
                'parent_id' => 0,
                'slug' => 'e-mail',
                'sort_order' => 6,
                'description' => 'E-mail - User',
                'route' => NULL,
                'icon_font' => 'fas fa-envelope-open-text',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 11,
                "label" => '{"en":"Settings","es":"Configuraciones"}',
                'link' => '#',
                'parent_id' => 0,
                'slug' => 'settings',
                'sort_order' => 6,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-gear',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 12,
                "label" => '{"en":"System","es":"Sistema"}',
                'link' => route('global.config'),
                'parent_id' => 11,
                'slug' => 'system',
                'sort_order' => 0,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-gears',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 13,
                "label" => '{"en":"Modules","es":"M\u00f3dulos"}',
                'link' => route('admin.module'),
                'parent_id' => 11,
                'slug' => 'modules',
                'sort_order' => 1,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-cubes',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 14,
                "label" => '{"en":"Themes","es":"Temas"}',
                'link' => route('admin.theme'),
                'parent_id' => 11,
                'slug' => 'themes',
                'sort_order' => 2,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-television',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 15,
                "label" => '{"en":"Menus","es":"Men\u00fas"}',
                'link' => route('admin.menu'),
                'parent_id' => 11,
                'slug' => 'menus',
                'sort_order' => 3,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-bars',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 16,
                "label" => '{"en":"Packages","es":"Paquetes"}',
                'link' => route('package'),
                'parent_id' => 11,
                'slug' => 'packages',
                'sort_order' => 4,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-dropbox',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 17,
                "label" => '{"en":"Custom Fields","es":"Campos Personalizados"}',
                'link' => route('config.fields'),
                'parent_id' => 11,
                'slug' => 'custom-fields',
                'sort_order' => 5,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-check-square',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 19,
                "label" => '{"en":"Cron Jobs","es":"Cron Jobs"}',
                'link' => route('cron'),
                'parent_id' => 11,
                'slug' => 'cron-jobs',
                'sort_order' => 7,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-calendar',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 20,
                "label" => '{"en":"Reports","es":"Informes"}',
                'link' => '#',
                'parent_id' => 0,
                'slug' => 'reports',
                'sort_order' => 5,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fas fa-file-chart-line',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 21,
                "label" => '{"en":"Tools","es":"Herramientas"}',
                'link' => '#',
                'parent_id' => 0,
                'slug' => 'tools',
                'sort_order' => 4,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fas fa-wrench',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 22,
                "label" => '{"en":"Push Notification","es":"Notificación de inserción"}',
                'link' => route('admin.notification.send.index'),
                'parent_id' => 21,
                'slug' => 'tools',
                'sort_order' => 8,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-bell',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],
            [
                "id" => 23,
                "label" => '{"en":"Marketing","es":"M\u00e1rketing"}',
                'link' => '#',
                'parent_id' => 0,
                'slug' => 'marketing',
                'sort_order' => 9,
                'description' => NULL,
                'route' => NULL,
                'icon_font' => 'fa fa-bolt',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0
            ],

            [
                "id" => 25,
                "label" => '{"en":"My profile","es":"My profile"}',
                'link' => route('user.profile'),
                'parent_id' => 0,
                'slug' => 'my-profile',
                'sort_order' => 2,
                'description' => 'My profile - User',
                'route' => NULL,
                'icon_font' => 'fas fa-user-circle',
                'icon_image' => NULL,
                'routeParams' => NULL,
                'protected' => 0,
            ],
        ]);
    }
}