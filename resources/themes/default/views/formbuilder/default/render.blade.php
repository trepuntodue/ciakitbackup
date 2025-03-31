@if ($formbuilder->status == true)

	@php $enable_google_recaptcha = $formbuilder->has_captcha; @endphp

	@if ( $formbuilder->is_public || (! $formbuilder->is_public && (Auth::guard('admin')->check() || Auth::guard('customer')->check())) )
		<div id="{{ $formbuilder->code }}" class="row justify-content-center mt-3 formbuilder">
			<form id="frm-{{ $formbuilder->code }}" action="{{ route('shop.formbuilder.form.store', ['code' => $formbuilder->code]) }}" method="POST" enctype="multipart/form-data" autocomplete="off" @submit.prevent="onSubmit">
				<div class="form-row">
					@csrf 

					@if ($enable_google_recaptcha)
						<input type="hidden" name="recaptcha_response" class="recaptchaResponse">
					@endif

					@foreach ($formbuilder->form_builder_json as $field)
						@includeIf($fieldview . $field['type'], ['fieldInfo' => $field])
					@endforeach
				</div>
			</form>
		</div>
	@endif

	@push('css')
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.4/jquery.rateyo.min.css" integrity="sha512-JEUoTOcC35/ovhE1389S9NxeGcVLIqOAEzlpcJujvyUaxvIXJN9VxPX0x1TwSo22jCxz2fHQPS1de8NgUyg+nA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="{{ bagisto_asset('css/formbuilder-default.css') }}">
	@endpush

	@push('scripts')
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.4/jquery.rateyo.min.js" integrity="sha512-09bUVOnphTvb854qSgkpY/UGKLW9w7ISXGrN0FR/QdXTkjs0D+EfMFMTB+CGiIYvBoFXexYwGUD5FD8xVU89mw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<script>
			Vue.component('date-picker', {
        		template: '<input/>',
        		props: ['dateFormat', 'startDate', 'endDate', 'defaultDate'],
        		data() {
        			return {
        				innerValue: this.defaultDate
        			}
        		},
				mounted: function() {
					var vm = this;
					$(this.$el).datepicker({
						autoclose: true,
						format: this.dateFormat,
						date: this.defaultDate,
						startDate: this.startDate,
						endDate: this.endDate
					})
					.on("changeDate", function(e) {
						vm.innerValue = e.format();
					});
				},
				methods: {
					clearData() {
						this.innerValue = [];
					}
				},
				watch: {
      				innerValue (val) {
        				this.$emit('input', val);
      				},
    			},
				beforeDestroy: function() {
					$(this.$el).datepicker('hide').datepicker('destroy');
				}
        	});

        	Vue.component('rating', {
        		inject: ['$validator'],
        		template: '<div/>',
        		props: {rating:0, min:0, max:5, target:''},
        		mounted: function() {
					var vm = this;
					$(this.$el).rateYo({
						rating: this.rating,
						numStars: this.max,
						minValue: this.min,
						maxValue: this.max,
						fullStar: true,
						spacing: "5px",
						starWidth: "20px",
						onSet: function (rating, rateYoInstance) {
							$('input[name="'+vm.target+'"]').val(rating);
							vm.$validator.validate(vm.target);
						}
					});
				},
				beforeDestroy: function() {
					$(this.$el).rateYo('destroy');
				}
        	});

        	Vue.component('select2', {
        		template: '<select><slot/></select>',
        		props: ['options', 'value', 'placeholder'],
        		data() {
        			return {
        				innerValue: this.value
        			}
        		},
				methods: {
					clearData() {
						this.innerValue = [];
						$(this.$el).val([]).trigger('change');
					}
				},
        		mounted: function() {
        			var vm = this;
					$(this.$el)
						.select2({
							data: this.options,
	            			placeholder: this.placeholder
						})
						.val(this.value)
						.trigger("change")
						.on("select2:select select2:unselect", function() {
							vm.innerValue = $(this).val();
      					});
				},
				watch: {
	        		options: function(options) {
	            		$(this.$el).select2({
	            			data: options,
	            			placeholder: this.placeholder
	            		});
	          		},
	          		innerValue (val) {
        				this.$emit('input', val);
      				},
	        	},
				beforeDestroy: function() {
					$(this.$el).off().select2('destroy');
				}
        	});

			function resetFBForm() {
				app.$validator.pause();
				app.$validator.errors.clear();

				app.$validator.fields['items'].forEach((field) => {
					if (field.componentInstance != undefined) {
						field.componentInstance.clearData();
					}
				});

				if ($('.rateyo').length > 0) {
					$('.rateyo').rateYo("rating", 0);
				}

				setTimeout(function() {
					app.$validator.resume();
				}, 50);
				
				return true;
			}
        </script>

		@if ($enable_google_recaptcha)
			@php $recaptchSiteKey = config('formbuilder.google_recaptcha_site_key'); @endphp
			
			<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchSiteKey }}"></script>
			<script type="text/javascript">
				function getRecaptcha() {
					grecaptcha.ready(function() {
						grecaptcha.execute('{{ $recaptchSiteKey }}', {
							action: 'comment'
						}).then(function (token) {
							var recaptchaElements = document.getElementsByName('recaptcha_response');
							for (var i = 0; i < recaptchaElements.length; i++) {
								recaptchaElements[i].value = token;
							}
						});
					});
				}

				$(document).on('renderReCaptcha', function (xhr) {
					getRecaptcha();
				});

				getRecaptcha();
			</script>
		@endif
	@endpush

@endif