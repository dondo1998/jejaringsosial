<style>
    .form-control-borderless {
        border: none;
    }

    .form-control-borderless:hover, .form-control-borderless:active, .form-control-borderless:focus {
        border: none;
        outline: none;
        box-shadow: none;
    }
</style>   
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <form class="card card-sm" action="<?=site_url('index.php/stbi/searching')?>" method="get">
            <div class="card-body row no-gutters align-items-center">
                <div class="col-auto">
                    <i class="fas fa-search h4 text-body"></i>
                </div>
                <!--end of col-->
                <div class="col">
                    <input class="form-control form-control-lg form-control-borderless" type="search" value="<?=$this->input->get('keyword')?>" placeholder="Ketikan kata kunci" name="keyword">
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

<div class="row mt-3">
    <?php
        if(count($files) > 0) {
            foreach ($files as $file) { ?>
                <div class="col-12 mb-3">
                    <a href="<?=base_url("assets/files/$file[nama_file]")?>" style="text-decoration:none">
                        <div class="card">
                            <div class="card-body">
                            <h5 class="card-title"><?=$file['deskripsi']?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?=$file['sim']?></h6>
                            </div>
                        </div>
                    </a>
                </div>
            <?php }
        } else { ?>
            <div class="col-12 mb-3">
                <a href="#" style="text-decoration:none">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="card-title">No Data Found</h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php }
    ?>
</div>