<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>my drive login page</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>

</head>
<body>
	<?php require("elements/nav.php") ?>

	<div class="row">
		<div class="col-md-6"></div>
		<div class="col-md-6 p-4">
			<div class="container mainCon p-4">
				<form class="m-5 rounded loginFrm" autocomplete="off">
					<h1 class="text-center text-light">Login Now !</h1><hr>

					<div class="mb-3 email_con">
						<label for="email" class="form-label">Email Id</label>
						<input type="email" id="email" class="form-control" required="required">
					</div>

					<div class="mb-3 pswFrmGroup">
						<label for="password" class="form-label">Enter Password</label>
						<input type="password" id="password" class="form-control" required="required">
						<i class="fa-regular fa-eye eyeIcon"></i>
					</div>

					<div class="text-center">
						<button class="btn btn-primary w-50 loginBtn">Login Now</button>
					</div>

					<div class="logMsg"></div>					
				</form>

				<form class="rounded m-5 verFrm d-none">
					<h1 class="text-center text-light">Verify You !</h1>
					<p class="form-text text-light">We have sent you a 6-digit verification code</p><hr>

					<div class="mb-3 verFrmGroup">
						<label for="ver_code" class="form-label">Enter Verification Code</label>
						<input type="text" id="ver_code" class="form-control" required="required">
					</div>
					<div class="verBtnBox text-center">
						<button class="btn btn-primary w-50 verBtn">Verify Now !</button>
					</div>
					<div class="verMsg"></div>
					
				</form>
			</div>
		</div>
	</div>


	<script type="text/javascript">
		$(document).ready(function(){
			$(".loginFrm").submit(function(e){
				e.preventDefault();
				$.ajax({
					type : "POST",
					url : "php/user_login.php",
					data : {
						email : $("#email").val(),
						pass : $("#password").val()
					},
					beforeSend : function(){
						$(".loginBtn").html("Please wait...");
					},
					success : function(response){
						if(response.trim() == "active")
						{
							window.location = "profile.php";
						}
						else if(response.trim() == "pending")
						{
							$(".loginFrm").addClass("d-none");
							$(".verFrm").removeClass("d-none");
						}
						else if(response.trim() == "user not found")
						{
							var div = document.createElement("DIV");
							div.className = "alert alert-danger mt-3";
							div.innerHTML = "Please register your account !";
							$(".logMsg").append(div);
							setTimeout(function(){
								$(".logMsg").html("");
								$(".loginBtn").html("Login Now");
								window.location = "index.php";
							},3000);
						}
						else
						{
							var div = document.createElement("DIV");
							div.className = "alert alert-danger mt-3";
							div.innerHTML = "Please check your password !";
							$(".logMsg").append(div);
							setTimeout(function(){
								$(".logMsg").html("");
								$(".loginBtn").html("Login Now");
							},3000);
						}
					}
				})
			})

			//verification program
			$(".verFrm").submit(function(e){
				e.preventDefault();
				$.ajax({
					type : "POST",
					url : "php/verify_code.php",
					data : {
					email : $("#email").val(),
					ver_code : $("#ver_code").val()
					},
					beforeSend : function(){
						$(".verBtn").html("Checking...");
						$(".verBtn").attr("disabled","disabled");
					},
					success : function(response){
						$(".verBtn").html("Verify Now !");
						$(".verBtn").removeAttr("disabled");
						//present verification massage
						if(response.trim() == "active")
						{
							var div = document.createElement("DIV");
							div.className = "alert alert-success mt-3";
							div.innerHTML = "Account verified successfully";
							$(".verMsg").append(div);
							//redirect login page
							setTimeout(function(){
								window.location = "login.php";
							},3000);
						}
						else{
							var div = document.createElement("DIV");
							div.className = "alert alert-danger mt-3";
							div.innerHTML = response;
							$(".verMsg").append(div);
							setTimeout(function(){
								$(".verMsg").html("");
							},3000);
						}
						
					}
				})
			})
			//show & hide password 
			$(".eyeIcon").click(function(){
				if($("#password").attr("type") == "password")
				{
					$("#password").attr("type","text");
					$(this).css("color","#323232");

				}
				else{
					$("#password").attr("type","password");
					$(this).css("color","#bbb");
				}
			})	
		})
	</script>


</body>
</html>