<div class="input-group">
  <form method="GET" role="search" id="searchmaster-form" action="{{ route('shop.cinema.master.list') }}"> <!-- PWS#13-filtri -->
    <div class="btn-toolbar full-width search-form" role="toolbar">

      {{-- 
        PW RICERCA HOME MASTER
        component
        \packages\Webkul\Velocity\src\Resources\assets\js\UI\components\image-search.vue        

      --}}

      {{-- <searchbar-component>
        <template v-slot:image-search>

          <image-search-component
            status="{{ core()->getConfigData('general.content.shop.image_search') == '1' ? 'true' : 'false' }}"
            upload-src="{{ route('shop.image.search.upload') }}" view-src="{{ route('shop.search.index') }}"
            common-error="{{ __('shop::app.common.error') }}"
            placeholder="{{ __('shop::app.header.searchmaster-text') }}"
            size-limit-error="{{ __('shop::app.common.image-upload-limit') }}">
          </image-search-component>
          
        </template>
      </searchbar-component> --}}

      <searchbar-component>
        <template></template>
      </searchbar-component>

    </div>
  </form>
</div>
