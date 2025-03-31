<div class="form-group col-lg-12">
    <label for="{{ $fieldInfo['name'] }}" class="@if (! empty($fieldInfo['required'])) mandatory @endif">{{ $fieldInfo['label'] }}</label>
    <input 
    	type="{{ $fieldInfo['subtype'] }}" 
    	class="{{ $fieldInfo['className'] }}" :class="[errors.has('{{ $fieldInfo['name'] }}') ? 'has-error' : '']"
    	id="{{ $fieldInfo['name'] }}" 
    	name="{{ $fieldInfo['name'] }}" 
    	value="{{ old($fieldInfo['name'], $fieldInfo['value'] ?? null) }}" 
    	@if (! empty($fieldInfo['placeholder'])) placeholder="{{ $fieldInfo['placeholder'] }}" @endif 
    	@if (! empty($fieldInfo['maxlength'])) maxlength="{{ $fieldInfo['maxlength'] }}" @endif 
    	@if (! empty($fieldInfo['required'])) v-validate="'required'" @endif 
		data-vv-as="&quot;{{ $fieldInfo['label'] }}&quot;"
    >

	<span class="control-error" v-if="errors.has('{{ $fieldInfo['name'] }}')" v-text="errors.first('{{ $fieldInfo['name'] }}')"></span>

    @if (! empty($fieldInfo['description']))
    <small id="{{ $fieldInfo['name'] }}-help" class="form-text text-muted">{{ $fieldInfo['description'] }}</small>
    @endif
</div>