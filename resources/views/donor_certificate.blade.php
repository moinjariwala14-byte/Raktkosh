<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Certificate - {{ $donor->certificate_number }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background-color: #eef2f5; font-family: 'Times New Roman', Times, serif; color: #222; }
        .cert-wrapper {
            max-width: 820px;
            margin: 40px auto;
            background: #fff;
            padding: 45px 50px;
            border: 8px double #a80000;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            position: relative;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 110pt;
            color: rgba(168, 0, 0, 0.04);
            pointer-events: none;
            z-index: 0;
        }
        .cert-content { position: relative; z-index: 1; }
        .cert-header h3 { color: #a80000; letter-spacing: 1px; font-weight: bold; text-transform: uppercase; font-size: 16pt; margin-bottom: 4px; }
        .cert-title { font-size: 24pt; font-weight: bold; color: #111; margin: 18px 0 10px 0; text-transform: uppercase; }
        .donor-name { font-size: 26pt; font-weight: bold; color: #a80000; border-bottom: 2px solid #555; display: inline-block; padding: 0 35px 4px 35px; margin: 15px 0; }
        .cert-details-table { width: 100%; margin: 25px 0; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }
        .cert-details-table td { padding: 8px 12px; border: 1px solid #ddd; background: #fdfdfd; }
        .signatures-row { margin-top: 50px; }
        .sign-box { border-top: 1px solid #333; width: 180px; margin: 0 auto; padding-top: 5px; font-weight: bold; font-size: 9.5pt; font-family: Arial, sans-serif; }
        @media print {
            body { background: #fff; margin: 0; padding: 0; }
            .cert-wrapper { margin: 0 auto; box-shadow: none; border: 6px double #a80000; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="cert-wrapper">
        <div class="watermark"><i class="fa-solid fa-droplet"></i></div>
        <div class="cert-content text-center">
            <div class="cert-header">
                <h3><i class="fa-solid fa-droplet me-2"></i>Indian Red Cross Society</h3>
                <p class="text-muted mb-0" style="font-family: Arial, sans-serif; font-size: 9pt;">Central Blood Transfusion Services & Regional Management Network</p>
                <p style="font-family: Arial, sans-serif; font-size: 8.5pt; color: #666;">Affiliated Transfusion Center &bull; Navsari District Jurisdiction</p>
            </div>

            <hr style="border-top: 2px solid #a80000; opacity: 0.8; margin: 15px 0;">

            <div class="cert-title">Certificate of Recognition</div>
            <p style="font-size: 11pt; font-style: italic; color: #444; margin-bottom: 5px;">This certificate is proudly awarded in recognition of voluntary service to</p>

            <div class="donor-name">{{ $donor->full_name }}</div>

            <p style="font-size: 11pt; max-width: 620px; margin: 8px auto 18px auto; line-height: 1.6;">
                for successfully enrolling as a recognized <strong>Voluntary Blood Donor</strong>. Your commitment supports life-saving emergency transfusions for critical trauma and surgical care patients.
            </p>

            <table class="cert-details-table text-start">
                <tr>
                    <td style="width: 50%;"><strong>Certificate Serial:</strong> <span class="text-danger fw-bold">{{ $donor->certificate_number }}</span></td>
                    <td style="width: 50%;"><strong>Verified Blood Group:</strong> <span class="badge bg-danger fs-6">{{ $donor->blood_group }}</span></td>
                </tr>
                <tr>
                    <td><strong>Donor Gender:</strong> {{ $donor->gender }}</td>
                    <td><strong>Clinical Eligibility:</strong> <span class="text-success fw-bold"><i class="fa-solid fa-circle-check"></i> ELIGIBLE</span></td>
                </tr>
                <tr>
                    <td><strong>Registered Contact:</strong> +91 {{ $donor->phone }}</td>
                    <td><strong>Date of Issue:</strong> {{ date('d-m-Y') }}</td>
                </tr>
            </table>

            <div class="row signatures-row text-center">
                <div class="col-4"><div class="sign-box">Medical Officer</div></div>
                <div class="col-4"><div style="font-size: 8pt; color: #777; margin-top: -15px;"><i class="fa-solid fa-stamp fa-3x" style="opacity: 0.25;"></i><br>Authorized Registry</div></div>
                <div class="col-4"><div class="sign-box">Transfusion In-Charge</div></div>
            </div>
        </div>
    </div>

    <div class="text-center pb-5 no-print">
        <button onclick="window.print()" class="btn btn-danger btn-lg me-2 px-4 shadow">
            <i class="fa-solid fa-print me-2"></i>Print Certificate / Save PDF
        </button>
        <a href="/" class="btn btn-secondary btn-lg px-4 shadow">
            <i class="fa-solid fa-house me-2"></i>Return to Home
        </a>
    </div>
</div>

</body>
</html>
