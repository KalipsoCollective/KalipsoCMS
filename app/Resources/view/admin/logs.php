		<div class="container-fluid">
			<div class="row">
				<header class="col-12 dash-header">
					<div class="d-flex align-items-center">
						<h1><i class="ti ti-virus-search"></i> <?php echo \KN\Helpers\Base::lang('base.logs'); ?></h1>
					</div>
					<p><?php echo $description; ?></p>
				</header>
				<div class="col-12 dash-content">
					<div class="bg-white p-2 mb-2 rounded shadow-sm" id="logsTable"></div>
				</div>
			</div>
		</div>