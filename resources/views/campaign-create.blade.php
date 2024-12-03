@extends("layouts.app")

	@section("style")
	<link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
	<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
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
                <h2>Tambah Campaign</h2>

                    <form action="{{ route('campaign.store') }}" method="POST">
                        @csrf

                        <!-- Campaign Title -->
                        <div class="mb-3">
                            <label for="campaignTitle" class="form-label">Campaign Title</label>
                            <input type="text" class="form-control" id="campaignTitle" name="campaignTitle" required>
                        </div>

                        <!-- Campaign Name -->
                        <div class="mb-3">
                            <label for="campaignName" class="form-label">Campaign Name</label>
                            <input type="text" class="form-control" id="campaignName" name="campaignName" required>
                        </div>

                        <!-- CS Numbers Dropdown -->
                        <div class="mb-3">
                            <label for="csNumbers" class="form-label">CS Number</label>
                            <select class="form-control" id="csNumbers" name="csNumbers" required>
                                <option value="">Select CS Number</option>
                                @foreach($csNumbers as $cs)
                                    <option value="{{ $cs['nomor'] }}" 
                                        @if(in_array($cs['nomor'], old('csNumbers', []))) selected @endif>
                                        {{ $cs['name'] }} - {{ $cs['nomor'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="selectedCampaigns" class="selected-card"></div>
                        </div>

                        <!-- Hidden Inputs -->
                        <div id="hiddenInputs"></div>

                        <!-- Meta Pixel -->
                        <div class="mb-3">
                            <label for="metaPixel" class="form-label">Meta Pixel</label>
                            <input type="text" class="form-control" id="metaPixel" name="metaPixel" required>
                        </div>

                        <!-- Pixel Track -->
                        <div class="mb-3">
                            <label for="pixelTrack" class="form-label">Pixel Track</label>
                            <select class="form-control" id="pixelTrack" name="pixelTrack" required>
                                <option value="ViewContent">ViewContent</option>
                                <option value="AddToCart">AddToCart</option>
                                <option value="AddToWishlist">AddToWishlist</option>
                                <option value="InitiateCheckout">InitiateCheckout</option>
                                <option value="AddPaymentInfo">AddPaymentInfo</option>
                                <option value="Purchase">Purchase</option>
                                <option value="Lead">Lead</option>
                                <option value="CompleteRegistration">CompleteRegistration</option>
                                <option value="Contact">Contact</option>
                            </select>
                        </div>

                        <!-- Campaign Type -->
                        <div class="mb-3">
                            <label for="campaignType" class="form-label">Campaign Type</label>
                            <select class="form-control" id="campaignType" name="campaignType" required>
                                <option value="cta_form">CTA Form</option>
                                <option value="redirect">Redirect</option>
                            </select>
                        </div>

                        <!-- Dropdown dengan multiple selection -->
                        <div id="formFieldsSection" class="mb-3" style="display:none;">
                            <label for="formFields" class="form-label">Form Fields</label>
                            <select class="form-control" id="formFields" name="formField[]" multiple required>
                                <option value="name">Name</option>
                                <option value="email">Email</option>
                                <option value="nomortelepon">Nomor Telepon</option>
                                <option value="alamat">Alamat</option>
                            </select>
                            <div id="selectedFormFields" class="selected-card"></div> <!-- Menampilkan pilihan yang dipilih -->
                        </div>

                        <!-- Template Message Section -->
                        <div id="templateMessageSection" class="mb-3" style="display:none;">
                            <label for="templateMessage" class="form-label">Template Message</label>
                            <textarea class="form-control" id="templateMessage" name="templateMessage" rows="4" placeholder="Masukkan pesan template"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah Campaign</button>
                    </form>
                    </div>

                    <div id="hiddenFormFields"></div>
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
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        // CS Number selection logic (Dropdown)
        const csNumbersSelectElement = document.getElementById('csNumbers');
        const selectedCampaignsContainer = document.getElementById('selectedCampaigns');
        const hiddenCampaignsContainer = document.getElementById('hiddenInputs');
        const selectedCsNumbers = [];

        csNumbersSelectElement.addEventListener('change', function() {
            const value = csNumbersSelectElement.value;
            const text = csNumbersSelectElement.options[csNumbersSelectElement.selectedIndex].text;

            if (value && !selectedCsNumbers.includes(value)) {
                selectedCsNumbers.push(value);

                // Create a card to display the selected CS number
                const card = document.createElement('div');
                card.classList.add('card', 'mb-2');
                card.innerHTML = `
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span>${text}</span>
                        <button type="button" class="btn btn-danger btn-sm remove-item" data-value="${value}">x</button>
                    </div>
                `;
                selectedCampaignsContainer.appendChild(card);

                // Create a hidden input for the selected value
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'csNumbers[]'; // Same name as the select
                hiddenInput.value = value;
                hiddenCampaignsContainer.appendChild(hiddenInput);

                // Disable the option in the dropdown
                const optionToDisable = Array.from(csNumbersSelectElement.options).find(opt => opt.value === value);
                if (optionToDisable) {
                    optionToDisable.setAttribute('disabled', 'true');
                }
            }
        });

        // Handle removing a selected CS number
        selectedCampaignsContainer.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                const valueToRemove = e.target.getAttribute('data-value');

                // Remove from selectedCsNumbers array
                const index = selectedCsNumbers.indexOf(valueToRemove);
                if (index !== -1) {
                    selectedCsNumbers.splice(index, 1);
                }

                // Remove the card from the displayed list
                e.target.closest('.card').remove();

                // Remove the hidden input
                const hiddenInputs = hiddenCampaignsContainer.querySelectorAll(`input[value="${valueToRemove}"]`);
                hiddenInputs.forEach(input => input.remove());

                // Enable the option in the dropdown again
                const optionToEnable = Array.from(csNumbersSelectElement.options).find(opt => opt.value === valueToRemove);
                if (optionToEnable) {
                    optionToEnable.removeAttribute('disabled');
                }
            }
        });

        // Form Fields selection logic (Dropdown)
        const formFieldsSelectElement = document.getElementById('formFields');
        const selectedFormFieldsContainer = document.getElementById('selectedFormFields');
        const hiddenFormFieldsContainer = document.getElementById('hiddenFormFields');
        const selectedFormFieldsValues = [];

        document.addEventListener("DOMContentLoaded", function() {
            const formFieldsSelect = document.getElementById('formFields');
            const selectedFormFieldsDiv = document.getElementById('selectedFormFields');
            
            // Listen for change event on select dropdown
            formFieldsSelect.addEventListener('change', function() {
                // Get the selected options
                const selectedOptions = Array.from(formFieldsSelect.selectedOptions);
                
                // Clear the current selections display
                selectedFormFieldsDiv.innerHTML = '';
                
                // Add selected items to the display
                selectedOptions.forEach(option => {
                    // Create a visual display for each selected option
                    const selectedCard = document.createElement('div');
                    selectedCard.classList.add('selected-item');
                    selectedCard.textContent = option.text; // Show option name (e.g., "Name")
                });
            });
        });

        // Handle removing a selected form field
        selectedFormFieldsContainer.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                const valueToRemove = e.target.getAttribute('data-value');

                // Remove from selectedFormFieldsValues array
                const index = selectedFormFieldsValues.indexOf(valueToRemove);
                if (index !== -1) {
                    selectedFormFieldsValues.splice(index, 1);
                }

                // Remove the card from the displayed list
                e.target.closest('.card').remove();

                // Remove the hidden input
                const hiddenInputs = hiddenFormFieldsContainer.querySelectorAll(`input[value="${valueToRemove}"]`);
                hiddenInputs.forEach(input => input.remove());

                // Enable the option in the dropdown again
                const optionToEnable = Array.from(formFieldsSelectElement.options).find(opt => opt.value === valueToRemove);
                if (optionToEnable) {
                    optionToEnable.removeAttribute('disabled');
                }
            }
        });

        // Show/hide sections based on campaign type
        const campaignTypeSelect = document.getElementById('campaignType');
        const formFieldsSection = document.getElementById('formFieldsSection');
        const templateMessageSection = document.getElementById('templateMessageSection');

        campaignTypeSelect.addEventListener('change', function() {
            const selectedValue = campaignTypeSelect.value;
            if (selectedValue === 'cta_form') {
                formFieldsSection.style.display = 'block';
                templateMessageSection.style.display = 'none';
            } else if (selectedValue === 'redirect') {
                formFieldsSection.style.display = 'none';
                templateMessageSection.style.display = 'block';
            }
        });

        // Trigger initial display of sections
        campaignTypeSelect.dispatchEvent(new Event('change'));
    </script>
    
	@endsection