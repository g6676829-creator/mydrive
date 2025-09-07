<?php
session_start();
if(empty($_SESSION['user']))
{
    header("location: login.php");
}

$userEmail = $_SESSION['user'];
require("php/database.php");
$sql = "SELECT * FROM users WHERE email = '$userEmail'";
$response = $db->query($sql);
$aa = $response->fetch_assoc();
$userName = $aa['name'];
$totalSpace = $aa['storage'];
$usedSpace = round($aa['used_storage'],2);
$per = round(($usedSpace*100)/$totalSpace,2);
$aa['id'];
$userTableName = "user_".$aa['id'];
$freeSpace = $totalSpace-$usedSpace;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>profile</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>

    <style type="text/css">
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .mainContainer{
            width: 100%;
            height: 100vh;
            display: flex;
        }
        .left{
            width: 17%;
            height: 100%;
            background-color: #080429;
        }
        .right{
            width: 83%;
            height: 100%;
            overflow: auto;
        }
        .profilePicture,.profileInput{
            width: 80px;
            height: 80px;
            border: 3px solid white;
            border-radius: 100%;
            background-image: url("images/doc.png");
            position: relative;
        }
        .profileInput{
            cursor: pointer;
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
        }
        .p_name,.planstxt{
            background: linear-gradient(90deg,#00ffff,#ff00c3);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .line{
            color: white !important;
            width: 100%;
        }
        .logout_btn a{
            text-decoration: none !important;
        }
        .prgBg{
            width: 80%;
            height: 10px;
            background-color: white;
            border-radius: 10px;
        }
        .prgBar{
            height: 100%;
            border-radius: 10px;
        }
        .thumb{
            width: 75px;
            height: 75px;
        }
        .myMenu{
            width: 100%;
            list-style: none;
            margin: 0;
            padding: 0;
            margin-top: 10px;
        }
        .fileMenu{
            width: 100%;
            padding: 5px;
            padding-left: 20px;
            color: #fff;
        }
        .fileMenu:hover{
            background-color: #fff;
            color: #080429;
            cursor: pointer;
            transition: .3s;
        }
        .loadingMsg{
            width: 100%;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
        }
        .del:hover{
            cursor: pointer;
            color: red;
            transition: .3s;
        }
        .star:hover{
            cursor: pointer;
            transition: .3s;
        }

        /*device responsive design start*/
        .smallMenu{
            width: 0;
            height: 100%;
            background-color: #080429;
            overflow: hidden;
            transition: 0.4s;
            position: absolute; 
            top: 0;
            left: 0;
            z-index: 100000;
        }
        .cut,.bars{
            cursor: pointer;
            position: absolute;
            top: 0;
            left: 0;
        }
        @media(max-width: 769px)
        {
            .right{
                width: 100%;
            }
        }
        @media(max-width: 321px){
            .search{
                width: 80%;
            }
        }

        /*device responsive design end*/

    </style>
</head>
<body>
    <div class="mainContainer">
        
        <div class="left pt-4 d-none d-lg-block">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <div class="profilePicture d-flex justify-content-center align-items-center">
                    <i class="fa fa-user fs-1 text-light userIcon"></i>
                    <input class="profileInput" type="file">
                </div>
                <span class="fs-5 text-light mt-2 p_name"><?php echo $userName;?></span>
                <hr class="line">
                <button class="btn btn-light btn-sm rounded-pill upload"><i class="fa fa-upload"></i>Upload File</button>
                <hr class="line">
                <div class="prgBg mt-3 d-none uProgress">
                    <div class="prgBar bg-primary uPbar text-center text-light" style="width:0%;"></div>
                </div>
                
                <ul class="myMenu">
                    <li class="fileMenu myFile" p_link="my_file"><i class="fa-solid fa-folder-open"></i> My Files</li>
                    <li class="fileMenu" p_link="f_file"><i class="fa-solid fa-star"></i> Favourite Files</li>
                    <li class="fileMenu" p_link="buy_storage"><i class="fa-solid fa-cart-shopping"></i> Buy Storage</li>
                </ul>
                
                <div class="uploadMsg"></div>

                <span class="fs-5 mt-2 text-light"><i class="fa-solid fa-database"></i> Storage</span>
                <div class="prgBg m-2">
                    <div class="prgBar bg-primary pb" style="width: <?php echo $per ?>%;"></div>
                </div>
                <span class="text-light"><span class="tus"><?php echo $usedSpace?></span>/<?php echo $totalSpace ?></span>
                
                <hr class="line">
                <button class="btn-danger btn btn-sm logout_btn"><a href="php/logout.php" class="text-light">Logout !</a></button>
            </div>
        </div>

        <!-- device responsive menu start -->
        <div class="smallMenu pt-4 d-block d-lg-none">

            <i class="fa-solid fa-x text-light fs-3 cut pt-3 ps-3"></i>

            <div class="d-flex justify-content-center align-items-center flex-column">
                <div class="profilePicture d-flex justify-content-center align-items-center">
                    <i class="fa fa-user fs-1 text-light userIcon"></i>
                    <input class="profileInput" type="file">
                </div>
                <span class="fs-5 text-light mt-2 p_name"><?php echo $userName;?></span>
                <hr class="line">
                <button class="btn btn-light btn-sm rounded-pill upload"><i class="fa fa-upload"></i>Upload File</button>
                <div class="prgBg mt-3 d-none uProgress">
                    <div class="prgBar bg-primary uPbar text-center text-light" style="width:0%;"></div>
                </div>
                
                <ul class="myMenu">
                    <li class="fileMenu myFile mm" p_link="my_file"><i class="fa-solid fa-folder-open"></i> My Files</li>
                    <li class="fileMenu mm" p_link="f_file"><i class="fa-solid fa-star"></i> Favourite Files</li>
                    <li class="fileMenu mm" p_link="buy_storage"><i class="fa-solid fa-cart-shopping"></i> Buy Storage</li>
                </ul>
                
                <div class="uploadMsg"></div>

                <span class="fs-5 mt-2 text-light"><i class="fa-solid fa-database"></i> Storage</span>
                <div class="prgBg m-2">
                    <div class="prgBar bg-primary pb" style="width: <?php echo $per ?>%;"></div>
                </div>
                <span class="text-light"><span class="tus"><?php echo $usedSpace?></span>/<?php echo $totalSpace ?></span>
                
                <hr class="line">
                <button class="btn-danger btn btn-sm logout_btn"><a href="php/logout.php" class="text-light">Logout !</a></button>
            </div>
        </div>
        <!-- device responsive menu end -->

        <div class="right">
            <nav class="navbar bg-body-tertiary sticky-top shadow-sm">

                 <i class="fa-solid fa-bars fs-3 pt-3 ps-3 d-block d-lg-none bars"></i>

                <div class="container-fluid">
                    
                    <form class="d-flex ms-auto search" role="search">
                        <input class="form-control me-2 searchInput" type="search" placeholder="Search" aria-label="Search">
                        <!-- <button class="btn btn-outline-primary" type="submit">Search</button> -->
                    </form>
                </div>
            </nav>
            <div class="content p-4"></div>
            <div class="loadingMsg d-none"></div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".upload").click(function(){
                var input = document.createElement("INPUT");
                input.setAttribute("type","file");
                input.click();

                input.onchange = function(){
                    $(".uProgress").removeClass("d-none");
                    var file = new FormData();
                    file.append("data",input.files[0]);
                    var ufSize = Math.floor(input.files[0].size/1024/1024);
                    //ufSize is stand for 'upoading file size'
                    var avStorage = <?php echo $freeSpace ?>;
                    //avStorage is stand for available storage

                    if(ufSize<avStorage)
                    {
                        $.ajax({
                            type : "POST",
                            url : "php/upload.php",
                            data : file,
                            processData : false,
                            contentType : false,
                            cache : false,
                            xhr : function(){
                                var request = new XMLHttpRequest();
                                request.upload.onprogress = function(e){
                                    var loaded = (e.loaded/1024/1024).toFixed(2);
                                    var total = (e.total/1024/1024).toFixed(2);
                                    var uPer = ((loaded*100)/total).toFixed(0);

                                    $(".uPbar").css("width",uPer+"%");
                                    $(".uPbar").html(uPer+"%");
                                }
                                return request;
                            },
                            success : function(response){
                                $(".uProgress").addClass("d-none");
                                var obj = JSON.parse(response);
                                
                                if(obj.msg == "file uploaded successfully")
                                {
                                    var newPer = (obj.usedFileSize*100)/<?php echo $totalSpace;?>

                                    $(".tus").html(obj.usedFileSize);
                                    $(".pb").css("width",newPer+"%");

                                    //present the massage
                                    var div = document.createElement("DIV");
                                    div.className = "alert alert-success mt-3";
                                    div.innerHTML = obj.msg;
                                    $(".uploadMsg").append(div);

                                    //remove the massage & reset progress bar after 3s
                                    setTimeout(function(){
                                        $(".uploadMsg").html("");
                                        $(".uPbar").css("width","0%");
                                        $(".uPbar").html("");
                                    },3000); 
                                    my_file();
                                }
                                else
                                {
                                    //present the massage
                                    var div = document.createElement("DIV");
                                    div.className = "alert alert-danger mt-3";
                                    div.innerHTML = obj.msg;
                                    $(".uploadMsg").append(div);

                                    //remove the massage & reset progress bar after 3s
                                    setTimeout(function(){
                                        $(".uploadMsg").html("");
                                        $(".uPbar").css("width","0%");
                                        $(".uPbar").html("");
                                    },3000) 
                                }
                            }
                        })
                    }
                    else{
                        //present the massage
                        var div = document.createElement("DIV");
                        div.className = "alert alert-danger mt-3";
                        div.innerHTML = "file size too large please purchase more storage";
                        $(".uploadMsg").append(div);

                        //remove the massage & reset progress bar after 3s
                        setTimeout(function(){
                            $(".uploadMsg").html("");
                            $(".uProgress").addClass("d-none");
                        },3000) 
                    }
                }
            });
            //menu coding start
            $(".fileMenu").each(function(){
                $(this).click(function(){
                    var page_link = $(this).attr("p_link");
                    
                    $.ajax({
                        type : "POST",
                        url : "php/pages/"+page_link+".php",
                        beforeSend : function(){
                            $(".loadingMsg").removeClass("d-none");
                            var div = document.createElement("DIV");
                            div.className = "alert alert-primary p-5 fs-1";
                            div.innerHTML = "loading...<i class='fa-solid fa-spinner fa-spin fs-1'></i>";
                            $(".loadingMsg").html(div);
                        },
                        success : function(response){
                            $(".loadingMsg").addClass("d-none");
                            $(".content").html(response);
                        }
                    })
                })
            }) 

            //click the my file menu as default
            function my_file() {
                $(".myFile").click();
            }
            my_file();
            // profile picture code start
            $(".profileInput").change(function(){
                var pic = this.value;
                $.ajax({
                    type : "POST",
                    url : "php/pr_picture.php",
                    data : {image : pic},
                    success : function(response){
                        alert(response);
                    }

                })
            });
            // profile picture code end

            // device responsive menu coding start
            $(".cut").click(function(){
                $(".smallMenu").css("width","0%");
            });

            $(".bars").click(function(){
                $(".smallMenu").css("width","75%");
            });

            $(".mm").each(function(){
                $(this).click(function(){
                    //$(".smallMenu").css("width","0%");
                    $(".cut").click();
                })
            });
            // device responsive menu coding start


            // search coding start
            /*$(".search").submit(function(e){
                e.preventDefault();
                var keywords = $(".searchInput").val();

                $.ajax({
                    type : "POST",
                    url : "php/pages/search.php",
                    data : {query : keywords},
                    beforeSend : function(){
                        $(".loadingMsg").removeClass("d-none");
                        var div = document.createElement("DIV");
                        div.className = "alert alert-primary p-5 fs-1";
                        div.innerHTML = "loading...<i class='fa-solid fa-spinner fa-spin fs-1'></i>";
                        $(".loadingMsg").html(div);
                    },
                    success : function(response){
                        $(".loadingMsg").addClass("d-none");
                        $(".content").html(response);
                    }
                })
            })*/

            // Attach the event handler to the input field with the 'oninput' event
            $(".searchInput").on("input", function() {
                var keywords = $(".searchInput").val();

                $.ajax({
                    type: "POST",
                    url: "php/pages/search.php",
                    data: { query: keywords },
                    beforeSend: function() {
                        $(".loadingMsg").removeClass("d-none");
                        var div = document.createElement("DIV");
                        div.className = "alert alert-primary p-5 fs-1";
                        div.innerHTML = "loading...<i class='fa-solid fa-spinner fa-spin fs-1'></i>";
                        $(".loadingMsg").html(div);
                    },
                    success: function(response) {
                        $(".loadingMsg").addClass("d-none");
                        $(".content").html(response);
                    }
                });
            });

            // search coding end     
        })
    </script>
</body>
</html>