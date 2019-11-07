<div class = "mb-5 text-center">
    <img src="<?=base_url('/assets/img/UNISBANK.png')?>" alt="" style="max-height:300px">
</div>
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <form class="card card-sm form-search">
            <div class="card-body row no-gutters align-items-center">
                <div class="col-auto">
                    <i class="fas fa-search h4 text-body"></i>
                </div>
                <!--end of col-->
                <div class="col">
                    <input class="form-control form-control-lg form-control-borderless word-stem" type="search" placeholder="Ketikan kata kunci">
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
<div class="row justify-content-center mt-3 hasil-stem">
    <table>
        <tr>
            <td>Kata Asli</td>
            <td class="px-3">:</td>
            <td class="wordr"></td>
        </tr>
        <tr>
            <td>Kata Stem</td>
            <td class="px-3">:</td>
            <td class="stemr"></td>
        </tr>
    </table>
</div>