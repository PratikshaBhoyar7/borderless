<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EMR · Borderless</title>
  <!-- Bootstrap 5 minimal CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Font: Instrument Sans (clean) -->
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Instrument Sans', -apple-system, sans-serif;
      background-color: white;
      color: #1b1b18;
      margin: 0;
      padding: 1.5rem;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .container-small {
      max-width: 780px;
      margin: 0 auto;
    }
    /* compact, clean, white background */
    .btn-login {
      border: 1px solid #d4d4d0;
      color: #1b1b18;
      font-size: 0.8rem;
      font-weight: 500;
      padding: 0.25rem 1rem;
      border-radius: 20px;
      background-color: white;
      transition: all 0.1s;
    }
    .btn-login:hover {
      border-color: #a0a09a;
      background-color: #f9f9f9;
    }
    .btn-register {
      border: 1px solid #d4d4d0;
      color: #1b1b18;
      font-size: 0.8rem;
      font-weight: 500;
      padding: 0.25rem 1rem;
      border-radius: 20px;
      background-color: white;
    }
    .btn-register:hover {
      border-color: #a0a09a;
      background-color: #f9f9f9;
    }
    .card-emr-compact {
      background: white;
      border: 1px solid #efefed;
      border-radius: 20px;
      padding: 1.5rem 1.75rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.02);
      transition: 0.1s;
    }
    .logo-small {
      max-height: 70px;
      width: auto;
      margin-right: 10px;
    }
    .emr-badge {
      background-color: #f2f1ef;
      color: #1b1b18;
      font-size: 0.7rem;
      font-weight: 500;
      padding: 0.25rem 0.85rem;
      border-radius: 30px;
      letter-spacing: 0.2px;
      border: none;
    }
    .feature-icon {
      width: 1.25rem;
      height: 1.25rem;
      background-color: white;
      border: 1px solid #e0dfdb;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .feature-dot {
      width: 0.45rem;
      height: 0.45rem;
      background-color: #aeaca7;
      border-radius: 50%;
      display: inline-block;
    }
    .link-red-soft {
      color: #f53003;
      font-weight: 500;
      text-decoration: underline;
      text-underline-offset: 3px;
      text-decoration-thickness: 0.5px;
      font-size: 0.8rem;
    }
    .link-red-soft:hover {
      color: #c41c00;
    }
    .hero-compact {
      background-color: #faf6f2;  /* very light warm white */
      border-radius: 18px;
      padding: 1.25rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border: 1px solid #f1eeeb;
      height: 100%;
      min-height: 180px;
    }
    .credit-small {
      font-size: 0.7rem;
      color: #7c7b77;
      border-top: 1px solid #ecece8;
      padding-top: 1rem;
      margin-top: 1rem;
      text-align: center;
      letter-spacing: 0.2px;
    }
    .text-soft {
      color: #5e5e5a;
    }
    /* override bootstrap card background to stay white */
    .bg-white-custom {
      background-color: white !important;
    }
    .developed-link {
      color: #1b1b18;
      font-weight: 500;
      text-decoration: underline;
      text-decoration-color: #cbcbc5;
      text-underline-offset: 3px;
    }
    .developed-link:hover {
      color: #000;
    }
    /* small adjustments */
    @media (max-width: 576px) {
      body { padding: 1rem; }
      .card-emr-compact { padding: 1.25rem; }
    }
  </style>
