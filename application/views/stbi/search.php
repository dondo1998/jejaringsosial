<div class = "mb-5 text-center">
    <img src="<?=base_url('assets/img/UNISBANK.png')?>" alt="" style="max-height:300px">
</div>
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <form class="card card-sm" action="<?=site_url('index.php/stbi/searching')?>" method="get">
            <div class="card-body row no-gutters align-items-center">
                <div class="col-auto">
                    <i class="fas fa-search h4 text-body"></i>
                </div>
                <!--end of col-->
                <div class="col">
                    <input class="form-control form-control-lg form-control-borderless" type="search" placeholder="Ketikan kata kunci" name="keyword">
                </div>
                <!--end of col-->
                <div class="col-auto">
                    <button class="btn btn-lg btn-success" type="submit">Search</button>
                </div>
                <!--end of col-->
            </div>
        </form>
    </div>
    <!--end of col-->
</div>