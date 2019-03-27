<!-- Header -->

<?php include('include/header.php');?>

<!-- //Header -->


<!-- Body -->

  <!--start-->

  <!-- Left Side Bar -->
    <div class="slider_Wrapper aboutWrapper clearfix">
    <?php foreach ($about as $key => $value) {
        if($value['cms_slug'] == 'about-us.')
        {
          
    ?>
      <?php $img = base_url('assets/uploads/background_images/').$value['cms_image'];?>
      <div class="col8 aboutBanner" style="background: url(<?php echo $img;?>) no-repeat center center / cover;">
        <div class="bannerContent">
          <h1><?php echo $value['cms_heading'];?> <span>Download PDF profile <a href="<?php echo base_url('assets/uploads/download/').$value['cms_file'];?>" target="_blank"><img src="<?php echo base_url();?>assets/images/pdf.svg" alt="" width="20"></a></span></h1>

             <div class="content" id="full_content">
               <!--  <p id="para">   -->           
                  <?php echo $value['cms_description'];?>
             <!--   </p> -->
             </div>
                <a class="readMore" id="read_More" href="javascript:void(0)">READ MORE ></a>
                <a class="readMore" id="read_Less" href="javascript:void(0)" style="display:none;">READ LESS ></a>
                



        </div>
          
      </div>
  <!-- //Left Side Bar-->
      
  <!-- Right Side Bar -->

        <?php include('include/right-side.php');?>

  <!-- //Right Side Bar -->
      <?php }};?> 
    </div>
    <!--end-->

<!-- //Body -->

<!-- Footer -->

<?php include('include/footer.php');?>

<!-- //Footer -->

