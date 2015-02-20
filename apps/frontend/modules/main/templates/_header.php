
<header>
<div class="container">
    <div class="row">
      <div class="grid_12">
      <div class="h_top">
        Reservation Centre: <span class="col1">(800) 2345-7896</span>
        <div class="socials"><a href="https://twitter.com/arriendas" class="fa fa-twitter col1"></a><a href="https://www.facebook.com/arriendaschile?fref=ts" class="fa fa-facebook"></a></div>
      </div>
     </div>
    </div>
  </div>
  <div id="stuck_container">
  <div class="container">
    <div class="row">
      <div class="grid_12">
        <h1><a href="<?php echo url_for('homepage') ?>">
            <img src="images/newDesign/despegar/logo.png" alt="Your Happy Family">
        </a></h1>   
        <div class="menu_block ">
          <nav class="horizontal-nav full-width horizontalNav-notprocessed">
            <ul class="sf-menu">
             <li class="current" ><a href="<?php echo url_for('homepage') ?>">Home</a>
               <ul>
                  <li><a href="#">Lorem ipsum dolor</a></li>
                  <li><a href="#">Conse ctetur </a></li>
                  <li><a href="#">Elit sed do eiusmod </a></li>
                  <li><a href="#">Incididunt ut</a>
                    <ul>
                      <li><a href="#">Dolore ipsu</a></li>
                      <li><a href="#">Consecte</a></li>
                      <li><a href="#">Elit Conseq</a></li>
                    </ul>
                  </li>
                  <li><a href="#">Et dolore magna </a></li>
                  <li><a href="#">Ut enim ad minim </a></li>
               </ul>
             </li>
             <li><a href="<?php echo url_for('ourFleet') ?>">Our Fleet</a></li>
             <li><a href="<?php echo url_for('reservations') ?>">Reservations</a></li>
             <li><a href="<?php echo url_for('rentalPolicies') ?>">Rental Policies</a></li>
             <li><a href="<?php echo url_for('contacts') ?>">Contacts</a></li>
           </ul>
          </nav>
          <div class="clear"></div>       
        </div>  
        <div class="clear"></div>     
        
      </div>
    </div>
  </div>    
</div>
 <div class="container">
    <div class="row">
      <div class="grid_12">
        <div class="slider_wrapper ">
          <div id="camera_wrap" class="">
            <div data-src="images/newDesign/despegar/slide.jpg"></div>
            <div data-src="images/newDesign/despegar/slide1.jpg"></div>
            <div data-src="images/newDesign/despegar/slide2.jpg"></div>  
          </div>
        </div>   
      </div>
    </div>
  </div>
  <br>
</header>    
 <script>
$(document).ready(function(){
  jQuery('#camera_wrap').camera({
    loader: false,
    pagination: true ,
    minHeight: '250',
    thumbnails: false,
    height: '30.34188034188034%',
    caption: false,
    navigation: false,
    fx: 'mosaic'
  });
});
</script>