@extends('backend.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-title text-muted">Total Subscription</h4>
                    <h2 class="mt-3 mb-2"><i class="mdi mdi-arrow-down text-danger me-2"></i><b>8952</b>
                    </h2>
                    <p class="text-muted mb-0 mt-3"><b>48%</b> From Last 24 Hours</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body p-t-10">
                    <h4 class="card-title text-muted mb-0">Order Status</h4>
                    <h2 class="mt-3 mb-2"><i class="mdi mdi-arrow-up text-success me-2"></i><b>6521</b></h2>
                    <p class="text-muted mb-0 mt-3"><b>42%</b> Orders in Last 10 months</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body p-t-10">
                    <h4 class="card-title text-muted mb-0">Unique Visitors</h4>
                    <h2 class="mt-3 mb-2"><i class="mdi mdi-arrow-up text-success me-2"></i><b>452</b></h2>
                    <p class="text-muted mb-0 mt-3"><b>22%</b> From Last 24 Hours</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body p-t-10">
                    <h4 class="card-title text-muted mb-0">Monthly Earnings</h4>
                    <h2 class="mt-3 mb-2"><i class="mdi mdi-arrow-down text-danger me-2"></i><b>5621</b>
                    </h2>
                    <p class="text-muted mb-0 mt-3"><b>35%</b> From Last 1 Month</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row">

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 card-title">Email Sent</h4>

                    <ul class="list-inline d-flex justify-content-around mt-3">
                        <li class="list-inline-item">
                            <h5 class="text-center"><b>3654</b></h5>
                            <p class="text-muted mb-0">Marketplace</p>
                        </li>
                        <li class="list-inline-item">
                            <h5 class="text-center"><b>954</b></h5>
                            <p class="text-muted mb-0">Last week</p>
                        </li>
                        <li class="list-inline-item">
                            <h5 class="text-center"><b>8462</b></h5>
                            <p class="text-muted mb-0">Last Month</p>
                        </li>
                    </ul>

                    <div class="morris-charts" id="morris-donut-example" style="height: 300px"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 card-title">Revenue</h4>

                    <ul class="list-inline d-flex justify-content-around mt-3">
                        <li class="list-inline-item">
                            <h5 class="text-center"><b>5248</b></h5>
                            <p class="text-muted mb-0">Marketplace</p>
                        </li>
                        <li class="list-inline-item">
                            <h5 class="text-center"><b>321</b></h5>
                            <p class="text-muted mb-0">Last week</p>
                        </li>
                        <li class="list-inline-item">
                            <h5 class="text-center"><b>964</b></h5>
                            <p class="text-muted mb-0">Last Month</p>
                        </li>
                    </ul>


                    <div id="morris-bar-example" style="height: 300px"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 card-title">Email Sent</h4>

                    <ul class="list-inline d-flex justify-content-around mt-3">
                        <li class="list-inline-item">
                            <h5 class="text-center"><b>3654</b></h5>
                            <p class="text-muted mb-0">Marketplace</p>
                        </li>
                        <li class="list-inline-item">
                            <h5 class="text-center"><b>954</b></h5>
                            <p class="text-muted mb-0">Last week</p>
                        </li>
                        <li class="list-inline-item">
                            <h5 class="text-center"><b>8462</b></h5>
                            <p class="text-muted mb-0">Last Month</p>
                        </li>
                    </ul>


                    <div id="morris-area-example" style="height: 300px"></div>
                </div>
            </div>
        </div>

    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-7">
            <div class="card card-h-100">
                <div class="card-body">
                    <h4 class="mb-4 mt-0 card-title">Recent Contacts</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td>$170,750</td>
                                        </tr>
                                        <tr>
                                            <td>Ashton Cox</td>
                                            <td>Junior Technical Author</td>
                                            <td>San Francisco</td>
                                            <td>66</td>
                                            <td>2009/01/12</td>
                                            <td>$86,000</td>
                                        </tr>
                                        <tr>
                                            <td>Cedric Kelly</td>
                                            <td>Senior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2012/03/29</td>
                                            <td>$433,060</td>
                                        </tr>
                                        <tr>
                                            <td>Airi Satou</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>33</td>
                                            <td>2008/11/28</td>
                                            <td>$162,700</td>
                                        </tr>
                                        <tr>
                                            <td>Brielle Williamson</td>
                                            <td>Integration Specialist</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2012/12/02</td>
                                            <td>$372,000</td>
                                        </tr>
                                        <tr>
                                            <td>Herrod Chandler</td>
                                            <td>Sales Assistant</td>
                                            <td>San Francisco</td>
                                            <td>59</td>
                                            <td>2012/08/06</td>
                                            <td>$137,500</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->

        <div class="col-lg-5">
            <div class="card card-h-100">
                <div class="card-body">
                    <h4 class="mb-4 mt-0 card-title">Goal Completion</h4>

                    <p class="font-600 mb-1">Add Product to cart <span
                            class="text-primary float-end"><b>80%</b></span></p>
                    <div class="progress  mb-3">
                        <div class="progress-bar progress-bar-primary " role="progressbar"
                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
                        </div><!-- /.progress-bar .progress-bar-danger -->
                    </div><!-- /.progress .no-rounded -->

                    <p class="font-600 mb-1">Complete Purchases <span
                            class="text-primary float-end"><b>50%</b></span></p>
                    <div class="progress  mb-3">
                        <div class="progress-bar progress-bar-primary " role="progressbar"
                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
                        </div><!-- /.progress-bar .progress-bar-pink -->
                    </div><!-- /.progress .no-rounded -->

                    <p class="font-600 mb-1">Visit Premium plan <span
                            class="text-primary float-end"><b>70%</b></span></p>
                    <div class="progress  mb-3">
                        <div class="progress-bar progress-bar-primary " role="progressbar"
                            aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
                        </div><!-- /.progress-bar .progress-bar-info -->
                    </div><!-- /.progress .no-rounded -->

                    <p class="font-600 mb-1">Send Inquiries <span
                            class="text-primary float-end"><b>65%</b></span></p>
                    <div class="progress  mb-3">
                        <div class="progress-bar progress-bar-primary " role="progressbar"
                            aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                        </div><!-- /.progress-bar .progress-bar-warning -->
                    </div><!-- /.progress .no-rounded -->

                    <p class="font-600 mb-1">Monthly Purchases <span
                            class="text-primary float-end"><b>25%</b></span></p>
                    <div class="progress  mb-3">
                        <div class="progress-bar progress-bar-primary " role="progressbar"
                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;">
                        </div><!-- /.progress-bar .progress-bar-warning -->
                    </div><!-- /.progress .no-rounded -->

                    <p class="font-600 mb-1"> Daily Visits<span
                            class="text-primary float-end"><b>40%</b></span></p>
                    <div class="progress  mb-0">
                        <div class="progress-bar progress-bar-primary " role="progressbar"
                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                        </div><!-- /.progress-bar .progress-bar-success -->
                    </div><!-- /.progress .no-rounded -->
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->

</div>
@endsection