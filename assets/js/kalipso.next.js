/*!
 * Kalipso Next Basic Scripts
 * Version: v1.0.0
 * Copyright 2022, Kalipso Collective
 * Released under the MIT License
 */


async function kalipsoFetch(url = null, method = 'POST', data = {}) {

	url = url ?? window.location.href;

	method = method ?? 'POST';
	method = method.toUpperCase();

	data = typeof data === 'string' ? JSON.parse(data) : data;
	data = typeof data === 'object' ? data : {};

	// Fetch
	return await fetch(url, {
		method: method,
		mode: 'cors',
		cache: 'no-cache',
		headers: {
			"X-KALIPSONEXT": "1.0.0",
			"Accept": "application/json",
		},
		credentials: 'same-origin',
		referrerPolicy: 'same-origin',
		redirect: 'follow',
		body: data
	})
	.then((response) => {
		if (response.status >= 200 && response.status < 300) {
			return response.json();
		} else {
			throw new Error(JSON.stringify({
				alerts: '<div class=\"kn-toast-alert\"><div class=\"kn-alert kn-alert-danger\">Server Response Problem! ['+ response.status +']</div></div>'
			}));
		}
	})
	.then(data => { return data; })
	.catch((error) => {
		if (typeof error.message === 'string') {
			try {
				return JSON.parse(error.message);
			} catch (e) {
				return {
					alerts: '<div class=\"kn-toast-alert\"><div class=\"kn-alert kn-alert-danger\">Server Response Problem!</div></div>'
				};
			}
		} else {
			return error;
		}
	});
}

function editorInit(el, domID = null) {

	if (domID) {
		domID = btoa(domID);
		const domHash = domID;
	}

	let elementOptions = {};

	if (typeof el.dataset !== 'undefined' && typeof el.dataset.options !== 'undefined') {
		elementOptions = {...JSON.parse(el.dataset.options), ...elementOptions};
	}

	let defaultOptions = {
		modules: {
			imageResize: {
				displaySize: false
			},
			toolbar: {
				container: [
					['bold', 'italic', 'underline', 'strike'], 
					['blockquote', 'code-block'],
					[{ 'list': 'ordered'}, { 'list': 'bullet' }],
					['link', 'image'],
					['clean']	
				],
				handlers: {	
					image: async function(value) {
						const input = document.createElement('input');	
						input.setAttribute('type', 'file');	
						input.setAttribute('accept', 'image/*');	
						input.click();
						
						const moduleName = this.quill.container.dataset.module ?? 'general';

						input.onchange = async () => {	
							let file = input.files[0];	
							let formData = new FormData();	
							formData.append('image', file);
							NProgress.start();
							const res = await kalipsoFetch('/management/content/' + moduleName + '/upload-file', 'POST', formData);
							responseFormatter(res, this.quill);
							NProgress.done();
						};
					},
					link: function(value) {
						if (value) {
							let range = this.quill.getSelection();
							if (range == null || range.length == 0) {
								alert("Not selected.");
								return;
							}
							let preview = this.quill.getText(range);
							if (/^\S+@\S+\.\S+$/.test(preview) && preview.indexOf('mailto:') !== 0) {
								preview = 'mailto:' + preview;
							}
							let tooltip = this.quill.theme.tooltip;
							tooltip.edit('link', 'http://');
						} else {
							this.quill.format('link', false);
						}
					}
				}
			},
		},
		theme: 'snow'
	};

	const options = {...defaultOptions, ...elementOptions};
	let editor = undefined;
	if (el.classList.contains('editor')) {
		editor = new Quill(el, options);
	}

	if (domID !== null && editor !== undefined) {
		if (window.domEditor === undefined) {
			window.domEditor = [];
		}

		window.domEditor[domID] = editor;
	}
}

