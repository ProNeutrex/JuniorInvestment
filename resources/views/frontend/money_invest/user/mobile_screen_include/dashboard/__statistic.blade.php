<div class="all-feature-mobile mb-3 mobile-screen-show">
    <div class="title">{{ __('All Statistic') }}</div>
    <div class="row">
        <div class="col-12">
            <div class="all-cards-mobile">
                <div class="row user-cards ">
                    <div class="col-lg-4 pb-5">
                        <div class="single">
                            <div class="icon"><i class="anticon anticon-inbox"></i></div>
                            <div class="content">
                                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ number_format($dataCount['balance'],2) }}</span></h4>
                                <p>Minha Banca</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 pb-5">
                        <div class="single">
                            <div class="icon"><i class="anticon anticon-check-square"></i></div>
                            <div class="content">
                                <h4><b>{{ $currencySymbol }}</b><span class="count">{{number_format($user->profit_balance,2) }}</span></h4>
                                <p>Meu lucro</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
