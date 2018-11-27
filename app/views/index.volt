<!DOCTYPE html>
<html lang="it">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Gestionale per la fiera Fa la cosa giusta Umbria">
    <meta name="author" content="Desegno s.r.l.">

    {{ get_title() }}

    <!-- Bootstrap core CSS-->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin.css" rel="stylesheet">

    {{ assets.outputCss() }}


  </head>

  <body id="page-top" {{ elements.getBodyClass() }}>

    {{ content() }}

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->

    <script src="/vendor/datatables/jquery.dataTables.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin.min.js"></script>
    <script src="/js/falacosagiusta.js"></script>
    {{ assets.outputJs() }}

    <!-- Demo scripts for this page-->
    <!--script src="/js/demo/datatables-demo.js"></script-->
    <!--script src="js/demo/chart-area-demo.js"></script-->

  </body>

</html>