function kalipsoInit(firstLoad = false, initSelector = null) {

	if (typeof window.init === 'function') { 
		window.init();
	}
	
	// Stored alert remove action
	alertRemove();

	/* Async. Form Submit */
	const forms = document.querySelectorAll('form[data-kn-form]');
	for (let i = 0; i < forms.length; i++) {
		forms[i].addEventListener("submit", async function(e) {
			e = e || window.event;
			e.preventDefault();
			NProgress.start();

			// Form Reset, Init
			const dom = e.target;
			dom.classList.add('sending');
			// form elements
			dom.querySelectorAll('[name]').forEach((el) => {
				el.classList.remove('is-valid');
				el.classList.remove('is-invalid');
			})
			// editor
			dom.querySelectorAll('[data-name]').forEach((el) => {
				el.classList.remove('border');
				el.classList.remove('border-1');
				el.classList.remove('border-danger');
			})

			// Append Datas
			const data = new FormData(dom);
			const editor = dom.querySelectorAll('[data-kn-toggle="editor"]');
			if (editor.length) {

				for (var i = 0; i < editor.length; i++) {
					let name = editor[i].getAttribute('data-name');
					let value = editor[i].querySelector('.ql-editor').innerHTML;
					data.append(name, value);
				}

			}

			// Fetch
			const response = await kalipsoFetch(dom.getAttribute('action'), dom.getAttribute('method'), data);

			if (response !== undefined) {
				responseFormatter(response, dom);
			}
			setTimeout(() => {
				dom.classList.remove('sending');
				NProgress.done();
			}, 500);
			
		});
	}

	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

	var editorToggle = document.querySelectorAll((initSelector ? initSelector + ' ' : '') + '[data-kn-toggle="editor"]');

	if (typeof Quill !== 'undefined' && editorToggle) {
		[].forEach.call(editorToggle, function(el) {
			editorInit(el, el.getAttribute('data-name'));
		});
	}
	
	if (firstLoad) {

		document.addEventListener("click", async function(e) {
			// Async. Action Buttons
			if (e.target.nodeName.toUpperCase() === 'BUTTON' || e.target.nodeName.toUpperCase() === 'A') {
				if (e.target.getAttribute('data-kn-action')) {
					
					e.preventDefault();

					let keep = true;
					if (e.target.getAttribute('data-kn-again')) {

						if (e.target.getAttribute('data-kn-again-check')) {
							keep = false;
						} else {
							let text = e.target.innerHTML;
							e.target.innerHTML = sanitizeHTML(e.target.getAttribute('data-kn-again'));
							e.target.setAttribute('data-kn-again-check', true);
							setTimeout(() => {
								e.target.innerHTML = text;
								e.target.removeAttribute('data-kn-again-check');
							}, 3000);
						}

					} else {
						keep = false;
					}

					if (! keep) {

						let action = e.target.getAttribute('data-kn-action');
						try {

							const url = new URL(action); // check valid url

							NProgress.start();
							response = await kalipsoFetch(
								e.target.getAttribute('data-kn-action'), 
								e.target.getAttribute('data-kn-again-method'), 
								e.target.getAttribute('data-kn-again-options')
							);

							if (response !== undefined) {
								responseFormatter(response);
							}
							setTimeout(() => {
								NProgress.done();
							}, 500);

						} catch (error) {

							if (action === 'manipulation') {

								if (e.target.getAttribute('data-kn-manipulation')) {

									const manipulation = JSON.parse(e.target.getAttribute('data-kn-manipulation'));
									responseFormatter(manipulation);
								}
							}
						}
					}
				}
			}
		});

		document.addEventListener("change", async function(e) {
			// Async. Action Buttons
			if (e.target.nodeName.toUpperCase() === 'INPUT' || e.target.nodeName.toUpperCase() === 'SELECT' || e.target.nodeName.toUpperCase() === 'TEXTAREA') {
				if (e.target.getAttribute('data-kn-change')) {
					
					e.preventDefault();

					let keep = true;
					if (e.target.getAttribute('data-kn-again')) {

						if (e.target.getAttribute('data-kn-again-check')) {
							keep = false;
						} else {
							let text = e.target.innerHTML;
							e.target.innerHTML = sanitizeHTML(e.target.getAttribute('data-kn-again'));
							e.target.setAttribute('data-kn-again-check', true);
							setTimeout(() => {
								e.target.innerHTML = text;
								e.target.removeAttribute('data-kn-again-check');
							}, 3000);
						}

					} else {
						keep = false;
					}

					let options = new FormData;
					if (e.target.getAttribute('data-kn-change').indexOf('/slug') !== -1) {
						options.append('slug', e.target.value);
						options.append('lang', e.target.getAttribute('data-kn-lang'));
						options.append('id', e.target.getAttribute('data-kn-id'));
					} else {
						keep = true;
					}

					if (! keep) {

						let url = e.target.getAttribute('data-kn-change');
						NProgress.start();
						response = await kalipsoFetch(
							e.target.getAttribute('data-kn-change'),
							'POST',
							options
						);

						if (response !== undefined) {
							responseFormatter(response);
						}
						setTimeout(() => {
							NProgress.done();
						}, 500);
					}
				}
			}
		});
	}
	
}

const sanitizeHTML = function (str) {
	var temp = document.createElement('div');
	temp.textContent = str;
	return temp.innerHTML;
};

function alertRemove() {
	const alerts = document.querySelectorAll('.kn-alert');
	if (alerts.length) {

		for (var i = alerts.length - 1; i >= 0; i--) {
			let element = alerts[i]
			setTimeout(() => {
				element.classList.add('out');
				setTimeout(() => {
					element.remove();
				}, 800);
			}, 5000);
			
		}
	}
}

