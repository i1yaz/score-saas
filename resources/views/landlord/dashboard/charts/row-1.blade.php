<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{formatAmountWithCurrency($stats['income_today'])}}</h3>
                <p>Today</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <div class="small-box-footer"></div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{formatAmountWithCurrency($stats['income_this_month'])}}</h3>
                <p>This Month</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <div  class="small-box-footer"></div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{formatAmountWithCurrency($stats['income_this_year'])}}</h3>
                <p>This Year</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <div class="small-box-footer"></div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{$stats['count_customers']}}</h3>
                <p>Customers</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <div class="small-box-footer"></div>
        </div>
    </div>

</div>
