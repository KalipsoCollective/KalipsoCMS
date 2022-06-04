		<div class="wrap">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h1><i class="<?php echo $detail->icon; ?>"></i> <?php echo $detail->title; ?></h1>
						<?php 
						echo htmlspecialchars_decode($detail->content);
						if (isset($detail->header_image_src->original) !== false) {
							echo '<img class="img-fluid" src="' . $detail->header_image_src->original . '" />';
						}

						echo '<div class="row">
						<pre>';
						print_r($detail);
						exit;
						foreach ($detail->countries as $country) {
							
							echo '
							<div class="col-4">
								<h2>' . $country->title . '</h2>
								' . htmlspecialchars_decode($country->content) . '
								<img class="img-fluid" src="' . $country->flag_src->original . '">
								<img class="img-fluid" src="' . $country->campus_image_src->original . '">
							</div>';

						}
						echo '</div>';
						?>
					</div>
				</div>
			</div>
		</div>
		