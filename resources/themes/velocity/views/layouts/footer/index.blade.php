<div class="footer">

	<div class="footer-content">

		<div class="px-8 bg-black center-content pb-5">

			<div class="container flex flex-wrap gap-8 py-6 sm:flex-nowrap sm:gap-0">

				<div class="w-full footer-col md:w-1/4">
					<img
						class="logo"
						width="200"
						height="150"
						src="{{ core()->getCurrentChannel()->logo_url ?? asset( 'themes/velocity/assets/images/ciakit-logo-black-600.png' ) }}"
						alt="ciakit"
					/>
				</div>

				<div class="w-full footer-col sm:px-4 md:w-3/4">
					<ul class="flex flex-col justify-end gap-4 sm:flex-row">
						<li><a
								class="text-lg text-white underline"
								href="/page/chi-siamo/"
							>Chi siamo</a></li>
						<li><a
								class="text-lg text-white underline"
								href="/page/supporto/"
							>Supporto</a></li>
						<li><a
								class="text-lg text-white underline"
								href="mailto:info@ciakit.com"
							>Contattami</a></li>
						<li class="hidden"><a
								class="text-lg text-white underline"
								href="/page/legale/"
							>Legale</a></li>
						<li><a
								class="text-lg text-white underline"
								href="https://accesso.acconsento.click/informative/page/5578/it"
								target="_blank"
							>Privacy policy</a></li>
						<li><a
								class="text-lg text-white underline"
								href="https://accesso.acconsento.click/cookies/page/it/5578"
								target="_blank"
							>Cookie policy</a></li>
						<!-- <li><a
								class="text-lg text-white underline"
								href="/page/cookie-policy/"
							>Cookie policy</a></li> -->
					</ul>

					<ul class="mt-4 flex flex-col justify-end gap-4 sm:flex-row lh-lg">
						<li><div class="mt-2 text-sm text-right text-white">
							© {{ date('Y'); }} Ciakit s.r.l.s. - P.iva 05619970287
						</div></li>
					</ul>
				</div>

				{{--
				<div class="w-full px-4 footer-col sm:w-1/2 md:w-1/4">
					<h4 class="text-lg uppercase">Chi siamo</h4>
				</div>

				<div class="w-full px-4 footer-col sm:w-1/2 md:w-1/4">
					<h4 class="text-lg uppercase">Supporto</h4>
				</div>

				<div class="w-full px-4 footer-col sm:w-1/2 md:w-1/4">
					<h4 class="text-lg uppercase">Legale</h4>
				</div>
				--}}

			</div>

		</div>

		{{-- @include( 'shop::layouts.footer.newsletter-subscription' ) --}}

		{{-- @include( 'shop::layouts.footer.footer-links' ) --}}

		@if ( core()->getConfigData( 'general.content.footer.footer_toggle' ) )
			@include( 'shop::layouts.footer.copy-right' )
		@endif
	</div>
</div>