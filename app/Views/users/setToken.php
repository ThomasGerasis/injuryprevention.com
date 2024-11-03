<script>
    window.localStorage.setItem('token', '<?=$personalToken?>');
    let previousUrl = localStorage.getItem('previousUrl');
    if (!previousUrl){
        previousUrl = '<?php echo base_url();?>';
    }
    setTimeout(function(){
        window.location = previousUrl;
    }, 100);
</script>