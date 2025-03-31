<language></language>


@push('scripts')
    <script type="text/x-template" id="language-template">
        <div>
            <div class="control-group" :class="[errors.has('country') ? 'has-error' : '']">
                <label for="country" class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                    {{ __('shop::app.customer.account.release.create.country') }}
                </label>

                <select
                    class="control"
                    id="country"
                    type="text"
                    name="country"
                    v-model="country"
                    v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'"
                    data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.country') }}&quot;">
                    <option value=""></option>

                    @foreach (core()->countries() as $country)
                    @endforeach
                </select>

                <span
                    class="control-error"
                    v-text="errors.first('country')"
                    v-if="errors.has('country')">
                </span>
            </div>

    
        </div>
    </script>

    <script>
        Vue.component('language', {
            template: '#language-template',

            inject: ['$validator'],

            data() {
                return {

                    countryStates: @json(core()->groupedStatesByCountries())
                }
            },

            methods: {
                haveStates() {
                    if (this.countryStates[this.country] && this.countryStates[this.country].length)
                        return true;

                    return false;
                },
            }
        });
    </script>
@endpush
