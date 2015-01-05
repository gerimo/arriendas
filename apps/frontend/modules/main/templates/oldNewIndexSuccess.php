<div class="side-slide">
    <div id="wrapper">
        <div class="container">
            <header id="header">
                <div class="logo">
                    <a href="<?php echo url_for('main/index') ?>" class="logo-desktop"><img src="images/designResponsive/logo.svg" height="19" width="123" alt="Arriendas.cl"></a>
                    <a href="<?php echo url_for('main/index') ?>" class="logo-mobile"><img src="images/designResponsive/logo-mobile.svg" height="16" width="22" alt="Arriendas.cl"></a>
                </div>
                <div class="nav-bar">
                    <nav id="nav">
                        <a href="#" class="nav-opener">Menu</a>
                        <ul class="nav-desktop">
                            <li><a href="<?php echo url_for('como_funciona/index') ?>">¿CÓMO FUNCIONA?</a></li>
                            <li><a href="<?php echo url_for('en_los_medios/index') ?>">EN LAS NOTICIAS</a></li>
                            <li><a href="https://arriendascl.zendesk.com/forums" target="_blank">AYUDA</a></li>
                        </ul>
                        <div class="nav-drop">
                            <ul class="nav-mobile">
                                <li><a href="#">RESERVAS</a></li>
                                <li><a href="#">MI PERFIL</a></li>
                                <li><a href="#">MENSAJES</a></li>
                                <li><a href="#">CALIFICACIONES</a></li>
                                <li><a href="#">SUBE UN AUTO</a></li>
                            </ul>
                        </div>
                    </nav>
                    <div class="right-area">
                        <a href="tel:0223333714" class="tel-link">(02) 2 333 37 14</a>
                        <a href="<?php echo url_for('main/login') ?>" class="login"><span>INGRESA</span></a>
                    </div>
                </div>
            </header>
            <div class="slideshow">
                <div class="slideset">
                    <div class="slide">
                        <img src="images/designResponsive/img1.jpg" height="586" width="1281" alt="image description" class="img-desktop">
                        <img src="images/designResponsive/img1-small.jpg" height="115" width="320" alt="image description" class="img-mobile">
                        <div class="outer">
                            <div class="text-holder">
                                <div class="text-area">
                                    <h1>ARRIENDA EL AUTO DE UN VECINO DESDE $17.000 FINALES</h1>
                                    <span class="sub-heading">en precios finales, todo incluído</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/designResponsive/img1.jpg" height="586" width="1281" alt="image description" class="img-desktop">
                        <img src="images/designResponsive/img1-small.jpg" height="115" width="320" alt="image description" class="img-mobile">
                        <div class="outer">
                            <div class="text-holder">
                                <div class="text-area">
                                    <h1>ARRIENDA EL AUTO DE UN VECINO DESDE $17.000 FINALES</h1>
                                    <span class="sub-heading">en precios finales, todo incluído</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-holder">
                <form accept-charset="#" class="from-search">
                    <span class="ico-search">search</span>
                    <input class="direction" id="direction" placeholder="Dirección" type="text">
                    <select class="region" id="region">
                        <option disabled value="<?php echo $Region->getCodigo() ?>"><?php echo $Region->getNombre() ?></option>
                    </select>
                    <select class="comuna" id="comuna">
                        <option value="0">Seleccione una comuna</option>
                        <?php foreach ($Comunas as $Comuna): ?>
                            <option value="<?php echo $Comuna->getCodigoInterno() ?>"><?php echo ucwords(strtolower($Comuna->getNombre())) ?></option>
                        <?php endforeach ?>
                    </select>
                    <input class="from datetimepicker" id="from" placeholder="Desde" type="text">
                    <input class="to datetimepicker" id="to" placeholder="Hasta" type="text">
                    <!-- <input id="search" type="submit" value="BUSCAR"> -->
                </form>
            </div>
        </div>
        <main id="main" role="main">
            <section class="tab-holder">
                <div class="top-area">
                    <strong class="heading">Filtros</strong>
                    <form action="#" class="filters">
                        <div class="row"><input type="checkbox" name="filter" id="automatic">
                        <label for="automatic">Automático</label></div>
                        <div class="diesel row"><input type="checkbox" name="filter" id="diesel">
                        <label for="diesel">Diesel</label></div>
                        <div class="low-consumption row"><input type="checkbox" name="filter" id="low">
                        <label for="low">Bajo Consumo</label></div>
                        <div class="row"><input type="checkbox" name="filrer" id="pasenger">
                        <label for="pasenger">Más de 5 pasajeros</label></div>
                    </form>
                </div>
                <div class="tab-area">
                    <ul class="tabset">
                        <li class="active"><a id="toMapList" href="#tab1"><span>MAPA</span></a></li>
                        <li><a id="toList" href="#tab2"><span>LISTA</span></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab1">
                            <div class="map-holder" id="map" style="height:575px">
                            </div>
                            <div class="list" id="map-list">
                                <div id="map-list-loading" class="loading" style="text-align: center; margin-top: 100px"><?php echo image_tag('ajax-loader.gif', array("width" => "80px", "height" => "80px")) ?></div>
                                <div id="map-list-container">
                                    <!-- <article class="box">
                                        <div class="img-holder"><img src="images/designResponsive/img3.jpg" height="99" width="134" alt="image description"></div>
                                        <div class="text-area">
                                            <h2><a href="#">Kia Morning, <span>City Car</span></a></h2>
                                            <span class="sub-heading">A 2 km Metro <strong>Tobalaba</strong></span>
                                            <span class="price">$18.000</span>
                                            <a href="#" class="reserve">RESERVAR</a>
                                        </div>
                                    </article>
                                    <article class="box odd">
                                        <div class="img-holder"><img src="images/designResponsive/img3.jpg" height="99" width="134" alt="image description"></div>
                                        <div class="text-area">
                                            <h2><a href="#">Kia Morning, <span>City Car</span></a></h2>
                                            <span class="sub-heading">A 2 km Metro <strong>Tobalaba</strong></span>
                                            <span class="price">$18.000</span>
                                            <a href="#" class="reserve">RESERVAR</a>
                                        </div>
                                    </article> -->
                                </div>
                                <!-- <a class="more" href="#">ver más</a> -->
                            </div>
                        </div>
                        <div id="tab2">
                            <div class="data-holder" id="list">
                                <div id="list-loading" class="loading" style="text-align: center; margin-top: 100px"><?php echo image_tag('ajax-loader.gif', array("width" => "80px", "height" => "80px")) ?></div>
                                <div id="list-container">
                                    <article class="box">
                                        <div class="img-holder"><img src="images/designResponsive/img3.jpg" height="99" width="134" alt="image description"></div>
                                        <div class="text-area">
                                            <h2><a href="#">Kia Morning, <span>City Car</span></a></h2>
                                            <span class="sub">Providencia </span>
                                            <span class="sub-heading">A 2 km Metro <strong>Tobalaba</strong></span>
                                            <span class="price">$18.000</span>
                                            <a href="#" class="reserve">RESERVAR</a>
                                        </div>
                                    </article>
                                    <article class="box">
                                        <div class="img-holder"><img src="images/designResponsive/img3.jpg" height="99" width="134" alt="image description"></div>
                                        <div class="text-area">
                                            <h2><a href="#">Kia Morning, <span>City Car</span></a></h2>
                                            <span class="sub">Providencia </span>
                                            <span class="sub-heading">A 2 km Metro <strong>Tobalaba</strong></span>
                                            <span class="price">$18.000</span>
                                            <a href="#" class="reserve">RESERVAR</a>
                                        </div>
                                    </article>
                                    <a class="more" href="#">ver más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="video-holder">
                <div class="video-box">
                    <h2 class="title"><span>¿Cómo Funciona?</span></h2>
                    <iframe src="//player.vimeo.com/video/45668172" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
            </section>
            <section class="block">
                <div class="price-holder">
                    <h2 class="title"><span>Compara Precios</span></h2>
                    <div class="table-holder">
                        <table>
                            <tr>
                                <th></th>
                                <th><span class="th"><img src="images/designResponsive/logo-avis.svg" height="18" width="52" alt="AVIS"></span></th>
                                <th><span class="th"><img src="images/designResponsive/logo-hertz.svg" height="19" width="61" alt="Hertz"></span></th>
                                <th><span class="th"><img src="images/designResponsive/logo-eurorpcar.svg" height="21" width="112" alt="Europcar"></span></th>
                                <th class="last"><span class="th"><img src="images/designResponsive/logo.svg" height="19" width="123" alt="Arriendas.cl"></span></th>
                            </tr>
                            <tr>
                                <td><span class="td">Mediano</span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td class="last"><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                            </tr>
                            <tr>
                                <td><span class="td">CamionetaSUV</span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td class="last"><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                            </tr>
                            <tr>
                                <td><span class="td">Furgon Utilitario</span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td class="last"><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                            </tr>
                            <tr>
                                <td><span class="td">City Car Economico</span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td class="last"><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                            </tr>
                        </table>
                    </div>
                    <p>Precios con IVA, aplicando descuento por reservas en internet, con seguro de daños, robo y accidentes personales. Muestra tomada 4/4/2013 en sus páginas de internet</p>
                </div>

                <div class="logo-holder">
                    <h2 class="title"><span>Arriendas en las noticias</span></h2>
                    <div class="carousel">
                        <div class="mask">
                            <div class="slideset">
                                <div class="slide">
                                    <ul class="logo-list">
                                        <li style="text-align: center"><a href="http://www.t13.cl/videos/actualidad/arrienda-tu-auto-es-la-nueva-tendencia-entre-los-chilenos"><img src="images/logos_canais/13.png" alt="Canal 13" style="margin: 0"></a></li>
                                        <li style="text-align: center"><a href="http://cnnchile.com/noticia/2014/01/10/arriendas-el-emprendimiento-que-permite-arrendar-tu-propio-auto"><img src="images/logos_canais/LogoCNN.png" alt="CNN Chile" style="margin: 0"></a></li>
                                        <li style="text-align: center"><a href="http://www.24horas.cl/nacional/rent-a-car-vecino-la-nueva-forma-de-viajar-906946"><img src="images/logos_canais/logotvn2.png" alt="TVN" style="margin: 0"></a></li>
                                        <li style="text-align: center"><a href="http://www.emol.com/noticias/economia/2012/07/27/552815/emprendedor-estrenara-primer-sistema-de-arriendo-de-vehiculos-por-hora-de-chile.html"><img src="images/logos_canais/LogoEmol.png" alt="EMOL" style="margin: 0"></a></li>
                                    </ul>
                                </div>
                                <div class="slide">
                                    <ul class="logo-list">
                                        <li><a href="http://www.lun.com/lunmobile//pages/NewsDetailMobile.aspx?IsNPHR=1&dt=2012-10-23&NewsID=0&BodyId=0&PaginaID=6&Name=6&PagNum=0&SupplementId=0&Anchor=20121023_6_0_0"><img src="images/logos_canais/LogoLUN.png" alt="Las Últimas Noticias"></a></li>
                                        <li><a href="http://www.tacometro.cl/prontus_tacometro/site/artic/20121030/pags/20121030152946.html"><img src="images/logos_canais/LogoPublimetro.png" alt="Publimetro"></a></li>
                                        <li><a href="http://www.lasegunda.com/Noticias/CienciaTecnologia/2012/08/774751/arriendascl-sistema-de-alquiler-de-autos-por-horas-debuta-en-septiembre"><img src="images/logos_canais/LogoLaSegunda.png" alt="La Segunda"></a></li>
                                        <li><a href="http://www.lacuarta.com/noticias/cronica/2013/08/63-157571-9-ahora-puedes-arrendar-el-automovil-de-tu-vecino.shtml"><img src="images/logos_canais/LogoLaCuarta.png" alt="La Cuarta"></a></li>
                                    </ul>
                                </div>
                                <div class="slide">
                                    <ul class="logo-list">
                                        <li><a href="http://www.diariopyme.cl/arrienda-tu-auto-y-gana-dinero-extra-a-fin-de-mes/prontus_diariopyme/2013-06-23/212000.html"><img src="images/logos_canais/LogoDiarioPyme.png" alt="Diario PYME"></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <a class="btn-prev" href="#">Previous</a>
                        <a class="btn-next" href="#">Next</a>
                    </div>
                </div>
            </section>
            <section class="testimonials">
                <img src="images/designResponsive/img4.jpg" height="609" width="1281" alt="image description">
                <div class="outer">
                    <div class="holder">
                        <h2>TESTIMONIOS</h2>
                        <blockquote>
                            <q>Puedo arrendar desde mi casa, en cualquier horario, sin tarjeta de crédito. Es el mismo auto que en un rent a car pero 30% más barato.</q>
                            <cite><strong>Javiera Cruzat,</strong> <em>Usuario de Arriendas</em></cite>
                        </blockquote>
                    </div>
                </div>
            </section>
            <section class="terms-box">
                <h2 class="title"><span>Condiciones de Arriendo</span></h2>
                <div class="text-area">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus. </p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus. </p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus. </p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus. </p>
                </div>
                <div class="link-holder">
                    <a href="<?php echo url_for('main/login') ?>">INGRESAR</a>
                </div>
            </section>
        </main>
    </div>
</div>