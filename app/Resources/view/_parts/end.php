		<script src="<?php echo KN\Helpers\Base::assets('libs/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
		<script src="<?php echo KN\Helpers\Base::assets('libs/nprogress/nprogress.js'); ?>"></script>
		<script src="<?php echo KN\Helpers\Base::assets('libs/vpjax/vpjax.min.js'); ?>"></script>
		<script src="<?php echo KN\Helpers\Base::assets('libs/kalipsotable/l10n/tr.js'); ?>"></script>
		<script src="<?php echo KN\Helpers\Base::assets('libs/kalipsotable/kalipso.table.js'); ?>"></script>
		<script src="<?php echo KN\Helpers\Base::assets('libs/quill/quill.min.js'); ?>"></script>
		<script src="<?php echo KN\Helpers\Base::assets('libs/quill/image-resize.min.js'); ?>"></script>
		<script src="<?php echo KN\Helpers\Base::assets('libs/draggable/draggable.bundle.min.js'); ?>"></script>
		<script>
			<?php 
			$moduleList = [['value' => 'general', 'name' => KN\Helpers\Base::lang('base.general')]];
			if (isset($modules)) {
				foreach ($modules as $moduleName => $moduleData) {
					$moduleList[] = ['value' => $moduleName, 'name' => KN\Helpers\Base::lang($moduleData['name'])];
				}
			}
			?>
			window.init = () => {

				let contentListSource = null;
				let contentListColumns = null;
				if (document.querySelector('#contentsTable')) {
					const el = document.querySelector('#contentsTable');
					contentListSource = el.getAttribute('data-source');
					contentListColumns = el.getAttribute('data-columns');
					if (contentListColumns) {
						contentListColumns = JSON.parse(el.getAttribute('data-columns'))
					}
				}

				let tableVariables = {
					mediasTable: {
						selector: "#mediasTable",
						language: "<?php echo \KN\Helpers\Base::lang('lang.code'); ?>",
						server: true,
						source: '<?php echo $this->url('/management/media/list') ?>',
						columns: [ 
							{
								"searchable": {
									"type": "number",
									"min": 1,
									"max": 999
								},
								"orderable": true,
								"title": "#",
								"key": "id"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.name'); ?>",
								"key": "name"
							},
							{
								"searchable": {
									"type": "select",
									"maxlength": 50,
									"datas": <?php echo json_encode($moduleList); ?>,
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.module'); ?>",
								"key": "module"
							},
							{
								"searchable": false,
								"orderable": false,
								"title": "<?php echo \KN\Helpers\Base::lang('base.preview'); ?>",
								"key": "files"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.extension'); ?>",
								"key": "mime"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.size'); ?>",
								"key": "size"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.created_at'); ?>",
								"key": "created"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.updated_at'); ?>",
								"key": "updated"
							},
							{
								"searchable": false,
								"orderable": false,
								"title": "<?php echo \KN\Helpers\Base::lang('base.action'); ?>",
								"key": "action"
							}
						],
						customize: {
							tableWrapClass: "table-responsive",
							tableClass: "table table-bordered",
							inputClass: "form-control form-control-sm",
							selectClass: "form-select form-select-sm",
						},
						tableHeader: {
							searchBar: true
						},
						tableFooter: {
							visible: true,
							searchBar: true
						}
					},
					usersTable: {
						selector: "#usersTable",
						language: "<?php echo \KN\Helpers\Base::lang('lang.code'); ?>",
						server: true,
						source: '<?php echo $this->url('/management/users/list') ?>',
						columns: [ 
							{
								"searchable": {
									"type": "number",
									"min": 1,
									"max": 999
								},
								"orderable": true,
								"title": "#",
								"key": "id"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.username'); ?>",
								"key": "u_name"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.name'); ?>",
								"key": "name"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.email'); ?>",
								"key": "email"
							},
							{
								"searchable": {
									"type": "date",
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.birth_date'); ?>",
								"key": "birth_date"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.role'); ?>",
								"key": "role"
							},
							{
								"searchable": {
									"type": "date",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.created_at'); ?>",
								"key": "created"
							},
							{
								"searchable": {
									"type": "date",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.updated_at'); ?>",
								"key": "updated"
							},
							{
								"searchable": {
									"type": "select",
									"datas": [
										{"value": 'active', "name": "<?php echo \KN\Helpers\Base::lang('base.active'); ?>"},
										{"value": 'passive', "name": "<?php echo \KN\Helpers\Base::lang('base.passive'); ?>"}
									],
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.status'); ?>",
								"key": "status"
							},
							{
								"searchable": false,
								"orderable": false,
								"title": "<?php echo \KN\Helpers\Base::lang('base.action'); ?>",
								"key": "action"
							}
						],
						customize: {
							tableWrapClass: "table-responsive",
							tableClass: "table table-bordered",
							inputClass: "form-control form-control-sm",
							selectClass: "form-select form-select-sm",
						},
						tableHeader: {
							searchBar: true
						},
						tableFooter: {
							visible: true,
							searchBar: true
						}
					},
					rolesTable: {
						selector: "#rolesTable",
						language: "<?php echo \KN\Helpers\Base::lang('lang.code'); ?>",
						server: true,
						source: '<?php echo $this->url('/management/roles/list') ?>',
						columns: [ 
							{
								"searchable": {
									"type": "number",
									"min": 1,
									"max": 999
								},
								"orderable": true,
								"title": "#",
								"key": "id"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.name'); ?>",
								"key": "name"
							},
							{
								"searchable": false,
								"orderable": false,
								"title": "<?php echo \KN\Helpers\Base::lang('base.routes'); ?>",
								"key": "routes"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.users'); ?>",
								"key": "users"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.created_at'); ?>",
								"key": "created"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.updated_at'); ?>",
								"key": "updated"
							},
							{
								"searchable": false,
								"orderable": false,
								"title": "<?php echo \KN\Helpers\Base::lang('base.action'); ?>",
								"key": "action"
							}
						],
						customize: {
							tableWrapClass: "table-responsive",
							tableClass: "table table-bordered",
							inputClass: "form-control form-control-sm",
							selectClass: "form-select form-select-sm",
						},
						tableHeader: {
							searchBar: true
						},
						tableFooter: {
							visible: true,
							searchBar: true
						}
					},
					logsTable: {
						selector: "#logsTable",
						language: "<?php echo \KN\Helpers\Base::lang('lang.code'); ?>",
						server: true,
						source: '<?php echo $this->url('/management/logs/list') ?>',
						columns: [ 
							{
								"searchable": {
									"type": "number",
									"min": 1,
									"max": 999
								},
								"orderable": true,
								"title": "#",
								"key": "id"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.endpoint'); ?>",
								"key": "endpoint"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.request'); ?>",
								"key": "req"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.middleware'); ?>",
								"key": "middleware"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.controller'); ?>",
								"key": "controller"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.ip'); ?>",
								"key": "ip"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.user'); ?>",
								"key": "user"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.execute_time'); ?>",
								"key": "exec_time"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.created_at'); ?>",
								"key": "created"
							},
							{
								"searchable": false,
								"orderable": false,
								"title": "<?php echo \KN\Helpers\Base::lang('base.action'); ?>",
								"key": "action"
							}
						],
						customize: {
							tableWrapClass: "table-responsive",
							tableClass: "table table-bordered",
							inputClass: "form-control form-control-sm",
							selectClass: "form-select form-select-sm",
						},
						tableHeader: {
							searchBar: true
						},
						tableFooter: {
							visible: true,
							searchBar: true
						}
					},
					sessionsTable: {
						selector: "#sessionsTable",
						language: "<?php echo \KN\Helpers\Base::lang('lang.code'); ?>",
						server: true,
						source: '<?php echo $this->url('/management/sessions/list') ?>',
						columns: [ 
							{
								"searchable": {
									"type": "number",
									"min": 1,
									"max": 999
								},
								"orderable": true,
								"title": "#",
								"key": "id"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.auth_code'); ?>",
								"key": "auth_code"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.user'); ?>",
								"key": "user"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.role'); ?>",
								"key": "role"
							},
							{
								"searchable": true,
								"orderable": false,
								"title": "<?php echo \KN\Helpers\Base::lang('base.device'); ?>",
								"key": "header"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.ip'); ?>",
								"key": "ip"
							},
							{
								"searchable": false,
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.last_action_date'); ?>",
								"key": "last_action_date"
							},
							{
								"searchable": {
									"type": "text",
									"maxlength": 50
								},
								"orderable": true,
								"title": "<?php echo \KN\Helpers\Base::lang('base.last_action_point'); ?>",
								"key": "last_action_point"
							}
						],
						customize: {
							tableWrapClass: "table-responsive",
							tableClass: "table table-bordered",
							inputClass: "form-control form-control-sm",
							selectClass: "form-select form-select-sm",
						},
						tableHeader: {
							searchBar: true
						},
						tableFooter: {
							visible: true,
							searchBar: true
						}
					},
					contentsTable: {
						selector: "#contentsTable",
						language: "<?php echo \KN\Helpers\Base::lang('lang.code'); ?>",
						server: true,
						source: contentListSource,
						columns: contentListColumns,
						customize: {
							tableWrapClass: "table-responsive",
							tableClass: "table table-bordered",
							inputClass: "form-control form-control-sm",
							selectClass: "form-select form-select-sm",
						},
						tableHeader: {
							searchBar: true
						},
						tableFooter: {
							visible: true,
							searchBar: true
						}
					},
				}

				for(const [key, value] of Object.entries(tableVariables)) {
					window[key] = new KalipsoTable(value);
				}

				const drag = new Draggable.Sortable(document.querySelectorAll('.kn-menu-drag'), {
					draggable: '.kn-menu-item',
					handle: '.kn-menu-item-dragger',
					mirror: {
						constrainDimensions: true
					},
					exclude: {
						plugins: [Draggable.Plugins.Focusable],
						sensors: [Draggable.Sensors.TouchSensor],
					  },
				});

			}
		</script>
		<script src="<?php echo KN\Helpers\Base::assets('js/kalipso.next.js'); ?>"></script>
	</body>
</html>