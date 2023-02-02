<div id="navbar-menu" class="right-panel-wrapper" style="">
    <div class="panel-overlay" style="z-index:1002;"></div>
    <div class="right-panel" style="z-index:1003;">
        <div class="right-panel-head" style="margin-bottom:20px;">
            <div></div>

            <a class="close-panel">
                <i data-icon="feather/x" style="width:38px;height:38px;"></i>
            </a>
        </div>

        <div class="right-panel-body">
            <div style="padding: 1.5rem 4rem;">
                <div class="container" style="flex-direction: column;align-items: flex-start;">
                    <div>
                        <div class="menu-item @if ((request()->route()->action['activeMenuItem'] ?? null) == \App\Enums\MenuItemsEnum::Home) active @endif" style="display:inline-block;text-align: left;min-width: auto;padding:0;font-size: 2rem;margin-bottom:40px">
                            <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Home->name, [], false) }}" style="color: inherit;text-decoration: none;">
                                Home
                            </a>
                        </div>
                    </div>

                    <div>
                        <div class="menu-item @if ((request()->route()->action['activeMenuItem'] ?? null) == \App\Enums\MenuItemsEnum::Categories) active @endif" style="display:inline-block;text-align: left;min-width: auto;padding:0;font-size: 2rem;margin-bottom:40px">
                            <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Categories->name, [], false) }}" style="color: inherit;text-decoration: none;">
                                Categories
                            </a>
                        </div>
                    </div>

                    <div>
                        <div class="menu-item @if ((request()->route()->action['activeMenuItem'] ?? null) == \App\Enums\MenuItemsEnum::Recommended) active @endif" style="display:inline-block;text-align: left;min-width: auto;padding:0;font-size: 2rem;margin-bottom:40px">
                            <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::Recommended->name, [], false) }}" style="color: inherit;text-decoration: none;">
                                Recommended
                            </a>
                        </div>
                    </div>

                    <div>
                        <div class="menu-item @if ((request()->route()->action['activeMenuItem'] ?? null) == \App\Enums\MenuItemsEnum::MyHistory) active @endif" style="display:inline-block;text-align: left;min-width: auto;padding:0;font-size: 2rem;margin-bottom:40px">
                            <a href="{{ route('menu_'.\App\Enums\MenuItemsEnum::MyHistory->name, [], false) }}" style="color: inherit;text-decoration: none;">
                                My History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
