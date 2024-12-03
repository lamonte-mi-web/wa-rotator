@extends("layouts.app")

	@section("style")
    <link href="{{ asset('assets/plugins/highcharts/css/highcharts.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />

    <style>
        .cs-numbers-grid {
            display: grid;
            grid-template-columns: 1fr; /* Membuat satu kolom */
            gap: 8px; /* Jarak antar elemen */
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
                <h2>Edit Campaign</h2>
                <form action="{{ route('campaigns.update', parameters: ['id' => optional($campaign)['id']]) }}" method="POST">
                @csrf
                @method('PUT')
                    <!-- Campaign Title -->
                    <input type="hidden" name="id" value="{{ old('id', $campaign['id']) }}">
                    <div class="mb-3">
                        <label for="campaignTitle" class="form-label">Campaign Title</label>
                        <input type="text" class="form-control" id="campaignTitle" name="campaignTitle" 
                            value="{{ old('campaignTitle', $campaign['campaignTitle']) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="campaignName" class="form-label">Campaign Name</label>
                        <input type="text" class="form-control" id="campaignName" name="campaignName" 
                            value="{{ old('campaignName', $campaign['campaignName']) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="csNumbers" class="form-label">CS Numbers</label>
                        <select multiple class="form-control" id="csNumbers" name="csNumbers[]">
                            @foreach($csNumbers as $cs)
                                <option value="{{ $cs['nomor'] }}"
                                    @if(in_array($cs['nomor'], old('csNumbers', $campaign['csNumbers'] ?? []))) selected @endif>
                                    {{ $cs['name'] }} - {{ $cs['nomor'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="pixelTrack" class="form-label">Pixel Track</label>
                        <select class="form-control" id="pixelTrack" name="pixelTrack" required>
                            <option value="ViewContent" @if(old('pixelTrack', $campaign['pixelTrack']) == 'ViewContent') selected @endif>ViewContent</option>
                            <option value="AddToCart" @if(old('pixelTrack', $campaign['pixelTrack']) == 'AddToCart') selected @endif>AddToCart</option>
                            <option value="AddToWishlist" @if(old('pixelTrack', $campaign['pixelTrack']) == 'AddToWishlist') selected @endif>AddToWishlist</option>
                            <option value="InitiateCheckout" @if(old('pixelTrack', $campaign['pixelTrack']) == 'InitiateCheckout') selected @endif>InitiateCheckout</option>
                            <option value="AddPaymentInfo" @if(old('pixelTrack', $campaign['pixelTrack']) == 'AddPaymentInfo') selected @endif>AddPaymentInfo</option>
                            <option value="Purchase" @if(old('pixelTrack', $campaign['pixelTrack']) == 'Purchase') selected @endif>Purchase</option>
                            <option value="Lead" @if(old('pixelTrack', $campaign['pixelTrack']) == 'Lead') selected @endif>Lead</option>
                            <option value="CompleteRegistration" @if(old('pixelTrack', $campaign['pixelTrack']) == 'CompleteRegistration') selected @endif>CompleteRegistration</option>
                            <option value="Contact" @if(old('pixelTrack', $campaign['pixelTrack']) == 'Contact') selected @endif>Contact</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="metaPixel" class="form-label"> Meta Pixel</label>
                        <input type="text" class="form-control" id="metaPixel" name="metaPixel" 
                        value="{{ old('metaPixel', $campaign['metaPixel']) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="campaignType" class="form-label">Campaign Type</label>
                        <select class="form-control" id="campaignType" name="campaignType" onchange="toggleFields(this.value)">
                            <option value="cta_form" 
                                    @if(old('campaignType', $campaign['campaignType']) == 'cta_form') selected @endif>CTA Form</option>
                            <option value="redirect" 
                                    @if(old('campaignType', $campaign['campaignType']) == 'redirect') selected @endif>Redirect</option>
                        </select>
                    </div>

                    <div id="formFieldsSection" class="mb-3" style="display: {{ old('campaignType', $campaign['campaignType']) == 'cta_form' ? 'block' : 'none' }};">
                        <label for="formFields" class="form-label">Form Fields</label>
                        <select multiple class="form-control" id="formFields" name="formField[]">
                            <option value="name" @if(in_array('name', old('formField', $campaign['formField'] ?? []))) selected @endif>Name</option>
                            <option value="email" @if(in_array('email', old('formField', $campaign['formField'] ?? []))) selected @endif>Email</option>
                            <option value="nomortelepon" @if(in_array('nomortelepon', old('formField', $campaign['formField'] ?? []))) selected @endif>Nomor Telepon</option>
                            <option value="alamat" @if(in_array('alamat', old('formField', $campaign['formField'] ?? []))) selected @endif>Alamat</option>
                        </select>
                    </div>

                    <div id="templateMessageSection" class="mb-3" style="display: {{ old('campaignType', $campaign['campaignType'] ?? '') == 'redirect' ? 'block' : 'none' }};">
                        <label for="templateMessage" class="form-label">Template Message</label>
                        <textarea class="form-control" id="templateMessage" name="templateMessage">{{ old('templateMessage', $campaign['templateMessage'] ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Campaign</button>
                </form>
				</div><!--end row-->
			</div>
		</div>
		@endsection
		
	@section("script")
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
    <script>
        new PerfectScrollbar('.customers-list');
        new PerfectScrollbar('.store-metrics');
        new PerfectScrollbar('.product-list');
    </script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
    function toggleFields(type) {
        document.getElementById('formFieldsSection').style.display = (type === 'cta_form') ? 'block' : 'none';
        document.getElementById('templateMessageSection').style.display = (type === 'redirect') ? 'block' : 'none';
    }
    </script>
    
	@endsection