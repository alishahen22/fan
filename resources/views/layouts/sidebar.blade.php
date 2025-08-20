@php
use App\Helpers\PermissionHelper;
@endphp

<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="javascript:void(0);" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
{{--                <h1 style="color: white; font-weight: bolder">@lang('Dashboard')</h1>--}}
            </span>
        </a>
        <!-- Light Logo-->
        <a href="javascript:void(0);" class="logo logo-light">
            <span class="logo-sm">
                <img style="height: 50px; width: 50px;" src="{{ URL::asset('build/images/side_bar_logo_sm.png') }}"
                     alt="" height="22">
            </span>
            <span class="logo-lg">
                <img style="height: 90px; width: 180px;" src="{{ URL::asset('build/images/side_bar_logo.png') }}" alt=""
                     height="37">
{{--                <h1 style="color: white; font-weight: bolder;line-height: 60px;">@lang('FANN')</h1>--}}
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('Dashboard')</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('root') ? 'active' : '' }}" href="/">
                        <i class="ri-dashboard-2-line"></i> <span>@lang('Statistics')</span>
                    </a>
                </li>
                {{--                @permission('orders_list')--}}

                {{--                @endpermission--}}
                {{--                @permission('users_list')--}}

                @permission('users_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('users.*') ? 'active' : '' }}"
                       href="{{ route('users.index') }}">
                        <i class="ri-team-line"></i> <span>@lang('Users')</span>
                    </a>
                </li>
                @endpermission
                {{--                @endpermission--}}
                @php
                    $new_orders = \App\Models\Order::where('status','pending')->count();
                    $new_direct_orders = \App\Models\DirectOrder::where('seen_at',null)->count();
                    $new_get_prices = \App\Models\GetPrice::where('seen_at',null)->count();
                    $un_seen_messages = \App\Models\Contact::where('seen_at',null)->count();
                @endphp

                @permission('orders_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                        <i class="ri-shopping-cart-2-line"></i> <span>الطلبات</span>
                        @if($new_orders > 0)
                            <span class="badge badge-pill bg-danger"
                                  data-key="t-hot">{{$new_orders}}</span>
                        @endif
                    </a>
                </li>
                @endpermission

                @permission('direct_orders_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('direct_orders.*') ? 'active' : '' }}" href="{{ route('direct_orders.index') }}">
                        <i class="ri-shopping-cart-2-line"></i> <span>الطلبات المباشرة</span>
                        @if($new_direct_orders > 0)
                            <span class="badge badge-pill bg-danger"
                                  data-key="t-hot">{{$new_direct_orders}}</span>
                        @endif
                    </a>
                </li>
                @endpermission

                @permission('get_prices_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('get_prices.*') ? 'active' : '' }}" href="{{ route('get_prices.index') }}">
                        <i class="ri-shopping-cart-2-line"></i> <span>طلبات الحصول على تسعيره</span>
                        @if($new_get_prices > 0)
                            <span class="badge badge-pill bg-danger"
                                  data-key="t-hot">{{$new_get_prices}}</span>
                        @endif
                    </a>
                </li>
                @endpermission

                @permission('notifications_create')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('notifications.*') ? 'active' : '' }}"
                       href="{{ route('notifications.renderNotification') }}">
                        <i class="ri-notification-4-fill"></i> <span>@lang('Send Notifications')</span>
                    </a>
                </li>
                @endpermission
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">التجهيزات</span></li>

                @permission('products_list|categories_list|attributes_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('products.*') || Request::routeIs('categories.*') || Request::routeIs('attributes.*') ? 'active' : '' }}"
                    href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::routeIs('products.*') || Request::routeIs('categories.*') || Request::routeIs('attributes.*') }}"
                    aria-controls="sidebarProducts">
                        <i class="ri-store-2-line"></i> <span>@lang('إدارة المنتجات')</span>
                    </a>
                    <div class="menu-dropdown collapse {{ Request::routeIs('products.*') || Request::routeIs('categories.*') || Request::routeIs('attributes.*') ? 'show' : '' }}"
                        id="sidebarProducts">
                        <ul class="nav nav-sm flex-column">
                            @permission('categories_list')
                            <li class="nav-item">
                                <a href="{{ route('categories.index') }}" class="nav-link {{ Request::routeIs('categories.*') ? 'active' : '' }}">
                                    <i class="ri-list-check"></i> @lang('Categories')
                                </a>
                            </li>
                            @endpermission
                            @permission('attributes_list')
                            <li class="nav-item">
                                <a href="{{ route('attributes.index') }}" class="nav-link {{ Request::routeIs('attributes.*') ? 'active' : '' }}">
                                    <i class="ri-list-check"></i> @lang('attributes')
                                </a>
                            </li>
                            @endpermission
                            @permission('products_list')
                            <li class="nav-item">
                                <a href="{{ route('products.index') }}" class="nav-link {{ Request::routeIs('products.*') ? 'active' : '' }}">
                                    <i class="ri-list-check"></i> @lang('Products')
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                @endpermission

                @permission('sliders_list|banners_list|steps_list|reviews_list|offers_list|splashes_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('sliders.*') || Request::routeIs('banners.*') || Request::routeIs('steps.*') || Request::routeIs('reviews.*') || Request::routeIs('offers.*') || Request::routeIs('splashes.*') ? 'active' : '' }}"
                    href="#sidebarContent" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::routeIs('sliders.*') || Request::routeIs('banners.*') || Request::routeIs('steps.*') || Request::routeIs('reviews.*') || Request::routeIs('offers.*') || Request::routeIs('splashes.*') }}"
                    aria-controls="sidebarContent">
                        <i class="ri-image-2-line"></i> <span>@lang('إدارة المحتوى')</span>
                    </a>
                    <div class="menu-dropdown collapse {{ Request::routeIs('sliders.*') || Request::routeIs('banners.*') || Request::routeIs('steps.*') || Request::routeIs('reviews.*') || Request::routeIs('offers.*') || Request::routeIs('splashes.*') ? 'show' : '' }}"
                        id="sidebarContent">
                        <ul class="nav nav-sm flex-column">
                            @permission('sliders_list')
                            <li class="nav-item">
                                <a href="{{ route('sliders.index') }}" class="nav-link {{ Request::routeIs('sliders.*') ? 'active' : '' }}">
                                    @lang('Sliders')
                                </a>
                            </li>
                            @endpermission
                            @permission('banners_list')
                            <li class="nav-item">
                                <a href="{{ route('banners.index') }}" class="nav-link {{ Request::routeIs('banners.*') ? 'active' : '' }}">
                                    @lang('banners')
                                </a>
                            </li>
                            @endpermission
                            @permission('steps_list')
                            <li class="nav-item">
                                <a href="{{ route('steps.index') }}" class="nav-link {{ Request::routeIs('steps.*') ? 'active' : '' }}">
                                    @lang('steps')
                                </a>
                            </li>
                            @endpermission
                            @permission('reviews_list')
                            <li class="nav-item">
                                <a href="{{ route('reviews.index') }}" class="nav-link {{ Request::routeIs('reviews.*') ? 'active' : '' }}">
                                    @lang('reviews')
                                </a>
                            </li>
                            @endpermission
                            @permission('offers_list')
                            <li class="nav-item">
                                <a href="{{ route('offers.index') }}" class="nav-link {{ Request::routeIs('offers.*') ? 'active' : '' }}">
                                    @lang('offers')
                                </a>
                            </li>
                            @endpermission
                            @permission('splashes_list')
                            <li class="nav-item">
                                <a href="{{ route('splashes.index') }}" class="nav-link {{ Request::routeIs('splashes.*') ? 'active' : '' }}">
                                    @lang('splashes')
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                @endpermission

                @permission('cities_list|vouchers_list|contacts_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('cities.*') || Request::routeIs('vouchers.*') || Request::routeIs('contacts.*') ? 'active' : '' }}"
                    href="#sidebarServices" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::routeIs('cities.*') || Request::routeIs('vouchers.*') || Request::routeIs('contacts.*') }}"
                    aria-controls="sidebarServices">
                        <i class="ri-customer-service-2-line"></i> <span>@lang('الخدمات والتسويق')</span>
                    </a>
                    <div class="menu-dropdown collapse {{ Request::routeIs('cities.*') || Request::routeIs('vouchers.*') || Request::routeIs('contacts.*') ? 'show' : '' }}"
                        id="sidebarServices">
                        <ul class="nav nav-sm flex-column">
                            @permission('cities_list')
                            <li class="nav-item">
                                <a href="{{ route('cities.index') }}" class="nav-link {{ Request::routeIs('cities.*') ? 'active' : '' }}">
                                    <i class="ri-map-pin-line"></i> @lang('cities')
                                </a>
                            </li>
                            @endpermission
                            @permission('vouchers_list')
                            <li class="nav-item">
                                <a href="{{ route('vouchers.index') }}" class="nav-link {{ Request::routeIs('vouchers.*') ? 'active' : '' }}">
                                    <i class="ri-price-tag-3-line"></i> @lang('Vouchers')
                                </a>
                            </li>
                            @endpermission
                            @permission('contacts_list')
                            <li class="nav-item">
                                <a href="{{ route('contacts.index') }}" class="nav-link {{ Request::routeIs('contacts.*') ? 'active' : '' }}">
                                    <i class="ri-message-2-line"></i> التواصل معنا
                                    @if($un_seen_messages > 0)
                                    <span class="badge badge-pill bg-danger" data-key="t-hot">{{$un_seen_messages}}</span>
                                    @endif
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                @endpermission

                {{--  --}}
                @permission('items_list|supplies_list|print_services_list|quotations_list|packages_list')
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::routeIs('items.*') || Request::routeIs('supplies.*') || Request::routeIs('print-services.*') || Request::routeIs('quotations.*') || Request::routeIs('invoices.*') || Request::routeIs('packages.*') || Request::routeIs('printSettings.*') ? 'active' : '' }}"
                href="#sidebarStaticPages" data-bs-toggle="collapse" role="button"
                aria-expanded="{{ Request::routeIs('items.*') || Request::routeIs('supplies.*') || Request::routeIs('print-services.*') || Request::routeIs('quotations.*') || Request::routeIs('invoices.*') || Request::routeIs('packages.*') || Request::routeIs('printSettings.*') }}"
                aria-controls="sidebarStaticPages">
                    <i class="ri-pages-line"></i> <span>@lang('قائمة التسعير')</span>
                </a>
                <div class="menu-dropdown collapse {{ Request::routeIs('items.*') || Request::routeIs('supplies.*') || Request::routeIs('print-services.*') || Request::routeIs('quotations.*') || Request::routeIs('invoices.*') || Request::routeIs('packages.*') || Request::routeIs('printSettings.*') ? 'show' : '' }}"
                    id="sidebarStaticPages">
                    <ul class="nav nav-sm flex-column">
                        @permission('items_list')
                        <li class="nav-item">
                            <a href="{{ route('items.index') }}" class="nav-link {{ Request::routeIs('items.*') ? 'active' : '' }}">
                                @lang('تكويد المواد الخام')
                            </a>
                        </li>
                        @endpermission
                        @permission('supplies_list')
                        <li class="nav-item">
                            <a href="{{ route('supplies.index') }}" class="nav-link {{ Request::routeIs('supplies.*') ? 'active' : '' }}">
                                @lang('تكويد المستلزمات')
                            </a>
                        </li>
                        @endpermission
                        @permission('print_services_list')
                        <li class="nav-item">
                            <a href="{{ route('print-services.index') }}" class="nav-link {{ Request::routeIs('print-services.*') ? 'active' : '' }}">
                                @lang('تكويد اصناف')
                            </a>
                        </li>
                        @endpermission
                        @permission('quotations_list')
                        <li class="nav-item">
                            <a href="{{ route('quotations.index') }}" class="nav-link {{ Request::routeIs('quotations.*') ? 'active' : '' }}">
                                @lang('عرض سعر')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('invoices.index') }}" class="nav-link {{ Request::routeIs('invoices.*') ? 'active' : '' }}">
                                @lang('الفاتورة')
                            </a>
                        </li>
                        @endpermission

                        @permission('packages_list')
                        <li class="nav-item">
                            <a href="{{ route('packages.index') }}" class="nav-link {{ Request::routeIs('packages.*') ? 'active' : '' }}">
                                @lang('الباقات')
                            </a>
                        </li>
                        @endpermission
                        @permission('print_settings_view')
                        <li class="nav-item">
                            <a href="{{ route('printSettings.edit') }}" class="nav-link {{ Request::routeIs('printSettings.*') ? 'active' : '' }}">
                                @lang('إعدادات الطباعة')
                            </a>
                        </li>
                        @endpermission
                    </ul>
                </div>
            </li>
                @endpermission

                @permission('pages_edit|roles_list|admins_list|point_settings_list|settings_view')
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">الإعدادات</span></li>
                @endpermission

                @permission('pages_edit')
                @php
                    $pages = \App\Models\Page::all();
                @endphp
                @if($pages && $pages->isNotEmpty())
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::routeIs('pages.*') ? 'active' : '' }}"
                           href="#sidebarAdminPages" data-bs-toggle="collapse" role="button"
                           aria-expanded="{{ Request::routeIs('pages.*') }}" aria-controls="sidebarAdminPages">
                            <i class="ri-pages-line"></i> <span>@lang('translation.pages')</span>
                        </a>
                        <div class="menu-dropdown collapse {{ Request::routeIs('pages.*') ? 'show' : '' }}"
                             id="sidebarAdminPages">
                            <ul class="nav nav-sm flex-column">
                                @foreach($pages as $page)
                                    <li class="nav-item">
                                        <a href="{{ route('pages.edit',$page->type) }}"
                                           class="nav-link {{ request()->segment(3) == $page->type ? 'active' : '' }}">@lang('translation.'.$page->type)</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li> <!-- end Dashboard Menu -->
                @endif
                @endpermission

                @permission('roles_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <i class="ri-lock-2-fill"></i> <span>@lang('Roles & Permissions')</span>
                    </a>
                </li>
                @endpermission

                @permission('admins_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('admins.*') ? 'active' : '' }}"
                       href="{{ route('admins.index') }}">
                        <i class="ri-user-2-fill"></i> <span>@lang('Admins')</span>
                    </a>
                </li>
                @endpermission

                @permission('point_settings_list')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('point_settings.*') ? 'active' : '' }}"
                       href="{{ route('point_settings.create') }}">
                        <i class="ri-medal-fill"></i> <span>إعدادات النقاط</span>
                    </a>
                </li>
                @endpermission

                @permission('settings_view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('settings.*') ? 'active' : '' }}"
                       href="{{ route('settings.edit') }}">
                        <i class="ri-settings-2-fill"></i> <span>@lang('translation.settings')</span>
                        </a>
                    </li>
                @endpermission

            </ul>
            <br>
            <br>
            <br>
            <br>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>


