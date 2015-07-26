<link href="/css/newDesign/nblgr/pages/signin.css" rel="stylesheet" type="text/css">
<link href="/css/newDesign/nblgr/custom.css" rel="stylesheet">

<body>   
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
      
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img alt="Arriendas.cl" style="margin: -12px 0 0 0" src="/images/newDesign/logo.svg" height="44" width="123"></a>
            </div>

            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="">                     
                        <!-- <a href="./signup.html">
                            Create an Account
                        </a>         -->    
                    </li>

                    <li class="">            
                        <!-- <a href="#">
                            <i class="icon-chevron-left"></i>&nbsp;&nbsp; 
                            Back to Homepage
                        </a> -->
                    </li>
                </ul>
                </div>
        </div>
    </nav>

    <div class="account-container stacked">
        <div class="content clearfix">
            <form action="<?php echo url_for("login_do") ?>" method="post">
                <h1>Sign In</h1>        
                
                <div class="login-fields">
                    <p>Sign in using your registered account:</p>

                    <div class="field">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="" placeholder="Username" class="form-control input-lg username-field" />
                    </div> 
                    
                    <div class="field">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" value="" placeholder="Password" class="form-control input-lg password-field"/>
                    </div> 
                </div> 
                
                <div class="login-actions">
                    <!-- <span class="login-checkbox">
                        <input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
                        <label class="choice" for="Field">Keep me signed in</label>
                    </span> -->
                    <?php if($sf_user->getFlash('show')): ?>
                        <p class="error-message" >
                            <?php echo html_entity_decode($sf_user->getFlash('msg')) ?>
                        </p>
                    <?php endif ?>
                                        
                    <button class="login-action btn btn-primary">Sign In</button>
                </div> <!-- .actions -->
                
                <!-- <div class="login-social">
                    <p>Sign in using social network:</p>
                    
                    <div class="twitter">
                        <a href="#" class="btn_1">Login with Twitter</a>                
                    </div>
                    
                    <div class="fb">
                        <a href="#" class="btn_2">Login with Facebook</a>               
                    </div>
                </div> -->    
            </form>
        </div>
    </div>
</body>

<!-- Text Under Box -->
<!-- <div class="login-extra">
    Don't have an account? <a href="./signup.html">Sign Up</a><br/>
    Remind <a href="#">Password</a>
</div>  --><!-- /login-extra -->