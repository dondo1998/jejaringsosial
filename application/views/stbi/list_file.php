<div class="row">
    <?php
        foreach ($files as $file) { ?>
            <div class="col-md-6 col-xs-12 mb-3">
                <a href="<?=base_url("assets/files/$file->nama_file")?>" style="text-decoration:none">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="card-title"><?=$file->deskripsi?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?=$file->nama_file?></h6>
                        </div>
                    </div>
                </a>
            </div>
        <?php }
    ?>
</div>