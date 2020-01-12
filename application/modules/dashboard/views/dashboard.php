
<div class="row">
    <section class="col-lg-12">
        <div class="row m-l-none m-r-none m-t  white-bg shortcut ">
            <div class="col-sm-6 col-md-3 b-r  p-sm">
                <span class="pull-left m-r-sm text-navy"><i class="fa fa-plus-circle"></i></span>
                <a href="#" class="clear">
                    <span class="h3 block m-t-xs"><strong> Modules  </strong>
                    </span> <small class="text-muted text-uc"> Manage Existing Modules or Create new one </small>
                </a>
            </div>
            <div class="col-sm-6 col-md-3 b-r p-sm">
                <span class="pull-left m-r-sm text-info">	<i class="fa fa-cogs"></i></span>
                <a href="#" class="clear">
                    <span class="h3 block m-t-xs"><strong>Setting</strong>
                    </span> <small class="text-muted text-uc">  Setting Up your application login option , sitename , email etc. </small>
                </a>
            </div>
            <div class="col-sm-6 col-md-3 b-r  p-sm">
                <span class="pull-left m-r-sm text-warning">	<i class="fa fa-sitemap"></i></span>
                <a href="#" class="clear">
                    <span class="h3 block m-t-xs"><strong> Site Menu </strong></span>
                    <small class="text-muted text-uc">Manage Menu for your application frontend or backend   </small> </a>
            </div>
            <div class="col-sm-6 col-md-3 b-r  p-sm">
                <span class="pull-left m-r-sm ">	<i class="fa fa-users"></i></span>
                <a href="#" class="clear">
                    <span class="h3 block m-t-xs"><strong>Users &amp; Groups</strong>
                    </span> <small class="text-muted text-uc">Manage groups and users and grant what module and menu are accesible  </small> </a>
            </div>
        </div>
    </section>
</div><!-- /.row -->
<hr>
<!-- Main row -->
<div class="row panel">
<!-- Left col -->
<section class="col-lg-8 connectedSortable">

    <!-- Cash collection bar chart in days & monthly -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header"><i class="fa fa-inbox"></i>Cash collection</li>
        </ul>
        <div id="cash_chart_days" style="position: relative; height: 300px;"></div>
    </div>

    <!-- Tickets bar chart in days & monthly -->
  

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header"><i class="fa fa-inbox"></i>Patient</li>
        </ul>
        <div id="patient_chart_months" style="position: relative; height: 300px;"></div>
    </div>


</section><!-- /.Left col -->
<!-- right col (We are only adding the ID to make the widgets sortable)-->
<section class="col-lg-4 connectedSortable">

    <!-- morris chart -->
    <div class="row">
        <h3 class="text-center">Hospital Manpower Status</h3>
        <div id="manpower_chart" style="width: 100%; height: 300px"></div>
    </div>

    <div class="row">
        <h3 class="text-center">Hospital Bed Status</h3>
        <div id="bed_chart" style="width: 100%; height: 300px"></div>
    </div>

</section><!-- right col -->

</div><!-- /.row (main row) -->

<!--</section> --><!-- /.content -->