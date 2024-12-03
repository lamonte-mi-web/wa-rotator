@extends('layouts.app')

@section('style')
    <link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
    <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <style>
        /* General Layout */
.page-wrapper {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
}

.card {
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    background-color: #ffffff;
}

.card-body {
    padding: 20px;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333333;
    margin-bottom: 20px;
}

.detail-section strong {
    color: #333333;
    font-size: 1rem;
    font-weight: 600;
    display: block;
    margin-bottom: 8px;
}
/* Mobile Responsiveness */
@media (max-width: 768px) {
    .form-field-grid {
        grid-template-columns: 1fr 1fr;
    }
    .cs-cards-container {
        grid-template-columns: 1fr 1fr;
    }
}

    </style>
@endsection

@section('wrapper')
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

                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Campaign Details</h5>

                            <div class="campaign-details">
                                <div class="detail-section">
                                    <strong>Campaign Title:</strong>
                                    <p>{{ $campaign['campaignTitle'] }}</p>
                                </div>

                                <div class="detail-section">
                                    <strong>Campaign Name:</strong>
                                    <p>{{ $campaign['campaignName'] }}</p>
                                </div>

                                <div class="detail-section">
                                    <strong>CS Numbers:</strong>
                                    <div class="cs-cards-container">
                                        @foreach ($campaign['csNumbers'] as $csNumber)
                                            <div class="cs-card">
                                                <div class="cs-number">{{ $csNumber }}</div>
                                                <div class="cs-name">CS Name Placeholder</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (count($campaign['csNumbers']) == 0)
                                        <div class="data-kosong">Data Kosong</div>
                                    @endif
                                </div>

                                <div class="detail-section">
                                    <strong>Meta Pixel:</strong>
                                    <p>{{ $campaign['metaPixel'] ?: 'Not Available' }}</p>
                                </div>

                                <div class="detail-section">
                                    <strong>Campaign Type:</strong>
                                    <p>{{ $campaign['campaignType'] }}</p>
                                </div>

                                <div class="detail-section">
                                    <strong>Pixel Track:</strong>
                                    <p>{{ $campaign['pixelTrack'] }}</p>
                                </div>

                                <div class="detail-section">
                                    <strong>Form Fields:</strong>
                                    @if (empty($campaign['formField']))
                                        <p class="data-kosong">Data Kosong</p>
                                    @else
                                        <div class="form-field-grid">
                                            @foreach ($campaign['formField'] as $field)
                                                <div class="form-field-item">{{ ucfirst($field) }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="detail-section">
                                    <strong>Template Message:</strong>
                                    @if (empty($campaign['templateMessage']))
                                        <p class="data-kosong">Data Kosong</p>
                                    @else
                                        <p>{{ $campaign['templateMessage'] }}</p>
                                    @endif
                                </div>

                                <div class="back-button">
                                    <a href="{{ route('campaign') }}" class="btn btn-primary btn-sm">Back to Campaign List</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>
@endsection

@section('script')
    <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/plugins/highcharts/js/highcharts.js"></script>
    <script src="assets/plugins/highcharts/js/exporting.js"></script>
    <script src="assets/js/custom.js"></script>
@endsection