</head>
<body class="bg-white">

  <!-- minimal header: only login/register buttons -->
  <div class="container-small d-flex justify-content-end mb-3">
    <div class="d-flex gap-2">
      <!--<a href="/login" class="btn btn-login">Log in</a>-->
      <!--<a href="/register" class="btn btn-register">Register</a>-->
    </div>
  </div>

  <!-- main compact card – completely white background, small footprint -->
  <div class="container-small d-flex align-items-center flex-grow-1" style="margin-top: 0.25rem;">
    <div class="row g-3 w-100">
      
      <!-- LEFT: logo + EMR info + features + developed by -->
      <div class="col-12 col-md-7">
        <div class="card-emr-compact h-100 d-flex flex-column bg-white-custom">
          
          <!-- logo row: small but clear -->
          <div class="d-flex align-items-center mb-3">
            <img src="https://www.borderlessworldfoundation.org/images/logo.png" 
                 alt="Borderless logo" 
                 class="logo-small">
            <span class="emr-badge ms-2">EMR Application</span>
          </div>
          
          <!-- headline small -->
          <h1 class="fs-6 fw-semibold mb-1" style="letter-spacing: -0.01em;">Borderless EMR</h1>
          <p class="text-soft small mb-2" style="font-size: 0.75rem; max-width: 95%;">
            Electronic Medical Records for humanitarian clinics.<br>Secure, offline‑first, sovereign.
          </p>
          
          <!-- minimal feature list (very compact) -->
          <ul class="list-unstyled mb-2 small">
            <li class="d-flex gap-2 mb-2 align-items-center">
              <span class="feature-icon"><span class="feature-dot"></span></span>
              <span class="text-soft" style="font-size: 0.75rem;">FHIR® ready · Interoperable</span>
            </li>
            <li class="d-flex gap-2 mb-2 align-items-center">
              <span class="feature-icon"><span class="feature-dot"></span></span>
              <span class="text-soft" style="font-size: 0.75rem;">Offline-first · mesh sync</span>
            </li>
            <li class="d-flex gap-2 align-items-center">
              <span class="feature-icon"><span class="feature-dot"></span></span>
              <span class="text-soft" style="font-size: 0.75rem;">HIPAA / GDPR compliant</span>
            </li>
          </ul>
          
          <!-- very subtle login suggestion + developed by (exact) -->
          <div class="mt-auto d-flex align-items-center justify-content-between pt-2">
            <a href="/login" class="link-red-soft small">
              Log in to dashboard
              <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="margin-left: 2px;">
                <path d="M7.70833 6.20834V2.79167H3.54167M2.5 7.25L7.5 2.75" stroke="currentColor" stroke-width="0.8" stroke-linecap="square"/>
              </svg>
            </a>
            <span class="small text-soft" style="font-size: 0.65rem;">
              developed by 
              <a href="https://yavittech.com" target="_blank" class="developed-link" style="font-size: 0.65rem;">yavittech.com</a>
            </span>
          </div>
        </div>
      </div>
      
      <!-- RIGHT: compact visual / logo presence (small, clean) -->
      <div class="col-12 col-md-5">
        <div class="hero-compact bg-white-custom" style="background-color: #fff; border: 1px solid #efedeb;">
          <!-- small logo centered (just enough) -->
          <div class="d-flex flex-column align-items-center">
            <div class="bg-white p-2" style="border: 1px solid #f0eeea;">
              <img src="https://www.borderlessworldfoundation.org/images/logo.png" 
                   alt="foundation" 
                   style="width: 178px; height: auto;">
            </div>
            <h2 class="fs-6 fw-medium mt-2 mb-0" style="color: #1b1b18;">borderless</h2>
            <span class="small text-secondary" style="font-size: 0.6rem;">since 2010 · 14 countries</span>
            
            <!-- quick login hint (transparent) -->
            <a href="/login" class="btn btn-sm btn-outline-secondary mt-2 px-3 py-1" 
               style="border-radius: 40px; border-color: #dbdbd7; color: #2c2c2a; font-size: 0.7rem; background: white;">
              ➤ Access EMR
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- footer credit: developed by yavittech.com (clean and small) -->
  <div class="container-small credit-small d-flex justify-content-between align-items-center">
    <span class="small text-secondary">EMR · Borderless World</span>
    <span class="small">
      developed by 
      <a href="https://yavittech.com" target="_blank" class="developed-link">yavittech.com</a>
    </span>
  </div>

  <!-- optional Bootstrap bundle (lightweight) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>