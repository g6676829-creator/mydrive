<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>my drive</title>
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
				<form class="m-5 rounded signupFrm" autocomplete="off">
					<h1 class="text-center text-light">Sign Up !</h1><hr>
					<div class="mb-3">
						<label for="username" class="form-label">Name</label>
						<input type="text" id="username" class="form-control" required="required">
					</div>

					<div class="mb-3 email_con">
						<label for="email" class="form-label">Email Id</label>
						<input type="email" id="email" class="form-control" required="required">
						<i class="fa-solid fa-spinner fa-spin d-none emailLoader"></i>
					</div>

					<div class="mb-3 pswFrmGroup">
						<label for="password" class="form-label">Create Password</label>
						<input type="password" id="password" class="form-control" required="required">
						<i class="fa-regular fa-eye eyeIcon"></i>
					</div>

					<div class="mb-3 d-flex justify-content-between">
						<div class="form-text text-light">Click to genrate for improve sequrity .</div>
						<button class="btn btn-sm btn-danger genPswBtn">Genrate</button>
					</div>

					<div class="text-center">
						<button class="btn btn-primary w-50 subBtn" disabled="disabled">Register Now !</button>
					</div>

					<div class="msg"></div>					
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
			});

			//ajax password suggestion
			$(".genPswBtn").click(function(e){
				e.preventDefault();
				$.ajax({
					type:"POST",
					beforeSend: function(){
						$(".eyeIcon").removeClass("fa-regular fa-eye");
						$(".eyeIcon").addClass("fa-solid fa-spinner fa-spin");
					},
					url:"php/generate_psw.php",
					success : function(response){
						$(".eyeIcon").removeClass("fa-solid fa-spinner fa-spin");
						$(".eyeIcon").addClass("fa-regular fa-eye");
						$("#password").val(response.trim());
						$("#password").attr("type","text");
						$(".eyeIcon").css("color","#323232");
					}
				});
			});

			//emailLoader coding
			$("#email").on('input',function(){
				$(".emailLoader").removeClass("d-none");
			})
			//chack user allready exist or not
			$("#email").on('change',function(){
				//ajax for email verification
				$.ajax({
					type : "POST",
					url : "php/verify_email.php",
					data : {email : $(this).val()},
					success : function(response){
						$(".emailLoader").removeClass("fa-solid fa-spinner fa-spin");
						if(response.trim() == "notfound")
						{
							$(".emailLoader").removeClass("fa-solid fa-x");
							$(".emailLoader").addClass("fa-solid fa-check");
							$(".emailLoader").css({color:"green"});
							$(".subBtn").removeAttr("disabled");
						}
						else
						{
							$(".emailLoader").removeClass("fa-solid fa-check");
							$(".emailLoader").addClass("fa-solid fa-x");
							$(".emailLoader").css({color:"red"});
							$(".subBtn").attr("disabled","disabled");
						}
					}
				})
			});

			//sigin up coding
			$(".signupFrm").submit(function(e){
				e.preventDefault();
				$.ajax({
					type : "POST",
					url : "php/register.php",
					data : {
						name : $("#username").val(),
						email : $("#email").val(),
						password : $("#password").val()
					},
					beforeSend : function(){
						$(".subBtn").html("Please wait...");
						$(".subBtn").attr("disabled","disabled");
					},
					success : function(response){
						$(".subBtn").html("Register Now !");
						$(".subBtn").removeAttr("disabled");
						if(response.trim() == "success")
						{
							var div = document.createElement("DIV");
							div.className = "alert alert-success mt-3";
							div.innerHTML = "Registered Successfully";
							$(".msg").append(div);
							//remove the massage and reset form
							setTimeout(function(){
								$(".msg").html("");
								//$(".signupFrm").trigger('reset');
								$(".verFrm").removeClass("d-none");
								$(".signupFrm").addClass("d-none");
								
							},3000);
						}
						else if(response.trim() == "userexist")
						{
							var div = document.createElement("DIV");
							div.className = "alert alert-warning mt-3";
							div.innerHTML = "user allready exist !";
							$(".msg").append(div);
							//remove the massage and reset form
							setTimeout(function(){
								$(".msg").html("");
								$(".signupFrm").trigger('reset');
							},3000);
						}
						else
						{
							var div = document.createElement("DIV");
							div.className = "alert alert-danger mt-3";
							div.innerHTML = "Registation Failed !";
							$(".msg").append(div);
							//remove the massage and reset form
							setTimeout(function(){
								$(".msg").html("");
								$(".signupFrm").trigger('reset');
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
							//remove the warning msg
							setTimeout(function(){
								$(".verMsg").addClass("d-none");
							},3000);
						}
						
					}
				})
			})			

		})
	</script>
</body>
</html>