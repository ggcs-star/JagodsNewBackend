<section class="franchise-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Jagdai Foods Franchise Formats</h2>
            <p class="text-muted">
                Choose the franchise model that best suits your investment and business goals.
            </p>
        </div>

        <div class="row g-4">

            <!-- QSR Franchise -->
           <div class="col-lg-2 col-md-4 col-6">
                <div class="franchise-card">
                    <img src="{{ asset('frontend/images/franchise/default.jpg') }}" alt="QSR Franchise">
                    <div class="card-body">
                        <h5>QSR Franchise</h5>
                    </div>
                </div>
            </div>

            <!-- Internet Restaurant -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="franchise-card">
                    <img src="{{ asset('frontend/images/franchise/default.jpg') }}" alt="Internet Restaurant Franchise">
                    <div class="card-body">
                        <h5>Internet Restaurant </h5>
                    </div>
                </div>
            </div>

            <!-- Food Trolley -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="franchise-card">
                    <img src="{{ asset('frontend/images/franchise/default.jpg') }}" alt="Food Trolley Franchise">
                    <div class="card-body">
                        <h5>Food Trolley </h5>
                    </div>
                </div>
            </div>

            <!-- Food Tempo -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="franchise-card">
                    <img src="{{ asset('frontend/images/franchise/default.jpg') }}" alt="Food Tempo Franchise">
                    <div class="card-body">
                        <h5>Food Tempo </h5>
                    </div>
                </div>
            </div>

            <!-- Food Court -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="franchise-card">
                    <img src="{{ asset('frontend/images/franchise/default.jpg') }}" alt="Food Court Franchise">
                    <div class="card-body">
                        <h5>Food Court </h5>
                    </div>
                </div>
            </div>

            <!-- Influencer -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="franchise-card">
                    <img src="{{ asset('frontend/images/franchise/default.jpg') }}" alt="Influencer Franchise">
                    <div class="card-body">
                        <h5>Influencer Franchise</h5>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
.franchise-section{
    background:#f8f9fa;
}

.franchise-card{
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    border:1px solid #eee;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
    transition:.35s;
    height:100%;
}

.franchise-card:hover{
    transform:translateY(-8px);
    box-shadow:0 18px 35px rgba(0,0,0,.15);
}

.franchise-card img{
    width:100%;
    height:170px;
    object-fit:cover;
    transition:.4s;
}

.franchise-card:hover img{
    transform:scale(1.08);
}

.franchise-card .card-body{
    padding:15px 10px;
    text-align:center;
}

.franchise-card h5{
    margin:0;
    font-size:17px;
    font-weight:700;
    color:#222;
    line-height:1.4;
    transition:.3s;
}

.franchise-card:hover h5{
    color:#6C34CC;
}

@media(max-width:992px){
    .franchise-card img{
        height:160px;
    }
}

@media(max-width:768px){
    .franchise-card img{
        height:140px;
    }

    .franchise-card h5{
        font-size:15px;
    }
}
</style>