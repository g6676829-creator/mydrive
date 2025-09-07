<?php
	session_start();
	if(empty($_SESSION['user'])) {
		header("location: login.php");
	}

	$userEmail = $_SESSION['user'];
	require("../database.php");
	$sql = "SELECT * FROM users WHERE email = '$userEmail'";
	$response = $db->query($sql);
	$aa = $response->fetch_assoc();
	$userTableName = "user_".$aa['id']; // user table name = user folder name also
	$totalSpace = $aa['storage'];
?>

<h2 class="text-center planstxt mb-4">MY FILES</h2>

<div class="row">
	<?php
		$fileDataSql = "SELECT * FROM $userTableName";
		$fileResponse = $db->query($fileDataSql);

		while($fileArray = $fileResponse->fetch_assoc()) {
			$fileDetailArray = pathinfo($fileArray['file_name']);

			$fileName = $fileDetailArray['filename'];
			$extName = $fileDetailArray['extension'];
			$baseName = $fileDetailArray['basename'];

			// Start creating display elements
			echo '
				<div class="col-md-4">
					<div class="d-flex border p-3 mb-3">
						<div class="me-2">';
							if($extName == "mp4") {
								echo "<img src='images/mp4.png' class='thumb'>";
							} else if($extName == "mp3") {
								echo "<img src='images/mp3.png' class='thumb'>";
							} else if($extName == "pdf") {
								echo "<img src='images/pdf.png' class='thumb'>";
							} else if($extName == "pptx" || $extName == "ppt") {
								echo "<img src='images/ppt.png' class='thumb'>";
							} else if($extName == "docx" || $extName == "doc") {
								echo "<img src='images/doc.png' class='thumb'>";
							} else if($extName == "xlsx" || $extName == "xls") {
								echo "<img src='images/xls.png' class='thumb'>";
							} else if($extName == "txt") {
								echo "<img src='images/txt.png' class='thumb'>";
							} else if($extName == "zip") {
								echo "<img src='images/zip.png' class='thumb'>";
							} else if($extName == "mov") {
								echo "<img src='images/mov.png' class='thumb'>";
							} else if($extName == "wmv") {
								echo "<img src='images/wmv.png' class='thumb'>";
							} else if($extName == "jpg" || $extName == "jpeg" || $extName == "png" || $extName == "gif" || $extName == "webp") {
								echo "<img src='data/" . $userTableName . "/" . $baseName . "' class='thumb'>";
							}
						echo '</div>

						<div class="w-100">
							<span>' . $fileName . '</span>
							<hr>
							<div class="d-flex justify-content-around w-100">
								<a href="data/' . $userTableName . '/' . $baseName . '" target="blank"><i class="fas fa-eye"></i></a>
								<a href="data/' . $userTableName . '/' . $baseName . '" download><i class="fas fa-download"></i></a>
								<i class="fas fa-trash del" id="' . $fileArray['id'] . '" folder="' . $userTableName . '" file="' . $baseName . '"></i>';
								if($fileArray['star'] == "yes"){
									echo '<i class="fa-solid fa-star text-warning star" id="' . $fileArray['id'] . '" status="no" table="' . $userTableName . '"></i>';
								}
								else{
									echo '<i class="fa-solid fa-star text-secondary star" id="' . $fileArray['id'] . '" status="yes" table="' . $userTableName . '"></i>';
								}
							echo '</div>
						</div>
					</div>
				</div>';
		}
	?>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".del").each(function() {
				$(this).click(function() {
					var id = $(this).attr("id");
					var folder = $(this).attr("folder");
					var file = $(this).attr("file");
					var cle = $(this);
					
					$.ajax({
						type : "POST",
						url : "php/delete.php",
						data : {
							i : id,
							fold : folder,
							fil : file
						},
						success : function(response){
							var obj = JSON.parse(response);
							
							if(obj.msg == "file deleted successfully")
							{
								var newPer = (obj.newFileSize*100)/<?php echo $totalSpace ?>;
								
								$(".tus").html(obj.newFileSize);
								$(".pb").css("width",newPer+"%");

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
								},3000);
								var parent = cle.parent().parent().parent().parent();
								parent.remove();
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
					});
				});
			})
			//favourite file code
			$(".star").each(function(){
				$(this).click(function(){
					var starId = $(this).attr('id');
					var starStatus = $(this).attr('status');
					var tableName = $(this).attr('table');

					$.ajax({
						type : "POST",
						url : "php/star_file.php",
						data : {
							id : starId,
							status : starStatus,
							table : tableName
						},
						success : function(response){
							if(response == "success"){
								$(".myFile").click();
							}
							else{
								alert(response);
							}
						}
					})
				})
			})
		})
	</script>
</div>
