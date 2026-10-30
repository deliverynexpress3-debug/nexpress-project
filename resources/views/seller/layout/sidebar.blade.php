<div class="sidebar">
    <div class="side-head">
        <a href="{{ route('seller.dashboard') }}" class="primary-color side-logo">
            <h3>{{ App_Name() }}</h3>
        </a>
        <button class="btn side-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <ul class="side-menu mt-4">
        <li
            class="side_line {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}{{ request()->routeIs('seller.profile*') ? 'active' : '' }}">
            <a href="{{ route('seller.dashboard') }}">
                <i class="fa-solid fa-house fa-2xl menu-icon"></i>
                <span>{{ __('label.dashboard') }}</span>
            </a>
        </li>
        <p class="partition"><span>{{ __('label.basic_controls') }}</span></p>
        <li
            class="side_line {{ request()->routeIs('seller.category*') ? 'active' : '' }} {{ request()->routeIs('seller.subcategory_list*') ? 'active' : '' }}">
            <a href="{{ route('seller.category.index') }}">
                <i class="fa-solid fa-shapes fa-2xl menu-icon"></i>
                <span>{{ __('label.category') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('seller.brand*') ? 'active' : '' }}">
            <a href="{{ route('seller.brand.index') }}">
                <i class="fa-solid fa-briefcase fa-2xl menu-icon"></i>
                <span>{{ __('label.brand') }}</span>
            </a>
        </li>
        <p class="partition"><span>{{ __('label.product_manage') }}</span></p>
        <li
            class="dropdown {{ request()->routeIs('seller.hashtag*') ? 'active' : '' }} {{ request()->routeIs('seller.tax*') ? 'active' : '' }} {{ request()->routeIs('seller.stockstatus*') ? 'active' : '' }} {{ request()->routeIs('seller.attributes*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-trowel-bricks fa-2xl menu-icon"></i>
                <span>{{ __('label.product_control') }}</span>
            </a>
            <ul
                class="dropdown-menu side-submenu {{ request()->routeIs('seller.hashtag*') ? 'show' : '' }} {{ request()->routeIs('seller.tax*') ? 'show' : '' }} {{ request()->routeIs('seller.stockstatus*') ? 'show' : '' }}{{ request()->routeIs('seller.attributes*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('seller.hashtag*') ? 'active' : '' }}">
                    <a href="{{ route('seller.hashtag.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right fa-xl submenu-icon"></i>
                        <span>{{ __('label.tag') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('seller.tax*') ? 'active' : '' }}">
                    <a href="{{ route('seller.tax.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right fa-xl submenu-icon"></i>
                        <span>{{ __('label.tax') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('seller.stockstatus*') ? 'active' : '' }}">
                    <a href="{{ route('seller.stockstatus.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right fa-xl submenu-icon"></i>
                        <span>{{ __('label.stock_status') }}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('seller.attributes*') ? 'active' : '' }}">
                    <a href="{{ route('seller.attributes.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-caret-right fa-xl submenu-icon"></i>
                        <span>{{ __('label.attributes') }}</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="side_line {{ request()->routeIs('seller.item*') ? 'active' : '' }}">
            <a href="{{ route('seller.item.index') }}">
                <i class="fa-solid fa-cart-plus fa-2xl menu-icon"></i>
                <span>{{ __('label.product') }}</span>
            </a>
        </li>
        @if (Setting_Data()['offer_access'] == 1)
            <p class="partition"><span>{{ __('label.offers') }}</span></p>
            <li class="side_line {{ request()->routeIs('seller.offers*') ? 'active' : '' }}">
                <a href="{{ route('seller.offers.index') }}">
                    <i class="fa-solid fa-gift fa-2xl menu-icon"></i>
                    <span>{{ __('label.offers') }}</span>
                </a>
            </li>
            <li class="side_line {{ request()->routeIs('seller.promocode*') ? 'active' : '' }}">
                <a href="{{ route('seller.promocode.index') }}">
                    <i class="fa-solid fa-ticket fa-2xl menu-icon"></i>
                    <span>{{ __('label.promocode') }}</span>
                </a>
            </li>
        @endif
        <p class="partition"><span>{{ __('label.order_manage') }}</span></p>
        <li class="side_line {{ request()->routeIs('seller.order*') ? 'active' : '' }}">
            <a href="{{ route('seller.order.index') }}">
                <i class="fa-solid fa-cart-shopping fa-2xl menu-icon"></i>
                <span>{{ __('label.orders') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('seller.return-request*') ? 'active' : '' }}">
            <a href="{{ route('seller.return-request.index') }}">
                <i class="fa-solid fa-clock-rotate-left fa-2xl menu-icon"></i>
                <span>{{ __('label.return_request') }}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('seller.cancel-request*') ? 'active' : '' }}">
            <a href="{{ route('seller.cancel-request.index') }}">
                <i class="fa-solid fa-times-circle fa-2xl menu-icon"></i>
                <span>{{ __('label.cancel_request') }}</span>
            </a>
        </li>
        <p class="partition"><span>{{ __('label.sales_reports') }}</span></p>
        <li class="side_line {{ request()->routeIs('seller.sales-report*') ? 'active' : '' }}">
            <a href="{{ route('seller.sales-report.index') }}">
                <i class="fa-solid fa-chart-line fa-2xl menu-icon"></i>
                <span>{{ __('label.sales_reports') }}</span>
            </a>
        </li>
        <p class="partition"><span>{{ __('label.reviews') }}</span></p>
        <li class="side_line {{ request()->routeIs('seller.review*') ? 'active' : '' }}">
            <a href="{{ route('seller.review.index') }}">
                <i class="fa-solid fa-comments fa-2xl menu-icon"></i>
                <span>{{ __('label.reviews') }}</span>
            </a>
        </li>
        <p class="partition"><span>{{ __('label.financial') }}</span></p>
       
        <li class="side_line {{ (request()->routeIs('seller.wallet*')) ? 'active' : '' }}">
            <a href="{{ route('seller.wallet.index') }}">
                <i class="fa-solid fa-right-left fa-2xl menu-icon"></i>
                <span>{{__('label.wallet')}}</span>
            </a>
        </li>
        <p class="partition"><span>{{ __('label.logout') }}</span></p>
        <li>
            <a href="{{ route('seller.logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket fa-2xl menu-icon"></i>
                <span>{{ __('label.logout') }}</span>
            </a>

            <form id="logout-form" action="{{ route('seller.logout') }}" method="GET" class="d-none">
                @csrf
            </form>
        </li>

    </ul>

</div>
