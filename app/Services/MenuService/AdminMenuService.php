<?php

namespace App\Services\MenuService;

use App\Services\MenuService\AdminMenuItem;
use App\Services\Content\ContentService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AdminMenuService
{
    /**
     * @var AdminMenuItem[][]
     */
    protected array $groups = [];

    /**
     * Add a menu item to the admin sidebar.
     *
     * @param AdminMenuItem|array $item The menu item or configuration array
     * @param string|null $group The group to add the item to
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addMenuItem(AdminMenuItem|array $item, ?string $group = null)
    {
        $group = $group ?: __('Gestion de Inventario');
        $menuItem = $this->createAdminMenuItem($item);
        if (!isset($this->groups[$group])) {
            $this->groups[$group] = [];
        }

        if ($menuItem->userHasPermission()) {
            $this->groups[$group][] = $menuItem;
        }
    }

    protected function createAdminMenuItem(AdminMenuItem|array $data): AdminMenuItem
    {
        if ($data instanceof AdminMenuItem) {
            return $data;
        }

        $menuItem = new AdminMenuItem();

        if (isset($data['children']) && is_array($data['children'])) {
            $data['children'] = array_map(
                function ($child) {
                    // Check if user is authenticated
                    $user = auth()->user();
                    if (!$user) {
                        return null;
                    }

                    // Handle permissions.
                    if (isset($child['permission'])) {
                        $child['permissions'] = $child['permission'];
                        unset($child['permission']);
                    }

                    $permissions = $child['permissions'] ?? [];
                    if (empty($permissions) || $user->hasAnyPermission((array) $permissions)) {
                        return $this->createAdminMenuItem($child);
                    }

                    return null;
                },
                $data['children']
            );

            // Filter out null values (items without permission).
            $data['children'] = array_filter($data['children']);
        }

        // Convert 'permission' to 'permissions' for consistency
        if (isset($data['permission'])) {
            $data['permissions'] = $data['permission'];
            unset($data['permission']);
        }

        // Handle route with params
        if (isset($data['route']) && isset($data['params'])) {
            $routeName = $data['route'];
            $params = $data['params'];

            if (is_array($params)) {
                $data['route'] = route($routeName, $params);
            } else {
                $data['route'] = route($routeName, [$params]);
            }
        }

        return $menuItem->setAttributes($data);
    }

    public function getMenu()
    {
        $this->addMenuItem([
            'label' => __('Panel de Control'),
            'iconClass' => 'bi bi-speedometer2',
            'route' => route('admin.dashboard'),
            'active' => Route::is('admin.dashboard'),
            'id' => 'dashboard',
            'priority' => 1,
            'permissions' => 'dashboard.view'
        ]);

        // Content Management Menu from registered post types
        try {
            $this->registerPostTypesInMenu();
        } catch (\Exception $e) {
            // Skip if there's an error
        }

      

                    $this->addMenuItem([
                        'label' => __('Usuarios'),
                        'iconClass' => 'bi bi-person-fill-gear',
                        'id' => 'users-submenu',
                        'active' => Route::is('admin.users.*'),
                        'priority' => 20,
                        'permissions' => ['user.create', 'user.view', 'user.edit', 'user.delete'],
                        'children' => [
                            [
                                'label' => __('Usuarios'),
                                'route' => route('admin.users.index'),
                                'active' => Route::is('admin.users.index') || Route::is('admin.users.edit'),
                                'priority' => 20,
                                'permissions' => 'user.view'
                            ],
                            [
                                'label' => __('Nuevos Usuarios'),
                                'route' => route('admin.users.create'),
                                'active' => Route::is('admin.users.create'),
                                'priority' => 10,
                                'permissions' => 'user.create'
                            ]
                        ]
                    ], __('Control de usuarios'));

                    $this->addMenuItem([
                        'label' => __('Roles & Permisos'),
                        'iconClass' => 'bi bi-key-fill',
                        'id' => 'roles-submenu',
                        'active' => Route::is('admin.roles.*') || Route::is('admin.permissions.*'),
                        'priority' => 20,
                        'permissions' => ['role.create', 'role.view', 'role.edit', 'role.delete'],
                        'children' => [
                            [
                                'label' => __('Roles'),
                                'route' => route('admin.roles.index'),
                                'active' => Route::is('admin.roles.index') || Route::is('admin.roles.edit'),
                                'priority' => 10,
                                'permissions' => 'role.view'
                            ],
                            [
                                'label' => __('Nuevo Role'),
                                'route' => route('admin.roles.create'),
                                'active' => Route::is('admin.roles.create'),
                                'priority' => 20,
                                'permissions' => 'role.create'
                            ],
                            [
                                'label' => __('Permisos'),
                                'route' => route('admin.permissions.index'),
                                'active' => Route::is('admin.permissions.index') || Route::is('admin.permissions.show'),
                                'priority' => 30,
                                'permissions' => 'role.view'
                            ]
                        ]
                    ], __('Control de usuarios'));

                    $this->addMenuItem([
                            'label' => __('Monitoreo'),
                            'iconClass' => 'bi bi-display',
                            'id' => 'monitoring-submenu',
                            'active' => Route::is('admin.actionlog.*'),
                            'priority' => 40,
                            'permissions' => ['pulse.view', 'actionlog.view'],
                            'children' => [
                                [
                                    'label' => __('Logs'),
                                    'route' => route('admin.actionlog.index'),
                                    'active' => Route::is('admin.actionlog.index'),
                                    'priority' => 20,
                                    'permissions' => 'actionlog.view'
                                ],
                                [
                                    'label' => __('Monitor de sistema'),
                                    'route' => route('pulse'),
                                    'active' => false,
                                    'target' => '_blank',
                                    'priority' => 10,
                                    'permissions' => 'pulse.view'
                                ]
                            ]
                        ], __('Control de usuarios'));

        $this->addMenuItem([
            'label' => __('Inventario'),
            'iconClass' => 'bi bi-archive',
            'id' => 'inventario-submenu',
            'active' => Route::is('admin.inventories.*'),
            'priority' => 40,
            'route' => route('admin.inventories.index'),
            'active' => Route::is('admin.inventories.index'),
            //'permissions' => ['pulse.view', 'actionlog.view'],
           /*  'children' => [
                [
                    'label' => __('Inventario'),
                   
                    'priority' => 20,
                    //'permissions' => 'box.view'
                ],
                
            ] */
        ]);

        $this->addMenuItem([
            'label' => __('Ventas'),
            'iconClass' => 'bi bi-receipt-cutoff',
            'id' => 'sales-submenu',
            'active' => Route::is('admin.sales.*'),
            'priority' => 40,
            'route' => route('admin.sales.index'),
            'active' => Route::is('admin.sales.index'),
            //'permissions' => ['pulse.view', 'actionlog.view'],
           /*  'children' => [
                [
                    'label' => __('Inventario'),
                   
                    'priority' => 20,
                    //'permissions' => 'box.view'
                ],
                
            ] */
        ]);

        $this->addMenuItem([
            'label' => __('Cajas'),
            'iconClass' => 'bi bi-menu-app-fill',
            'id' => 'cajas-submenu',
            'active' => Route::is('admin.box.*'),
            'priority' => 40,
            //'permissions' => ['pulse.view', 'actionlog.view'],
            'children' => [
                [
                    'label' => __('Todas las cajas'),
                    'route' => route('admin.box.index'),
                    'active' => Route::is('admin.box.index'),
                    'priority' => 20,
                    //'permissions' => 'box.view'
                ],
                /* [
                    'label' => __('New Caja'),
                    'route' => route('admin.box.create'),
                    'active' => Route::is('admin.box.create'),
                ] */
            ]
        ]);

        $this->addMenuItem([
            'label' => __('Tipo de monedas'),
            'iconClass' => 'bi bi-cash-coin',
            'id' => 'tipo-monedas-submenu',
            'active' => Route::is('admin.type-coins.*'),
            'priority' => 40,
            //'permissions' => ['pulse.view', 'actionlog.view'],
            'children' => [
                [
                    'label' => __('Todas las monedas'),
                    'route' => route('admin.type-coins.index'),
                    'active' => Route::is('admin.type-coins.index'),
                    'priority' => 20,
                    //'permissions' => 'box.view'
                ],
                /* [
                    'label' => __('New Caja'),
                    'route' => route('admin.box.create'),
                    'active' => Route::is('admin.box.create'),
                ] */
            ]
        ]);

        $this->addMenuItem([
            'label' => __('Proveedores'),
            'iconClass' => 'bi bi-truck',
            'id' => 'clients-submenu',
            'active' => Route::is('admin.suppliers.*'),
            'priority' => 45,
            //'permissions' => ['user.create', 'user.view', 'user.edit', 'user.delete'],
            'children' => [
                [
                    'label' => __('Proveedores'),
                    'route' => route('admin.suppliers.index'),
                    'active' => Route::is('admin.suppliers.index') || Route::is('admin.suppliers.edit'),
                    'priority' => 20,
                    //'permissions' => 'user.view'
                ],
                [
                    'label' => __('Nuevo proveedor'),
                    'route' => route('admin.suppliers.create'),
                    'active' => Route::is('admin.suppliers.create'),
                    'priority' => 10,
                    //'permissions' => 'user.create'
                ]
            ]
        ]);

        $this->addMenuItem([
            'label' => __('Clientes'),
            'iconClass' => 'bi bi-people-fill',
            'id' => 'clients-submenu',
            'active' => Route::is('admin.clients.*'),
            'priority' => 45,
            //'permissions' => ['user.create', 'user.view', 'user.edit', 'user.delete'],
            'children' => [
                [
                    'label' => __('Clientes'),
                    'route' => route('admin.clients.index'),
                    'active' => Route::is('admin.clients.index') || Route::is('admin.clients.edit'),
                    'priority' => 20,
                    //'permissions' => 'user.view'
                ],
                [
                    'label' => __('Nuevo cliente'),
                    'route' => route('admin.clients.create'),
                    'active' => Route::is('admin.clients.create'),
                    'priority' => 10,
                    //'permissions' => 'user.create'
                ]
            ]
        ]);


        $this->addMenuItem([
            'label' => __('Configuraciones'),
            'iconClass' => 'bi bi-gear',
            'id' => 'settings-submenu',
            'active' => Route::is('admin.settings.*') || Route::is('admin.translations.*'),
            'priority' => 1,
            'permissions' => ['settings.edit', 'translations.view'],
            'children' => [
                [
                    'label' => __('Configuración general'),
                    'route' => route('admin.settings.index'),
                    'active' => Route::is('admin.settings.index'),
                    'priority' => 20,
                    'permissions' => 'settings.edit'
                ],
               /*  [
                    'label' => __('Traducciones'),
                    'route' => route('admin.translations.index'),
                    'active' => Route::is('admin.translations.*'),
                    'priority' => 10,
                    'permissions' => ['translations.view', 'translations.edit']
                ] */
            ]
        ], __('Opciones'));
