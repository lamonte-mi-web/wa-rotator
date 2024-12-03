<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
        }
        .form-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            box-sizing: border-box;
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .cs-info {
            margin: 20px 0;
            font-size: 18px;
            color: #333;
            text-align: left;
        }
        .cs-info p {
            margin: 5px 0;
        }
        .form-field {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-field label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
        }
        .form-field input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            color: #555;
            box-sizing: border-box;
        }
        .form-field input:focus {
            border-color: #25d366;
            outline: none;
        }
        button {
            background-color: #25d366;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #128c7e;
        }
        .whatsapp-button {
            background-color: #25d366;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            width: 100%;
            text-align: center;
            margin-top: 20px;
            box-sizing: border-box;
        }
        .whatsapp-button:hover {
            background-color: #128c7e;
        }
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-top: 20px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
                max-width: 100%;
                box-sizing: border-box;
            }
            .logo {
                max-width: 120px;
            }
            .form-field input {
                padding: 10px;
                font-size: 14px;
            }
            button, .whatsapp-button {
                padding: 10px 15px;
                font-size: 14px;
            }
        }

        /* Decorative Elements */
        .heading {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .subheading {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }

        .cta-message {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #888;
        }

        .highlight {
            color: #25d366;
            font-weight: 600;
        }
    </style>
</head>
<body>
@section('head')
    @if(!empty($metaPixel))
        {!! $metaPixel !!}
    @endif
@endsection

<div class="form-container">
    <!-- Logo -->
    <img src="{{ asset('assets/images/logo-dark-sm.png') }}" alt="Logo" class="logo" style="width: 120px; height: auto;">

    <!-- Heading and Welcome Message -->
    <div class="heading">Selamat Datang di Lamonte Kami!</div>
    <div class="cta-message">Kami siap membantu Anda! Isi form di bawah ini untuk melanjutkan atau hubungi CS kami jika ada pertanyaan.</div>

    <!-- Nama dan Nomor CS -->
    @if ($assignedCSName != 'Tidak tersedia' && $assignedCSNumber != 'Tidak tersedia')
        <div class="cs-info">
            <p class="subheading">Hubungi CS Kami:</p>
            <p>{{ $assignedCSName }} - <a href="https://wa.me/{{ $assignedCSNumber }}" target="_blank" class="no-underline highlight">{{ $assignedCSNumber }}</a></p>
        </div>
    @else
        <div class="cs-info">
            <p><strong>Tidak tersedia</strong></p>
        </div>
    @endif

    @if ($campaignType == 'cta_form')
        @if(!empty($formField))
        <form action="{{ route('form.store', ['namaCampaign' => $campaignName]) }}" method="POST">
                @csrf
                <input type="hidden" name="assignedCSNumber" value="{{ $assignedCSNumber }}">

                @foreach($formField as $field)
                    <div class="form-field">
                        <label for="{{ $field }}">{{ ucfirst($field) }}</label>
                        <input type="text" name="{{ $field }}" id="{{ $field }}" required>
                    </div>
                @endforeach
                <button type="submit">Kirim</button>
            </form>
        @else
            <p>Data tidak tersedia, jika ingin menghubungi CS, klik tombol di bawah ini:</p>
            <a href="https://wa.me/{{ $assignedCSNumber }}" class="whatsapp-button" target="_blank">
                Hubungi CS via WhatsApp
            </a>
        @endif
    @elseif ($campaignType == 'redirect')
        <p>{{ $campaignDetail['templateMessage'] ?? 'Anda akan diarahkan ke halaman berikutnya. Terima kasih!' }}</p>
        <a href="https://wa.me/{{ $assignedCSNumber }}" class="whatsapp-button" target="_blank">
            Lanjutkan ke Halaman
        </a>
    @endif

    <!-- Error Handling -->
    @if (isset($error))
        <div class="alert">
            {{ $error }}
        </div>
    @endif
</div>

</body>
</html>
