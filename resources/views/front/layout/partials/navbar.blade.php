<div class="navbar">
    <div class="container navbar-content">
        <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Home->name, [], false) }}" style="color: inherit;">
            <div class="navbar-logo">
                <i data-icon="play-button"></i>

                <div class="navbar-logo-text">
                    {{ env('APP_NAME') }}
                </div>
            </div>
        </a>


        <div class="navbar-search">
            <div class="mobile-search-trigger">
                <i data-icon="feather/search"></i>
            </div>


            <div class="navbar-search-container" style="z-index:999;">
                <input data-navbar-search type="text" value="@if(isset($searchInputValue)){{$searchInputValue}}@endif" placeholder="Search">

                <div class="navbar-search-button" style="border-radius: 15px;">
                    <i data-icon="feather/search"></i>
                </div>

                <div data-navbar-search-suggestions>
                    <div data-navbar-search-suggestions-content></div>
                </div>
            </div>
        </div>


        <div class="dropdown" data-navbar-orientation>
            <div data-dropdown="navbar">
                <div>
                    <i data-icon="{{ strtolower(\App\Utils::getOrientationName($settingsService->get('orientation'))) }}"></i>

                    <span>
                        {{ \App\Utils::getOrientationName($settingsService->get('orientation')) }}
                    </span>
                </div>
            </div>

            <div data-dropdown-content="navbar">
                <div class="dropdown-content-menu-panel">
                    <div class="item @if (($settingsService->get('orientation') ?? \App\Enums\OrientationsEnum::STRAIGHT) == \App\Enums\OrientationsEnum::STRAIGHT) active @endif" data-href="{{ route('setOrientation', [\App\Enums\OrientationsEnum::STRAIGHT], false) }}">
                        <i data-icon="straight"></i>

                        <div class="text">
                            Straight
                        </div>
                    </div>

                    <hr style="margin: 4px 0;">

                    <div class="item @if ($settingsService->get('orientation') == \App\Enums\OrientationsEnum::GAY) active @endif" data-href="{{ route('setOrientation', [\App\Enums\OrientationsEnum::GAY], false) }}">
                        <i data-icon="gay"></i>

                        <div class="text">
                            Gay
                        </div>
                    </div>

                    <hr style="margin: 4px 0;">

                    <div class="item @if ($settingsService->get('orientation') == \App\Enums\OrientationsEnum::TRANS) active @endif" data-href="{{ route('setOrientation', [\App\Enums\OrientationsEnum::TRANS], false) }}">
                        <i data-icon="trans"></i>

                        <div class="text">
                            Trans
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="navbar-menu">

    <div class="container visible-desktop">
        <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Home->name, [], false) }}" style="color: inherit;">
            <div class="menu-item @if ((request()->route()->action['activeMenuItem'] ?? null) == \App\Enums\MenuItemsEnum::Home) active @endif">
                Home
            </div>
        </a>

        <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Categories->name, [], false) }}" style="color: inherit;">
            <div class="menu-item @if ((request()->route()->action['activeMenuItem'] ?? null) == \App\Enums\MenuItemsEnum::Categories) active @endif">
                Categories
            </div>
        </a>

        <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Recommended->name, [], false) }}" style="color: inherit;">
            <div class="menu-item @if ((request()->route()->action['activeMenuItem'] ?? null) == \App\Enums\MenuItemsEnum::Recommended) active @endif">
                Recommended
            </div>
        </a>

        <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::MyHistory->name, [], false) }}" style="color: inherit;">
            <div class="menu-item @if ((request()->route()->action['activeMenuItem'] ?? null) == \App\Enums\MenuItemsEnum::MyHistory) active @endif">
                My History
            </div>
        </a>
    </div>


    <div class="visible-mobile">
        <i data-icon="feather/menu" style="width: 32px;height: 32px;margin:6px 0 6px 10px" onclick="window['openRightPanel']('navbar-menu');"></i>
    </div>
</div>

<div class="page-dark-search"></div>
