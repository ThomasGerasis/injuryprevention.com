<script>
    localStorage.removeItem('token');
    localStorage.removeItem('email');
    localStorage.removeItem('username');
    let previousUrl = localStorage.getItem('previousUrl');
    if (!previousUrl){
        previousUrl = '<?php echo base_url();?>';
    }
    setTimeout(function(){
        window.location = previousUrl;
    }, 100);
</script>
