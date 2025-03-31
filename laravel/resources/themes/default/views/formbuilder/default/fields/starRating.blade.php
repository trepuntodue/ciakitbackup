<div class="form-group col-lg-12">
    <label for="{{ $fieldInfo['name'] }}" class="@if (! empty($fieldInfo['required'])) required @endif">{{ $fieldInfo['label'] }}</label>
    
    <rating 
        class="rateyo"
        rating="{{ old($fieldInfo['name'], $fieldInfo['value'] ?? '0') }}"
        min="0"
        max="{{ $fieldInfo['maxRating'] ?? '5' }}"
        target="{{ $fieldInfo['name'] }}"
    ></rating>

    <input 
        type="hidden" 
        id="{{ $fieldInfo['name'] }}" 
        name="{{ $fieldInfo['name'] }}" 
        value="{{ old($fieldInfo['name'], $fieldInfo['value'] ?? '0') }}"
        @if (! empty($fieldInfo['required'])) v-validate="'required|min_value:1|max_value:{{ $fieldInfo['maxRating'] ?? '5' }}'" @endif 
        data-vv-as="&quot;{{ $fieldInfo['label'] }}&quot;"
    >

    <span class="control-error" v-if="errors.has('{{ $fieldInfo['name'] }}')" v-text="errors.first('{{ $fieldInfo['name'] }}')"></span>
    
    @if (! empty($fieldInfo['description']))
    <small id="{{ $fieldInfo['name'] }}-help" class="form-text text-muted">{{ $fieldInfo['description'] }}</small>
    @endif
</div>