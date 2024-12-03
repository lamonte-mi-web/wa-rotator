@extends("layouts.app")

	@section("style")
	<link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
	<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <style>
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
        width: 40px; /* Slightly smaller for compact look */
        height: 40px;
        border-radius: 50%; /* Circular buttons */
        border: 2px solid #007bff;
        background-color: #fff;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
    }

    .btn-icon:hover {
        background-color: #007bff;
        color: #fff;
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

    /* Alert box styling */
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
		<div class="page-wrapper">
			<div class="page-content">
				<div class="row">
                @section('wrapper')
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <h5 class="card-title">Admin List</h5>
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
                            <button style="margin-bottom: 15px;" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCSModal">
                                <i class="bx bx-plus"></i> Tambahkan Data Customer Service
                            </button>
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th >No</th>
                                            <th> Nama CS</th>
                                            <th class="text-center">Nomor CS</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($customerservices as $index => $cs)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $cs['name'] }}</td>
                                            <td class="text-center" >{{ $cs['nomor'] }}</td>
                                            <td class="text-center">
                                                <button class="btn-icon btn-white btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#editCSModal" 
                                                    data-id="{{ $cs['id'] }}" 
                                                    data-name="{{ $cs['name'] }}" 
                                                    data-phone="{{ $cs['nomor'] }}">
                                                    <i class="bx bx-edit"></i> 
                                                </button>
                                                <form id="deleteForm" action="{{ route('cs.destroy', $cs['id']) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn-icon btn-white btn-sm" onclick="confirmDelete()"> <i class="bx bx-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
		new PerfectScrollbar('.customers-list');
		new PerfectScrollbar('.store-metrics');
		new PerfectScrollbar('.product-list');
	</script>
    <script>
            const editCSModal = document.getElementById('editCSModal');

            editCSModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Button yang membuka modal
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const phone = button.getAttribute('data-phone');

                // Isi data ke form modal
                document.getElementById('editCSId').value = id;
                document.getElementById('editName').value = name;
                document.getElementById('editNomor').value = phone;
            });
        </script>
        <script>
             editCSModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const actionUrl = `{{ url('/cs-number') }}/${id}`;
                document.getElementById('editCSForm').setAttribute('action', actionUrl);
            });
        </script>
        <script>
            function confirmDelete() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').submit();
                    }
                });
            }
        </script>
        <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#example').DataTable();
            } );
        </script>
        <script>
            $(document).ready(function() {
                var table = $('#example2').DataTable( {
                    lengthChange: false,
                    buttons: [ 'copy', 'excel', 'pdf', 'print']
                } );
            
                table.buttons().container()
                    .appendTo( '#example2_wrapper .col-md-6:eq(0)' );
            } );
        </script>
    @endsection
    <!-- Modal -->
    <div class="modal fade" id="addCSModal" tabindex="-1" aria-labelledby="addCSModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCSModalLabel">Tambah Data Customer Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk menambah data Customer Service -->
                    <form action="{{ route('cs-store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Customer Service</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama CS" required>
                        </div>

                        <div class="mb-3">
                            <label for="phoneNumber" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Masukkan Nomor Telepon CS" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit -->
    <div class="modal fade" id="editCSModal" tabindex="-1" aria-labelledby="editCSModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCSModalLabel">Edit Data Customer Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk mengedit data Customer Service -->
                    <form id="editCSForm" method="POST">
                    @csrf
                    @method('PUT')
                        <input type="hidden" id="editCSId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Customer Service</label>
                            <input type="text" class="form-control" id="editName" name="name" placeholder="Masukkan Nama CS" required>
                        </div>

                        <div class="mb-3">
                            <label for="editNomor" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="editNomor" name="nomor" placeholder="Masukkan Nomor Telepon CS" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    </form>
                </div>
            </div>
        </div>
    </div>