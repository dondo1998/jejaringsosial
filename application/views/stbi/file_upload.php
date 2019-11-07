<div>
    <?php
        if ($this->session->flashdata('err') != '') {
            echo "<div class=\"alert alert-danger\" role=\"alert\">
                ".$this->session->flashdata('err')."
            </div>";
        }
    ?>
    <!-- <form action="<?=site_url('index.php/stbi/do_upload')?>" method="POST" enctype="multipart/form-data"> -->
    <?php echo form_open_multipart('index.php/stbi/do_upload');?>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <input type="text" class="form-control" id="deskripsi" placeholder="Masukkan Deskripsi" name="description" required>
        </div>
        <div class="form-group">
            <label for="myfile">Upload File</label>
            <input type="file" class="form-control" id="myfile" placeholder="Masukkan file" name="myfile" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>