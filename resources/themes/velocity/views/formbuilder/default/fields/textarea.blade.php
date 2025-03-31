<div class="form-group">
    <label for="{{ $fieldInfo['name'] }}" class="@if (! empty($fieldInfo['required'])) mandatory @endif">{{ $fieldInfo['label'] }}</label>
    <textarea 
    	class="{{ $fieldInfo['className'] }}" :class="[errors.has('{{ $fieldInfo['name'] }}') ? 'has-error' : '']" 
    	id="{{ $fieldInfo['name'] }}" 
    	name="{{ $fieldInfo['name'] }}" 
    	@if (! empty($fieldInfo['placeholder'])) placeholder="{{ $fieldInfo['placeholder'] }}" @endif 
    	@if (! empty($fieldInfo['maxlength'])) maxlength="{{ $fieldInfo['maxlength'] }}" @endif 
        @if (! empty($fieldInfo['rows'])) rows="{{ $fieldInfo['rows'] }}" @endif 
    	@if (! empty($fieldInfo['required'])) v-validate="'required'" @endif 
        data-vv-as="&quot;{{ $fieldInfo['label'] }}&quot;"
    >{{ old($fieldInfo['name'], $fieldInfo['value'] ?? null) }}</textarea>

    <span class="control-error" v-if="errors.has('{{ $fieldInfo['name'] }}')" v-text="errors.first('{{ $fieldInfo['name'] }}')"></span>

    @if (! empty($fieldInfo['description']))
    <small id="{{ $fieldInfo['name'] }}-help" class="form-text text-muted">{{ $fieldInfo['description'] }}</small>
    @endif
</div>