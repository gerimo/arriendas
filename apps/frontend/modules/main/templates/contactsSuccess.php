<script src="/js/newDesign/despegar/TMForm.js"></script>
<link rel="stylesheet" href="/css/newDesign/despegar/form.css">
<div class="content">
  <div class="container">
    <div class="row">
      <div class="grid_6">
        <h2 class="mb1">contact information</h2>
        <div class="map">
        <figure class=" ">
<!--           <iframe src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Brooklyn,+New+York,+NY,+United+States&amp;aq=0&amp;sll=37.0625,-95.677068&amp;sspn=61.282355,146.513672&amp;ie=UTF8&amp;hq=&amp;hnear=Brooklyn,+Kings,+New+York&amp;ll=40.649974,-73.950005&amp;spn=0.01628,0.025663&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe>
 -->           <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.cl/maps?f=q&amp;source=s_q&amp;hl=es&amp;geocode=&amp;q=manuel+montt+1404&amp;aq=&amp;ie=UTF8&amp;hq=&amp;hnear=Manuel+Montt+1404,+Providencia,+Santiago,+Regi%C3%B3n+Metropolitana&amp;ll=-33.440679,-70.615253&amp;spn=0.005354,0.010568&amp;t=m&amp;z=14&amp;output=embed"></iframe><br /><small><a href="https://maps.google.cl/maps?f=q&amp;source=embed&amp;hl=es&amp;geocode=&amp;q=manuel+montt+1404&amp;aq=&amp;ie=UTF8&amp;hq=&amp;hnear=Manuel+Montt+1404,+Providencia,+Santiago,+Regi%C3%B3n+Metropolitana&amp;ll=-33.440679,-70.615253&amp;spn=0.005354,0.010568&amp;t=m&amp;z=14" style="color:#0000FF;text-align:left">Ver mapa más grande</a></small>
        </figure>
        <address>
          <dl>
                <dt class="text1 col2">
                    Manuel Montt 1404,
                    Providencia, Santiago.
                </dt>
                <dd><span>Telephone:</span>(02) 2640 2900</dd>
                <dd>E-mail: <a href="#" class="col3 td">soporte@arriendas.cl</a></dd>
          </dl>
         </address>
        </div>
      </div>
      <div class="grid_6">
        <h2 class="mb1">contact form</h2>
              <form id="form">
              <div class="success_wrapper">
              <div class="success-message">Contact form submitted</div>
              </div>
              <label class="name">
              <input type="text" placeholder="Name:" data-constraints="@Required @JustLetters" />
              <span class="empty-message">*This field is required.</span>
              <span class="error-message">*This is not a valid name.</span>
              </label>            
              <label class="email">
              <input type="text" placeholder="E-mail:" data-constraints="@Required @Email" />
              <span class="empty-message">*This field is required.</span>
              <span class="error-message">*This is not a valid email.</span>
              </label>
               <label class="phone">
                  <input type="text" placeholder="Phone:" data-constraints="@Required @JustNumbers"/>
                  <span class="empty-message">*This field is required.</span>
                  <span class="error-message">*This is not a valid phone.</span>
                  </label>
              <label class="message">
              <textarea placeholder="Message:" data-constraints='@Required @Length(min=20,max=999999)'></textarea>
              <span class="empty-message">*This field is required.</span>
              <span class="error-message">*The message is too short.</span>
              </label>
              <div>
              <div class="clear"></div>
              <div class="btns">
              <a href="#" data-type="submit" class="btn">Send</a></div>
              </div>
              </form>   
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){    
      $(".sf-menu").find(".current").removeClass("current"); 
      $(".sf-menu li:nth-child(5)").addClass("current");
});
</script>