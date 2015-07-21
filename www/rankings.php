<!DOCTYPE html>
<html lang="en">

<head>
<!--
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="-1" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
-->

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Hide and Seek in Gamla Stan</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/grayscale.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    
    <style>
/*         *{            border: 1px solid black; } */
        table{
            margin-left:auto;
            margin-right:auto;
        }
        table td{
            padding:5px;
        }
        td{
            text-align: left;
        }
        th{
            text-align: right;
        }
        ul{
            text-align: left;
            font-size:medium;
            margin:5px; padding:5px;
        }
        li{
            background: pink;
            padding:5px; margin:5px;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

        
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <!-- registry Section -->
    <section id="about" class="container content-section text-center">
        <div class='row'>
            <div class='col-md-6'>
                <h1>Rankings <small>as of <?php echo date('d/m H:i:s'); ?></small></h1>
                    <?php 	
                        include 'rankings_login.php';
                        rescore($link);
                        display($link);                    
                    ?>  
            </div>
            <div class='col-md-6'>
                <h3>How scoring works:</h3>
                <ul>  
                    <li>Everyone starts off with 100 points, as long as they participate in some way (find or are found by at least one person).</li> 
                    <li> Those 100 points are distributed amongst however many people find him/her, with the hider getting a share. </li>
                <li> For example: I am found by three people. Each of those three people get 25 points, and I also get 25 points.</li>
                <li> Really good hiders who are never found at all get all 100 points to themselves. But that's not enough to win -- it's also important to find people. 🏆</li>
                <li> Often, the scores of high-scoring players will fluctuate depending on the scores of the low-scoring players. It's quite an intricate game 😄</li>
                <li> Everyone is found at least once, by default. Otherwise we divide by zero and the best hiders get infinity points, which isn't fair 😜</li>
                </ul>
            </div>
        </div>
    </section>
    
   
 <footer>
    <div class="container text-center">
        <br/>
    </div>
</footer>


    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/grayscale.js"></script>

    <script  type="text/javascript">
        $( document ).ready(function() {
            console.log( "ready!" );
        });
        
        $('input[type=text]').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
    
        $( "#add" ).click(function() {
            console.log('adding rows!');
            $("#body").append(" <input type='text' maxlength='4'  class='form-control' placeholder='XXXX' name='numbers[]'> ");
            $("#body").append(" <input type='text' maxlength='4'  class='form-control' placeholder='XXXX' name='numbers[]'> ");
            $("#body").append(" <input type='text' maxlength='4'  class='form-control' placeholder='XXXX' name='numbers[]'> ");
            $("#body").append(" <input type='text' maxlength='4'  class='form-control' placeholder='XXXX' name='numbers[]'> ");
            $("#body").append(" <input type='text' maxlength='4'  class='form-control' placeholder='XXXX' name='numbers[]'> ");
        });
    </script>

</body>

</html>
