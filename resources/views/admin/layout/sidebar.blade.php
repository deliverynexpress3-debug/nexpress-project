<div class="sidebar">
    <div class="side-head">
        <a href="{{ route('admin.dashboard') }}" class="side-logo" style="text-decoration: none;">
            <h3 style="font-weight: bold; margin: 0;" class="primary-color">{{ App_Name() }}</h3>
        </a>

        <button class="btn side-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <ul class="side-menu mt-4">

        {{-- ============================================================
             1. DASHBOARD
        ============================================================ --}}
        <li
            class="side_line {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}{{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-house menu-icon"></i>
                <span>{{ __('label.dashboard') }}</span>
            </a>
        </li>

        {{-- ============================================================
             2. CATALOG  (Basic items + Product control + Products)
        ============================================================ --}}
        <p class="partition"><span>{{ __('label.basic_controls') }}</span></p>

        <li
            class="dropdown {{ request()->routeIs('admin.type*') ? 'active' : '' }} {{ request()->routeIs('admin.category.*') ? 'active' : '' }}{{ request()->routeIs('admin.subcategory_list') ? 'active' : '' }}{{ request()->routeIs('admin.brand.*') ? 'active' : '' }}{{ request()->routeIs('admin.article.*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-layer-group menu-icon"></i>
                <span>{{ __('label.basic_items') }}</span>
            </a>
            <ul
                class="dropdown-menu side-submenu {{ request()->routeIs('admin.type*') ? 'show' : '' }} {{ request()->routeIs('admin.category.*') ? 'show' : '' }}{{ request()->routeIs('admin.subcategory_list') ? 'show' : '' }}{{ request()->routeIs('admin.brand.*') ? 'show' : '' }} {{ request()->routeIs('admin.article.*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('admin.type.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.type.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.types') }}</span>
                    </a>
                </li>
                <li
                    class="side_line {{ request()->routeIs('admin.category*') ? 'active' : '' }}{{ request()->routeIs('admin.subcategory_list') ? 'active' : '' }}">
                    <a href="{{ route('admin.category.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.category') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.brand*') ? 'active' : '' }}">
                    <a href="{{ route('admin.brand.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.brand') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.article*') ? 'active' : '' }}">
                    <a href="{{ route('admin.article.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.article') }}</span>
                    </a>
                </li>
            </ul>
        </li>

        <li
            class="dropdown {{ request()->routeIs('admin.hashtag*') ? 'active' : '' }} {{ request()->routeIs('admin.tax*') ? 'active' : '' }} {{ request()->routeIs('admin.stockstatus*') ? 'active' : '' }} {{ request()->routeIs('admin.attributes*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-tags menu-icon"></i>
                <span>{{ __('label.product_control') }}</span>
            </a>
            <ul
                class="dropdown-menu side-submenu {{ request()->routeIs('admin.hashtag*') ? 'show' : '' }} {{ request()->routeIs('admin.tax*') ? 'show' : '' }} {{ request()->routeIs('admin.stockstatus*') ? 'show' : '' }}{{ request()->routeIs('admin.attributes*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('admin.hashtag*') ? 'active' : '' }}">
                    <a href="{{ route('admin.hashtag.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.tag') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.tax*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tax.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.tax') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.stockstatus*') ? 'active' : '' }}">
                    <a href="{{ route('admin.stockstatus.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.stock_status') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.attributes*') ? 'active' : '' }}">
                    <a href="{{ route('admin.attributes.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.attributes') }}</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="side_line {{ request()->routeIs('admin.item*') ? 'active' : '' }}">
            <a href="{{ route('admin.item.index') }}">
                <i class="fa-solid fa-cubes menu-icon"></i>
                <span>{{ __('label.product') }}</span>
            </a>
        </li>

        {{-- ============================================================
             3. SALES  (Orders + Delivery assignments + Offers)
        ============================================================ --}}
        <p class="partition"><span>{{ __('label.order_manage') }}</span></p>

        <li class="side_line {{ request()->routeIs('admin.order*') ? 'active' : '' }}">
            <a href="{{ route('admin.order.index') }}">
                <i class="fa-solid fa-cart-shopping menu-icon"></i>
                <span>{{ __('label.orders') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.delivery-assignment*') ? 'active' : '' }}">
            <a href="{{ route('admin.delivery-assignment.index') }}">
                <i class="fa-solid fa-truck menu-icon"></i>
                <span>{{ __('label.delivery_assignments') }}</span>
            </a>
        </li>
        <li
            class="dropdown {{ request()->routeIs('admin.reason*') ? 'active' : '' }}{{ request()->routeIs('admin.deliveryinstructor*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-clipboard-list menu-icon"></i>
                <span>{{ __('label.order_control') }}</span>
            </a>
            <ul
                class="dropdown-menu side-submenu {{ request()->routeIs('admin.reason*') ? 'show' : '' }}{{ request()->routeIs('admin.deliveryinstructor*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('admin.reason*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reason.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.return_reason') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.deliveryinstructor*') ? 'active' : '' }}">
                    <a href="{{ route('admin.deliveryinstructor.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right submenu-icon"></i>
                        <span>{{ __('label.delivery_instructor') }}</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="side_line {{ request()->routeIs('admin.offers*') ? 'active' : '' }}">
            <a href="{{ route('admin.offers.index') }}">
                <i class="fa-solid fa-gift menu-icon"></i>
                <span>{{ __('label.offers') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.promocode*') ? 'active' : '' }}">
            <a href="{{ route('admin.promocode.index') }}">
                <i class="fa-solid fa-ticket menu-icon"></i>
                <span>{{ __('label.promocode') }}</span>
            </a>
        </li>

        {{-- ============================================================
             4. USERS  (Customers + Sellers + Delivery boys)
        ============================================================ --}}
        <p class="partition"><span>{{ __('label.user_seller') }}</span></p>

        <li class="side_line {{ request()->routeIs('admin.customer*') ? 'active' : '' }}">
            <a href="{{ route('admin.customer.index') }}">
                <i class="fa-solid fa-users menu-icon"></i>
                <span>{{ __('label.users') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.seller.*') ? 'active' : '' }}">
            <a href="{{ route('admin.seller.index') }}">
                <i class="fa-solid fa-store menu-icon"></i>
                <span>{{ __('label.seller') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.deliveryboy*') ? 'active' : '' }}">
            <a href="{{ route('admin.deliveryboy.index') }}">
                <i class="fa-solid fa-motorcycle menu-icon"></i>
                <span>{{ __('label.delivery_boys') }}</span>
            </a>
        </li>

        {{-- ============================================================
             5. STOREFRONT  (Sliders + Sections + Pages)
        ============================================================ --}}
        <p class="partition"><span>{{ __('label.configuration') }}</span></p>

        <li class="side_line {{ request()->routeIs('admin.slider*') ? 'active' : '' }}">
            <a href="{{ route('admin.slider.index') }}">
                <i class="fa-solid fa-images menu-icon"></i>
                <span>{{ __('label.sliders') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.section*') ? 'active' : '' }}">
            <a href="{{ route('admin.section.index') }}">
                <i class="fa-solid fa-bars-staggered menu-icon"></i>
                <span>{{ __('label.section') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
            <a href="{{ route('admin.pages.index') }}">
                <i class="fa-solid fa-file-lines menu-icon"></i>
                <span>{{ __('label.pages') }}</span>
            </a>
        </li>

        {{-- ============================================================
             6. ENGAGEMENT  (Reviews + Notifications)
        ============================================================ --}}
        <p class="partition"><span>{{ __('label.interaction') }}</span></p>

        <li class="side_line {{ (request()->routeIs('admin.reviews*')) ? 'active' : '' }}">
            <a href="{{ route('admin.reviews.index') }}">
                <i class="fa-solid fa-comments menu-icon"></i>
                <span>{{__('label.reviews')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.notification.*') && !request()->routeIs('admin.notification.setting') ? 'active' : '' }}">
            <a href="{{ route('admin.notification.index') }}">
                <i class="fa-solid fa-bell menu-icon"></i>
                <span>{{ __('label.notification') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.daily_notification*') ? 'active' : '' }}">
            <a href="{{ route('admin.daily_notification.index') }}">
                <i class="fa-solid fa-clock menu-icon"></i>
                <span>{{ __('label.daily_notification') }}</span>
            </a>
        </li>

        {{-- ============================================================
             7. FINANCIAL  (Settlement + Withdrawal + Payment)
        ============================================================ --}}
        <p class="partition"><span>{{ __('label.financial') }}</span></p>

        <li class="side_line {{ request()->routeIs('admin.settlement*') ? 'active' : '' }}">
            <a href="{{ route('admin.settlement.index') }}">
                <i class="fa-solid fa-hand-holding-dollar menu-icon"></i>
                <span>{{ __('label.settlement') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.withdrawal*') ? 'active' : '' }}">
            <a href="{{ route('admin.withdrawal.index') }}">
                <i class="fa-solid fa-right-left menu-icon"></i>
                <span>{{ __('label.withdrawal_request') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.payment*') ? 'active' : '' }}">
            <a href="{{ route('admin.payment.index') }}">
                <i class="fa-solid fa-money-bill-wave menu-icon"></i>
                <span>{{ __('label.payment_settings') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.sales-report-item*') ? 'active' : '' }}">
            <a href="{{ route('admin.sales-report-item.index') }}">
                <i class="fa-solid fa-box menu-icon"></i>
                <span>{{ __('label.product_sales') }}</span>
            </a>
        </li>

        {{-- ============================================================
             8. REPORTS & ANALYTICS
        ============================================================ --}}
        <p class="partition"><span>{{ __('label.reports_analytics') }}</span></p>

        <li class="dropdown {{ request()->routeIs('admin.earning.report') ? 'active' : '' }} {{ request()->routeIs('admin.sales.report') ? 'active' : '' }}{{ request()->routeIs('admin.report.sellers') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-chart-column menu-icon"></i>
                <span>{{ __('label.reports') }}</span>
            </a>
            <ul
                class="dropdown-menu side-submenu {{ request()->routeIs('admin.earning.report') ? 'show' : '' }} {{ request()->routeIs('admin.sales.report') ? 'show' : '' }} {{ request()->routeIs('admin.report.sellers') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('admin.sales.report') ? 'active' : '' }}">
                    <a href="{{ route('admin.sales.report') }}" class="dropdown-item">
                        <i class="fa-solid fa-money-bill-trend-up submenu-icon"></i>
                        <span>{{ __('label.sales_reports') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.report.sellers') ? 'active' : '' }}">
                    <a href="{{ route('admin.report.sellers') }}" class="dropdown-item">
                        <i class="fa-solid fa-store submenu-icon"></i>
                        <span>{{ __('label.seller_reports') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.earning.report') ? 'active' : '' }}">
                    <a href="{{ route('admin.earning.report') }}" class="dropdown-item">
                        <i class="fa-solid fa-money-bill-transfer submenu-icon"></i>
                        <span>{{ __('label.earning_report') }}</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- ============================================================
             9. SETTINGS
        ============================================================ --}}
        <p class="partition"><span>{{ __('label.settings') }}</span></p>

        <li class="side_line {{ request()->routeIs('admin.appsetting*') ? 'active' : '' }}">
            <a href="{{ route('admin.appsetting.index') }}">
                <i class="fa-solid fa-gear menu-icon"></i>
                <span>{{ __('label.app_settings') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.panelsetting*') ? 'active' : '' }}">
            <a href="{{ route('admin.panelsetting.index') }}">
                <i class="fa-solid fa-palette menu-icon"></i>
                <span>{{ __('label.panel_settings') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.system.setting*') ? 'active' : '' }}">
            <a href="{{ route('admin.system.setting.index') }}">
                <i class="fa-solid fa-screwdriver-wrench menu-icon"></i>
                <span>{{ __('label.system_settings') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.integration-notification*') ? 'active' : '' }}">
            <a href="{{ route('admin.integration-notification.index') }}">
                <i class="fa-solid fa-bell-concierge menu-icon"></i>
                <span>{{ __('label.notification_setting') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.notification.setting') ? 'active' : '' }}">
            <a href="{{ route('admin.notification.setting') }}">
                <i class="fa-solid fa-tower-broadcast menu-icon"></i>
                <span>{{ __('label.onesignal') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.admob*') ? 'active' : '' }}">
            <a href="{{ route('admin.admob.index') }}">
                <i class="fa-brands fa-square-google-plus menu-icon"></i>
                <span>{{ __('label.admob') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.fbads*') ? 'active' : '' }}">
            <a href="{{ route('admin.fbads.index') }}">
                <i class="fa-brands fa-square-facebook menu-icon"></i>
                <span>{{ __('label.facebook_ads') }}</span>
            </a>
        </li>

        {{-- ============================================================
             10. LOGOUT
        ============================================================ --}}
        <p class="partition"><span>{{ __('label.logout') }}</span></p>
        <li>
            <a href="{{ route('admin.logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket menu-icon"></i>
                <span>{{ __('label.logout') }}</span>
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="GET" class="d-none">
                @csrf
            </form>
        </li>

    </ul>

</div>
