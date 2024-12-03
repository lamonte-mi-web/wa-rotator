@extends("layouts.app")

@section("style")
<link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<style>
    table {
        font-size: 13px; /* Make table text smaller */
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }


    td {
        background-color: #f8f9fa;
        color: #495057;
    }


    tbody tr:hover {
        background-color: #f1f5f8;
        cursor: pointer;
    }
</style>

@endsection

@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col">
                    <div class="card radius-10 mb-0">
                        <div class="card-body">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h5><strong> Name: {{ $dataTraffic['campaignName'] }}</strong></h5>
                                <a href="{{ route('campaign') }}" class="btn btn-outline-secondary btn-icon btn-sm"><i class="bx bx-chevrons-left"></i> Back</a>
                            </div>
                            <div id="trafficChart"></div>
                            <br>
                            <br>
                            <h5> <strong> Detail Traffic </strong></h5>                            
                            <div class="mt-4">
                                <div class="table-responsive mt-3">
                                    <table id="example" class="table table-striped table-bordered custom-table" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th>CS Name</th>
                                                <th>IP Address</th>
                                                <th>Location</th>
                                                <th>Timestamp</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataTraffic['details'] as $detail)
                                                <tr>
                                                    <td>{{ $detail['assignedCSNumber'] }}</td>
                                                    <td>{{ $detail['ip'] }}</td>
                                                    <td>{{ $detail['location'] }}</td>
                                                    <td>{{ \Carbon\Carbon::createFromTimestampMs($detail['timestamp'])->toDateTimeString() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End .col -->
            </div><!-- End .row -->
        </div><!-- End .page-content -->
    </div><!-- End .page-wrapper -->
@endsection

@section("script")
    <!-- Plugin Scripts -->
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/plugins/highcharts/js/highcharts.js') }}"></script>
    <script src="{{ asset('assets/plugins/highcharts/js/exporting.js') }}"></script>
    <script src="{{ asset('assets/plugins/highcharts/js/variable-pie.js') }}"></script>
    <script src="{{ asset('assets/plugins/highcharts/js/export-data.js') }}"></script>
    <script src="{{ asset('assets/plugins/highcharts/js/accessibility.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/index2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Scripts -->
    <script>
        // Initialize PerfectScrollbar for specific elements
        new PerfectScrollbar('.customers-list');
        new PerfectScrollbar('.store-metrics');
        new PerfectScrollbar('.product-list');
    </script>
        <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

    <script>
        // Extract the traffic data from the controller (Laravel to JS)
        var trafficData = @json($dataTraffic['traffic']);  // Passing PHP array to JavaScript

        // Prepare the categories (CS Names) and data points (Traffic Count) for the chart
        var categories = trafficData.map(function(item) {
            return item.csName; // CS Name for the x-axis
        });

        var seriesData = trafficData.map(function(item) {
            return item.totalTraffic; // Total Traffic for the y-axis
        });

        // ApexCharts Configuration
        var options = {
            chart: {
                type: 'bar', // Bar chart type
                height: 400,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 1000,
                    dynamicAnimation: {
                        enabled: true,
                        speed: 500
                    }
                }
            },
            series: [{
                name: 'Total Traffic',
                data: seriesData
            }],
            xaxis: {
                categories: categories, // Use the CS Names as categories (x-axis)
                title: {
                    text: 'CS Name',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#7f8c8d'
                    }
                },
                labels: {
                    rotate: -45, // Rotate the x-axis labels for better readability
                    style: {
                        fontSize: '12px',
                        colors: ['#7f8c8d']
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Total Traffic',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#7f8c8d'
                    }
                },
                labels: {
                    style: {
                        fontSize: '12px',
                        colors: ['#7f8c8d']
                    }
                }
            },
            title: {
                text: 'Total Traffic Tiap CS',
                align: 'center',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    color: '#2c3e50'
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 10
                }
            },
            colors: ['#8833ff'], // Solid color for bars (Purple)
            gradient: {
                enabled: true,
                shadeIntensity: 1,
                type: 'horizontal', // Horizontal gradient effect
                gradientToColors: ['#ff5733'], // Gradient end color (orange)
                stops: [0, 100]
            },
            tooltip: {
                enabled: true,
                shared: true,
                intersect: false,
                theme: 'dark',
                style: {
                    fontSize: '14px'
                },
                x: {
                    show: true,
                    formatter: function(val) {
                        return "CS: " + val;
                    }
                },
                y: {
                    formatter: function(val) {
                        return "Traffic: " + val;
                    }
                }
            },
            grid: {
                borderColor: '#e1e1e1',
                strokeDashArray: 5,
                position: 'back'
            }
        };

        // Create and render the ApexChart
        var chart = new ApexCharts(document.querySelector("#trafficChart"), options);
        chart.render();
    </script>
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />

    <!-- jQuery (needed by DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Initialize DataTable 1 -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

    <!-- Initialize DataTable 2 with buttons -->
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });
            
            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
