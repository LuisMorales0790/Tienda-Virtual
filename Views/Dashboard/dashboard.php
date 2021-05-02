   <?php headerAdmin($data); ?>
    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i><?= $data['page_title'];  ?></h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/dashboard">Dashboard</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">Dashboard</div>
            <!--Variable de sesion con todos los datos del cliente -->
            <?php
            // dep($_SESSION['userData']); 
              //getPermisos(1);
              //dep($_SESSION['permisos']);
              //dep($_SESSION['permisosMod']);
             ?>
          </div>
          <?php 
              $requestApi = CurlConnectionGet(URLPAYPAL."/v2/checkout/orders/11B685125U0064903","applicaton/json",getTokenPaypal()); 
               dep($requestApi);
              $requesPost = CurlConnectionPost(URLPAYPAL."/v2/payments/captures/60J2372691443282S/refund", "applicaton/json",getTokenPaypal());
             dep($requesPost);

           ?>
        </div>
      </div>
    </main>
    <?php footerAdmin($data); ?>
    