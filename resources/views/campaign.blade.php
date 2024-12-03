@extends("layouts.app")

@section("style")
<link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
<link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
    .button-container {
        display: flex; 
        justify-content: center; 
        gap: 10px; 
        align-items: center;
    }

    .button-container .btn {
        display: inline-flex; 
        justify-content: center;
        align-items: center;
        padding: 6px 12px; 
        font-size: 14px; 
        border-radius: 5px; 
    }  
    table {
        font-size: 12px; /* Make table text smaller */
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

    .btn-icon {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        border: 2px solid #000; /* Ganti border menjadi hitam */
        background-color: #000; /* Ganti background menjadi hitam */
        font-size: 16px;
        transition: all 0.3s ease-in-out;
    }

    .btn-icon:hover {
        background-color: #007bff; /* Warna latar belakang saat hover */
        color: #fff; /* Warna teks menjadi putih saat hover */
    }


    /* Modal styling */
    .modal-body form input,
    .modal-body form button {
        font-size: 14px; /* Smaller input and button text */
    }

    /* Improved button container layout */
    .button-container button {
        display: inline-block;
        margin-right: 5px;
    }

    /* Card styling */
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .card-body {
        padding: 20px;
    }

    .alert {
        border-radius: 8px;
        font-size: 14px;
    }

    .cs-number-item {
        background-color: #f1f5f8;
        border: 1px solid #e0e5ec;
        border-radius: 15px;
        padding: 8px 12px;
        color: #4c6e8c;
        font-size: 14px;
    }
    .button-container button {
        display: inline-block;
        margin-right: 2px; 
    }
</style>
@endsection

@section("wrapper")

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="col">
                <div class="card radius-10 mb-0">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <h5 class="card-title">Campaign List</h5>
                            <div class="ms-auto">
                                <div class="dropdown">
                                    <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted font-size-12">Sort By: </span> <span class="fw-medium">
                                            Monthly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                        <a class="dropdown-item" href="#">Weekly</a>
                                        <a class="dropdown-item" href="#">Monthly</a>
                                        <a class="dropdown-item" href="#">Yearly</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('campaign.create') }}" class="btn btn-success btn-sm">
                            <i class="bx bx-plus"></i> Tambah Data Campaign
                        </a>
                        <div class="table-responsive mt-3">
                            <table id="example" class="table table-striped table-bordered custom-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 210px;">Campaign Title</th>
                                        <th>Campaign Name</th>
                                        <th class="text-center">Total Form</th>
                                        <th class="text-center">Total Traffic</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($campaigns as $index => $campaign)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $campaign['campaignTitle'] }}</td>
                                        <td>
                                            <a href="{{ route('form', $campaign['campaignName']) }}" class="text-primary" target="_blank">
                                                {{ $campaign['campaignName'] }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('form.detail', $campaign['campaignName']) }}" class="text-primary">
                                                {{ $campaign['logData']['totalForm'] }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('traffic.detail', $campaign['campaignName']) }}" class="text-promary">
                                                {{ $campaign['logData']['totalTraffic'] }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="button-container d-flex gap-2 text-center" >
                                            <a href="{{ route('campaign.detail', $campaign['campaignName']) }}" class="btn-icon btn-white" title="View Details">
                                                <i class="bx bx-info-circle" style="color: black;"></i>
                                            </a>
                                            <a href="{{ route('campaigns.edit', $campaign['campaignName']) }}" class="btn-icon btn-white" title="Edit Campaign">
                                                <i class="bx bx-edit" style="color: black;"></i>
                                            </a>
                                                <form id="deleteCampaignForm-{{ $campaign['id'] }}" action="{{ route('campaign.destroy', $campaign['id']) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn-icon btn-white" title="Delete Campaign" onclick="confirmDeleteCampaign('{{ $campaign['id'] }}')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div><!--end row-->
    </div>
</div>
@endsection

@section("script")
<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="assets/plugins/highcharts/js/highcharts.js"></script>
<script src="assets/plugins/highcharts/js/exporting.js"></script>
<script src="assets/plugins/highcharts/js/variable-pie.js"></script>
<script src="assets/plugins/highcharts/js/export-data.js"></script>
<script src="assets/plugins/highcharts/js/accessibility.js"></script>
<script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
<script src="assets/js/index2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDeleteCampaign(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Campaign yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`deleteCampaignForm-${id}`).submit();
            }
        });
    }
</script>
<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
@endsection
