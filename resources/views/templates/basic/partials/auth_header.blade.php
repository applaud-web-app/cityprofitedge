<!-- header-section start  -->
<header class="header">
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl p-0 align-items-center">
                <a class="site-logo site-title" href="{{ route('home') }}">
                    <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="logo">
                </a>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu ms-auto">
                        <li><a href="{{ route('user.home') }}">@lang('Dashboard')</a></li>

                        <li class="menu_has_children">
                            <a href="#0">@lang('Learn2Trade')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.watchList') }}">@lang('Watch List')</a></li>
                                <li><a href="{{ route('user.ledgers') }}">@lang('Option Strategies')</a></li>
                                
                            </ul>
                        </li>
                        <li class="menu_has_children">
                            <a href="#0">@lang('Option Analysis')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.option-analysis') }}">@lang('All Graph')</a></li>
                                <li><a href="{{ route('user.ledgers') }}">@lang('Expiry Trading')</a></li>
                                
                            </ul>
                        </li>
                        {{-- <li class="menu_has_children">
                            <a href="#0">@lang('Deposit')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.deposit.index') }}">@lang('Deposit Now')</a></li>
                                <li><a href="{{ route('user.deposit.history') }}">@lang('Deposit History')</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('packages') }}">@lang('Product')</a></li>
                        <li><a href="{{ route('user.signals') }}">@lang('Signals')</a></li>
                        <li><a href="{{ route('user.referrals') }}">@lang('Referrals')</a></li>
                        --}}
                        {{-- <li class="menu_has_children">
                            <a href="#0">@lang('Financial Overview')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.ledgers') }}">@lang('Ledger')</a></li>
                                <li><a href="{{ route('user.stock.portfolios') }}">@lang('Stock Portfolio')</a></li>
                                <li><a href="{{ route('user.watchList') }}">@lang('Watch List')</a></li>
                            </ul>
                        </li> --}}
                        <li class="menu_has_children">
                            <a href="#0">@lang('AutoTrader')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.portfolio.top.gainers') }}">@lang('Trade Desk Signal')</a></li>
                                <li><a href="{{ route('user.trade-positions') }}">@lang('Trade Position')</a></li>
                                <li><a href="{{ route('user.order-books') }}">@lang('Order Book')</a></li>
                                <li><a href="{{ route('user.portfolio.broker-details') }}">@lang('Broker Details')</a></li>
                                <li><a href="{{ route('user.portfolio.oms-config') }}">@lang('OMS Config')</a></li>
                            </ul>
                        </li>
                        <li class="menu_has_children">
                            <a href="#0">@lang('Portfolios')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.stock.portfolios') }}">@lang('Stock Portfolio')</a></li>
                                <li><a href="{{ route('user.thematic.portfolios') }}">@lang('Thematic Portfolios')</a></li>
                                <li><a href="{{ route('user.global.stock.portfolio') }}">@lang('Global Stock Portfolio')</a></li>
                                <li><a href="{{ route('user.fo.portfolio.hedging') }}">@lang('F&O Portfolio-Hedging')</a></li>
                                <li><a href="{{ route('user.metals.portfolio') }}">@lang('Metals Portfolio (Gold & Silver)')</a></li>
                            </ul>
                        </li>
                       

                        {{-- <li class="menu_has_children">
                            <a href="#0">@lang('Portfolio Insights')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.portfolio.top.gainers') }}">@lang('Portfolio Top Gainers')</a></li>
                                <li><a href="{{ route('user.portfolio.top.losers') }}">@lang('Portfolio Top Losers')</a></li>
                            </ul>
                        </li> --}}
                        {{-- <li class="menu_has_children">
                            <a href="#0">@lang('Support')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('ticket.open') }}">@lang('New Ticket')</a></li>
                                <li><a href="{{ route('ticket.index') }}">@lang('My Tickets')</a></li>
                            </ul>
                        </li> --}}
                        <li class="menu_has_children">
                            <a href="#0">@lang('Account')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
                                {{-- <li><a href="{{ route('user.twofactor') }}">@lang('Two Factor')</a></li> --}}
                                <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                                <li><a href="{{ route('user.transactions') }}">@lang('Stock Transactions')</a></li>
                                <li><a href="{{ route('user.trade-book') }}">@lang('Trade Book')</a></li>
                                <li><a href="{{ route('user.pl-reports') }}">@lang('PL Reports')</a></li>
                                {{-- <li><a href="{{ route('user.option-analysis') }}">@lang('Option Analysis')</a></li> --}}
                                <li><a href="{{ route('user.logout') }}">@lang('Logout')</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="nav-right justify-content-xl-end">
                        <a href="{{ route('user.logout') }}" class="btn btn-md btn--base d-flex align-items-center">
                            <i class="las la-sign-out-alt fs--18px me-2"></i>
                            @lang('Logout')
                            </i>
                        </a>
                        @include('partials.language')
                    </div>
                </div>
            </nav>
        </div>
    </div><!-- header__bottom end -->
</header>
<!-- header-section end  -->