/* 
        $this->addMenuItem([
            'label' => __('Salir'),
            'iconClass' => 'bi bi-box-arrow-left',
            'route' => route('admin.users.logout'),
            'active' => false,
            'id' => 'logout',
            'priority' => 1,

        ], __('Cerrar sesión'));

        $this->groups = ld_apply_filters('admin_menu_groups_before_sorting', $this->groups); */

        $this->sortMenuItemsByPriority();
        return $this->applyFiltersToMenuItems();
    }

    /**
     * Register post types in the menu
     */
    protected function registerPostTypesInMenu(): void
    {
        $contentService = app(ContentService::class);
        $postTypes = $contentService->getPostTypes();

        if ($postTypes->isEmpty()) {
            return;
        }

        /* foreach ($postTypes as $typeName => $type) {
            // Skip if not showing in menu.
            if (isset($type->show_in_menu) && !$type->show_in_menu) {
                continue;
            }

            // Create children menu items.
            $children = [
                [
                    'title' => __("All {$type->label}"),
                    'route' => 'admin.posts.index',
                    'params' => $typeName,
                    'active' => request()->is('admin/posts/' . $typeName) ||
                        (request()->is('admin/posts/' . $typeName . '/*') && !request()->is('admin/posts/' . $typeName . '/create')),
                    'priority' => 10,
                    'permissions' => 'post.view'
                ],
                [
                    'title' => __('Add New'),
                    'route' => 'admin.posts.create',
                    'params' => $typeName,
                    'active' => request()->is('admin/posts/' . $typeName . '/create'),
                    'priority' => 20,
                    'permissions' => 'post.create'
                ]
            ];

            // Add taxonomies as children of this post type if this post type has them.
            if (!empty($type->taxonomies)) {
                $taxonomies = $contentService->getTaxonomies()
                    ->whereIn('name', $type->taxonomies);

                foreach ($taxonomies as $taxonomy) {
                    $children[] = [
                        'title' => __($taxonomy->label),
                        'route' => 'admin.terms.index',
                        'params' => $taxonomy->name,
                        'active' => request()->is('admin/terms/' . $taxonomy->name . '*'),
                        'priority' => 30 + $taxonomy->id, // Prioritize after standard items
                        'permissions' => 'term.view'
                    ];
                }
            }

            // Set up menu item with all children.
            $menuItem = [
                'title' => __($type->label),
                'iconClass' => get_post_type_icon($typeName),
                'id' => 'post-type-' . $typeName,
                'active' => request()->is('admin/posts/' . $typeName . '*') ||
                    (!empty($type->taxonomies) && $this->isCurrentTermBelongsToPostType($type->taxonomies)),
                'priority' => 10,
                'permissions' => 'post.view',
                'children' => $children
            ];

            $this->addMenuItem($menuItem, 'Content');
        } */
    }

    /**
     * Check if the current term route belongs to the given taxonomies
     */
    protected function isCurrentTermBelongsToPostType(array $taxonomies): bool
    {
        if (!request()->is('admin/terms/*')) {
            return false;
        }

        // Get the current taxonomy from the route
        $currentTaxonomy = request()->segment(3); // admin/terms/{taxonomy}

        return in_array($currentTaxonomy, $taxonomies);
    }

    protected function sortMenuItemsByPriority(): void
    {
        foreach ($this->groups as &$groupItems) {
            usort($groupItems, function ($a, $b) {
                return (int) $a->priority <=> (int) $b->priority;
            });
        }
    }

    protected function applyFiltersToMenuItems(): array
    {
        $result = [];
        foreach ($this->groups as $group => $items) {
            // Filter items by permission.
            $filteredItems = array_filter($items, function (AdminMenuItem $item) {
                return $item->userHasPermission();
            });

            // Apply filters that might add/modify menu items.
            $filteredItems = ld_apply_filters('sidebar_menu_' . strtolower($group), $filteredItems);

            // Only add the group if it has items after filtering.
            if (!empty($filteredItems)) {
                $result[$group] = $filteredItems;
            }
        }

        return $result;
    }

    public function shouldExpandSubmenu(AdminMenuItem $menuItem): bool
    {
        // If the parent menu item is active, expand the submenu.
        if ($menuItem->active) {
            return true;
        }

        // Check if any child menu item is active.
        foreach ($menuItem->children as $child) {
            if ($child->active) {
                return true;
            }
        }

        return false;
    }

    public function render(array $groupItems): string
    {
        $html = '';
        foreach ($groupItems as $menuItem) {
            $filterKey = $menuItem->id ?? Str::slug($menuItem->label) ?? '';
            $html .= ld_apply_filters('sidebar_menu_before_' . $filterKey, '');

            $html .= view('backend.layouts.partials.menu-item', [
                'item' => $menuItem,
            ])->render();

            $html .= ld_apply_filters('sidebar_menu_after_' . $filterKey, '');
        }

        return $html;
    }
}
