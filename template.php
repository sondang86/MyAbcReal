<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title><site title/></title>
        <meta name="description" content="<site description/>">
        <meta name="keywords" content="<site keywords/>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
        <link href="/vieclambanthoigian.com.vn/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="/vieclambanthoigian.com.vn/css/bootstrap.css" rel="stylesheet">        
        <link rel="stylesheet" href="/vieclambanthoigian.com.vn/css/main.css">
        <link href="/vieclambanthoigian.com.vn/css/sky-forms.css" rel="stylesheet" type="text/css"/>
        <link href="/vieclambanthoigian.com.vn/css/commons.css" rel="stylesheet" type="text/css"/>
            
        <!--<script type="text/javascript" src="/vieclambanthoigian.com.vn/js/jquery.min.js"></script>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        
        <!-- bxSlider -->
        <script src="/vieclambanthoigian.com.vn/js/jquery.bxslider.min.js" type="text/javascript"></script>
        <link href="/vieclambanthoigian.com.vn/css/jquery.bxslider.css" rel="stylesheet" type="text/css"/>
        
        <!-- Include Date Range Picker -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
        
        <!--For save job-->
        <script>
            var sitePath = 'http://localhost/vieclambanthoigian.com.vn';
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div id="header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 languages-menu">
                            <site saved_jobs/> 
                            <!--<site languages_menu/>-->
                            <br/>
                        </div>
                    </div>
                    <div class="row">
                        <!--MENU AREA-->
                        <!--LOGO-->
                        <div class="col-md-2 hide-sm">
                            <site logo/>
                        </div>
                        <!--MENU-->
                        <div class="col-md-10 col-sm-12">
                            <div id="nav_menu">
                                <nav class="navbar navbar-inverse">
                                    <div class="container-fluid">
                                        <div class="navbar-header">
                                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span> 
                                            </button>
                                            <!--<a class="navbar-brand" href="#"><i class="fa fa-home" aria-hidden="true"></i> Trang chủ</a>-->
                                        </div>
                                        <div class="collapse navbar-collapse" id="myNavbar">
                                            <ul class="nav navbar-nav">
                                                <site menu/>
                                            </ul>
                                            <ul class="nav navbar-nav navbar-right">
                                                <li><site login_links/></li>                                                
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
                
            <site home_panel/>	
            
            <site carousel/>
                
            <div class="container main-container">
                <div class="row">
                        
                    <div class="col-md-8 col-sm-12 min-height-400">
                        
                        <site top_banners/>
                            
                        <site content/>
                            
                        <div class="clearfix"></div>
                        <br/>
                            
                        <center>			
                            <site bottom_banners/>
                        </center>
                    </div>
                    
                    <div class="col-md-4 col-sm-12">
                        
                        <site aside_content/>    
                           
                        <br/>
                        <h3 class="aside-header">
                            {M_ADVERTISEMENTS}
                        </h3>
                        <hr class="top-bottom-margin"/>
                            
                        <site side_column_banners/>
                            
                    </div>
                    
                    <div class="clearfix"></div>
                </div>
            </div>	
            	
                
            <div class="footer custom-back-color default-back-color">
                
                <div class="container">
                    <div class="row">
                        
                        <div class="col-md-4 bottom-links">
                            <h4 class="widget-title white-font">{M_QUICK_LINKS}</h4>
                            <div class="bottom-links-nav">
                                
                                <ul>
                                    <site bottom_menu/>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                            
                            
                            
                            
                            
                        <div class="col-md-4 bottom-links">
                            <h4 class="widget-title white-font">{M_LATEST_NEWS}</h4>
                                
                            <site news/>
                                
                        </div>
                            
                        <div class="col-md-4 bottom-links">
                            <h4 class="widget-title white-font">{M_CONNECT_WITH_US}</h4>
                                
                            <div class="textwidget">
                                <site connect_with_us/>
                            </div>
                        </div>
                            
                            
                    </div>
                    <div class="clearfix"></div>
                        
                        
                </div>	
                    
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="white-font pull-left">
                        © vieclambanthoigian.com.vn 2016 Beta
                    </div>
                    <div class="footer-credits text-center white-font pull-right">
                        
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>        
        <link href="/vieclambanthoigian.com.vn/css/jquery-confirm.min.css" rel="stylesheet" type="text/css"/>
        <script src="/vieclambanthoigian.com.vn/js/jquery-confirm.min.js" type="text/javascript"></script>
        <script src="/vieclambanthoigian.com.vn/js/jquery.modal.js" type="text/javascript"></script>
        <script src="/vieclambanthoigian.com.vn/js/_functions.js" type="text/javascript"></script>
        <script src="/vieclambanthoigian.com.vn/js/functions.js"></script>
        <script src="/vieclambanthoigian.com.vn/js/bootstrap.min.js"></script>
        <link href="/vieclambanthoigian.com.vn/css/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>
        <script src="/vieclambanthoigian.com.vn/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
        <script src="/vieclambanthoigian.com.vn/js/jquery.validate.min.js" type="text/javascript"></script>
    <site login_form/>
        
</body>
</html>