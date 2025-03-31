<language></language>

@push('scripts')
    <script type="text/x-template" id="language-template">
        <div>
            <div class="control-group" :class="[errors.has('language') ? 'has-error' : '']">
                <label for="language" class="mandatory">
                    {{ __('shop::app.customer.account.release.create.language') }}
                </label>

                <select
                    class="control styled-select js-example-basic-multiple"
                    id="language"
                    type="text"
                    name="language[]"
                    v-model="language"
                    v-validate="'required'"
                    {{-- v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'" --}}
                    data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.language') }}&quot;"
                    multiple="multiple">
                    <option value="">{{ __('Select language') }}</option>

                    @foreach ($language as $lang)
                        <option {{ $lang->id === $defaultLanguage ? 'selected' : '' }}  value="{{ $lang->id }}">{{ $lang->name }}</option> <!-- PWS#230101 -->
                    @endforeach
                </select>

                <div class="select-icon-container">
                    <span class="select-icon rango-arrow-down"></span>
                </div>

                <span
                    class="control-error"
                    v-text="errors.first('language')"
                    v-if="errors.has('language')">
                </span>
            </div>


        </div>
    </script>

    <script>
        Vue.component('language', {
            template: '#language-template',

            inject: ['$validator'],

            data: function () {
                return {
                    language: "{{ $languageCode ?? $defaultLanguage }}",

                }
            },

        });
    </script>
@endpush