function responseFormatter(response, dom = null) {

	if (dom === null) {
		dom = document;
	}

	if (response.alerts !== undefined) {
		const alertDom = document.createElement('div');
		alertDom.innerHTML = response.alerts;
		if (alertDom.querySelector('.kn-toast-alert')) { // if with parent 
			document.querySelector('.kn-toast-alert').innerHTML = 
				alertDom.querySelector('.kn-toast-alert').innerHTML;
		} else {
			document.querySelector('.kn-toast-alert').innerHTML = 
				alertDom.innerHTML;
		}
		alertRemove();
	}

	if (dom && response.form_reset !== undefined && response.form_reset) {
		dom.reset();
		const editor = dom.querySelectorAll('[data-kn-toggle="editor"]');
		if (editor.length) {
			for (var i = 0; i < editor.length; i++) {
				editor[i].querySelector('.ql-editor').innerHTML = '';
			}
		}
	}

	if (response.modal_close !== undefined && document.querySelector(response.modal_close)) {
		const modal = bootstrap.Modal.getOrCreateInstance(document.querySelector(response.modal_close));
		modal.hide();
	}

	if (response.modal_open !== undefined && document.querySelector(response.modal_open)) {
		const modal = bootstrap.Modal.getOrCreateInstance(document.querySelector(response.modal_open));
		modal.show();
	}

	if (response.init !== undefined && response.init) {
		setTimeout(() => {
			kalipsoInit(false, response.init);
		}, 100);
	}

	if (response.reload !== undefined) {
		const timeOut = response.reload_timeout !== undefined ? response.reload_timeout : 1;
		setTimeout(() => {
			if (response.reload === true) {
				if (window.vanillaPjax) window.vanillaPjax.reload();
				else window.location.reload();
			} else {
				if (window.vanillaPjax) window.vanillaPjax.reload(response.reload);
				else window.location.href = response.reload;
			}
		}, timeOut);
	}

	if (response.table_reset !== undefined && window[response.table_reset] !== undefined) {
		window[response.table_reset].reset();
	}

	if (dom && response.manipulation !== undefined) {

		for (const [selector, data] of Object.entries(response.manipulation)) {
			
			if (dom.querySelector(selector)) {

				/**
				 * DOM manipulation for attributes. 
				 */
				if (data.attribute !== undefined && data.attribute) {
					for ([prop, value] of Object.entries(data.attribute)) {
						if (prop === 'value') dom.querySelector(selector).value = value
						else dom.querySelector(selector).setAttribute(prop, value);
					}
				}

				/**
				 * DOM manipulation for adding class. 
				 */
				if (data.class !== undefined && data.class.length) {
					for (var i = 0; i < data.class.length; i++) {
						dom.querySelector(selector).classList.add(data.class[i]);
					}
				}

				/**
				 * DOM manipulation for removing class. 
				 */
				if (data.remove_class !== undefined && data.remove_class.length) {
					for (var i = 0; i < data.remove_class.length; i++) {
						dom.querySelector(selector).classList.remove(data.remove_class[i]);
					}
				}

				/**
				 * DOM manipulation for removing class. 
				 */
				if (data.remove_element !== undefined && data.remove_element) {
					dom.querySelector(selector).remove();
				}

				/**
				 * DOM manipulation inner html. 
				 */
				if (data.html !== undefined && data.html) {
					dom.querySelector(selector).innerHTML = data.html;
				}

				if (data.html_append !== undefined && data.html_append) {
					const currentHtml = dom.querySelector(selector).innerHTML;
					dom.querySelector(selector).innerHTML += data.html_append;
				}
			}
		}
	}

	if (dom && response.editor_upload !== undefined) {

		for (var i = 0; i < response.editor_upload.length; i++) {

			let range = dom.getSelection();

			dom.insertEmbed(range.index ?? 0, 'image', response.editor_upload[i]);
		}
		
	}
}

function navOpen() {

	const body = document.querySelector('body');
	if (body.classList.contains('side-opened')) {
		body.classList.remove('side-opened');
	} else {
		body.classList.add('side-opened');
	}
}

NProgress.start();
(function() {

	window.vanillaPjax = new vPjax({selector: 'a:not([target="_blank"]):not([href="#!"])', wrap: '#wrap', timeOut: 3000}).init() // .form('[data-vpjax]')
	document.addEventListener("vPjax:start", (e) => {
		NProgress.start();
	})
	document.addEventListener("vPjax:finish", (e) => {
		NProgress.done();
		kalipsoInit();
	})
	kalipsoInit(true);
	setTimeout(() => {
		NProgress.done()
	}, 500)

})();