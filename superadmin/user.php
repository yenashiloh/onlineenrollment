<?php

include 'config.php';

session_start();

$superadmin_id = $_SESSION['superadmin_id'];

if(!isset($superadmin_id)){
   header('location:../login.php');
   exit;
}

?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>User</title>

  <!-- Site favicon -->
  <link
			rel="apple-touch-icon"
			sizes="180x180"
			href="../asset/img/logo.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="../asset/img/logo.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="../asset/img/logo.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="../vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="../src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
			crossorigin="anonymous"
		></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->

	</head>
	<body class="sidebar-light">
    <?php
    include 'header.php';
    include 'sidebar.php';
    ?>

		<div class="mobile-menu-overlay"></div>
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>User</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="superadmin_dashboard.php">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											User
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
						<div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
							<a href="create_user.php" class="btn btn-success">
								<i class="fa fa-plus"></i> Add New Employee
							</a>

						<div class="pd-20">
						</div>

						<?php
							if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
								echo "<div class='alert alert-success'>Record Deleted Successfully!</div>";
							}
						?>
						<div class="pb-20">
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr>
									<th>No.</th>
									<th>Name</th>
									<th>Username</th>
									<th>Role</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
							require_once "config1.php";

							$sql = "SELECT * FROM users";
							if($result = mysqli_query($link, $sql)){
								if(mysqli_num_rows($result) > 0){
									$no = 1;
									while($row = mysqli_fetch_array($result)){
										echo "<tr>";
											echo "<td>" . $no++ . "</td>";
											echo "<td>" . ucwords($row['first_name'] . " " . $row['last_name']) . "</td>";
											echo "<td>" . $row['username'] . "</td>";
											echo "<td>" . $row['role'] . "</td>";
											echo "<td>";
												echo '<a href="edit_user.php?id='. $row['id'] .'" class="m-2" title="Update Record" data-toggle="tooltip"><span class="bi bi-pencil-fill"></span></a>';
												echo '<a href="#" data-toggle="modal" data-target="#Medium-modal'.$row['id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill"></span></a>';
												
												// Delete Modal 
												echo '
												<div class="modal fade" id="Medium-modal'.$row['id'].'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel'.$row['id'].'" aria-hidden="true">
													<div class="modal-dialog modal-dialog-centered">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="myLargeModalLabel'.$row['id'].'">Confirm Delete</h5>
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
															</div>
															<div class="modal-body">
																<p>Are you sure you want to delete this record?</p>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
																<a href="delete.php?id='.$row['id'].'" class="btn btn-primary">Delete</a>
															</div>
														</div>
													</div>
												</div>';
											echo "</td>";
										echo "</tr>";
									}
								} else{
									echo '<tr><td colspan="5"><div class="alert alert-danger"><em>No records were found.</em></div></td></tr>';
								}
							} else{
								echo '<tr><td colspan="5"><div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div></td></tr>';
							}

							mysqli_free_result($result);

							mysqli_close($link);
							?>
							</tbody>
						</table>
						</div>
					</div>
        	</div>
			</div>
		</div>

		<!-- js -->
    	<script src="../vendors/scripts/core.js"></script>
		<script src="../vendors/scripts/script.min.js"></script>
		<script src="../vendors/scripts/process.js"></script>
		<script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<!-- buttons for Export datatable -->
		<script src="../src/plugins/datatables/js/dataTables.buttons.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.print.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.html5.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.flash.min.js"></script>
		<script src="../src/plugins/datatables/js/pdfmake.min.js"></script>
		<script src="../src/plugins/datatables/js/vfs_fonts.js"></script>
		<!-- Datatable Setting js -->
		<script src="../vendors/scripts/datatable-setting.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
