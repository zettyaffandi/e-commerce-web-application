
<?php include 'includes/header.php'; ?>

<body>
<div class="container mt-4 pt-2">
	<div class="col-lg-3 sidebar-box">
		<h3>Categories</h3>
		<ul class="categories">
			<li><a href="menu.php">All</a></li>
			<?php echo getCat(); ?>
		</ul>
	</div>

	  <div class="row col-lg-9">
	    <div class="row">
				<?php echo getMenu(); ?>
	    </div>
	</div>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
