<div class="form-group">
    <label for="{{ $fieldInfo['name'] }}" class="@if (! empty($fieldInfo['required'])) mandatory @endif">{{ $fieldInfo['label'] }}</label>
    <input 
    	type="range" 
    	class="{{ $fieldInfo['className'] }}" :class="[errors.has('{{ $fieldInfo['name'] }}') ? 'has-error' : '']" 
    	id="{{ $fieldInfo['name'] }}" 
    	name="{{ $fieldInfo['name'] }}" 
    	value="{{ old($fieldInfo['name'], $fieldInfo['value'] ?? null) }}" 
    	min="{{ $fieldInfo['min'] }}"
        max="{{ $fieldInfo['max'] }}"
        step="{{ $fieldInfo['step'] }}"
    	@if (! empty($fieldInfo['required'])) v-validate="'required'" @endif 
        data-vv-as="&quot;{{ $fieldInfo['label'] }}&quot;" 
    >

    <span class="control-error" v-if="errors.has('{{ $fieldInfo['name'] }}')" v-text="errors.first('{{ $fieldInfo['name'] }}')"></span>

    @if (! empty($fieldInfo['description']))
    <small id="{{ $fieldInfo['name'] }}-help" class="form-text text-muted">{{ $fieldInfo['description'] }}</small>
    @endif
</div>