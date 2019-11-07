<script>
    $('.hasil-stem').hide();
    $(".form-search").submit(function(e){
        $.post('<?=site_url('index.php/stbi/stemming')?>', {'word' : $('.word-stem').val() }, function(res) {
            console.log(res)
            $('.wordr').text(res.word);
            $('.stemr').text(res.stem);
            $('.hasil-stem').show();
        }, 'JSON');
        return false;
    });
</script>