<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=base_url('/assets/css/bootstrap.min.css')?>" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="<?=base_url('/assets/js/jquery-3.4.1.min.js')?>"></script>
    <!-- <link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet"> -->
    <!-- <script src="http://hayageek.github.io/jQuery-Upload-File/4.0.11/jquery.uploadfile.min.js"></script> -->
    <title><?=isset($title) ? $title : 'STBI'?></title>
  </head>
  <body>
  <?php
    if (isset($css)) {
      $this->load->view($css);
    }
  ?>
  
  <!-- Nav -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?=site_url()?>"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item <?=$this->uri->segment(2) == null || $this->uri->segment(2) == "searching" ? "active" : ''?>">
          <a class="nav-link" href="<?=site_url()?>">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?=$this->uri->segment(2) == "search_rank" || $this->uri->segment(2) == "searching_rank" ? "active" : ''?>" href="<?=site_url('index.php/stbi/search_rank')?>">HITS</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?=$this->uri->segment(2) == "stem" ? "active" : ''?>" href="<?=site_url('index.php/stbi/stem')?>">Stem</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?=$this->uri->segment(2) == "list_file" ? "active" : ''?>" href="<?=site_url('index.php/stbi/list_file')?>">File</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?=$this->uri->segment(2) == "upload_file" ? "active" : ''?>" href="<?=site_url('index.php/stbi/upload_file')?>">Upload</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-3">
    <?php $this->load->view($content) ?>
  </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?=base_url('/assets/js/popper.min.js')?>"></script>
    <script src="<?=base_url('/assets/js/bootstrap.min.js')?>"></script>
    <?=isset($js) ? $this->load->view($js,NULL,true) : ''?>
  </body>
</html>