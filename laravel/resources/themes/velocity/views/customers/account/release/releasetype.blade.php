<releasetype></releasetype>

@if($tipo == 'video')
    @push('scripts')
        <script type="text/x-template" id="releasetype-template">
            <div>
                <div class="control-group" :class="[errors.has('releasetype') ? 'has-error' : '']">
                    <label for="releasetype" class="mandatory">
                        {{ __('shop::app.customer.account.release.create.releasetype') }}
                    </label>

                    <select
                        class="control styled-select"
                        id="releasetype"
                        type="text"
                        name="releasetype"
                        v-model="releasetype"
                        v-validate="'required'"
                        onchange="onChangeType(this)"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.releasetype') }}&quot;">
                        <option value="">{{ __('Select Type') }}</option>

                        @foreach ($releasetype as $type)
                            @if((int) $type->id != (int) config('constants.release.tipo.poster')) <!-- PWS#video-poster - salto il tipo poster -->
                                <option {{ $type->id === $defaultType ? 'selected' : '' }}  value="{{ $type->id }}">{{ $type->name }}</option> <!-- PWS#02-23 -->
                            @endif
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>

                    <span
                        class="control-error"
                        v-text="errors.first('releasetype')"
                        v-if="errors.has('releasetype')">
                    </span>
                </div>

                
            </div>
        </script>

        <script>
            Vue.component('releasetype', {
                template: '#releasetype-template',

                inject: ['$validator'],

                data: function () {
                    return {
                        releasetype: "{{ $releasetypeCode ?? $defaultType }}",

                    }
                },

            });
        </script>
    @endpush
@elseif($tipo == 'poster')
    <input type="hidden" id="releasetype" name="releasetype" value="{{ config('constants.release.tipo.poster'); }}">
@endif