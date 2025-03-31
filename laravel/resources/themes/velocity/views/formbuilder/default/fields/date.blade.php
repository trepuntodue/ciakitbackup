@php
    $dateFormat = (! empty($fieldInfo['dateFormat'])) ? $fieldInfo['dateFormat'] : 'yyyy-mm-dd';
@endphp

<div class="form-group">
    <label for="{{ $fieldInfo['name'] }}" class="@if (! empty($fieldInfo['required'])) mandatory @endif">{{ $fieldInfo['label'] }}</label>

    <date-picker 
    	type="text" 
    	class="{{ $fieldInfo['className'] }} datepicker" :class="[errors.has('{{ $fieldInfo['name'] }}') ? 'has-error' : '']" 
    	id="{{ $fieldInfo['name'] }}" 
    	name="{{ $fieldInfo['name'] }}" 
    	default-date="{{ old($fieldInfo['name'], $fieldInfo['value'] ?? '') }}" 
        value="{{ old($fieldInfo['name'], $fieldInfo['value'] ?? '') }}" 
    	@if (! empty($fieldInfo['placeholder'])) placeholder="{{ $fieldInfo['placeholder'] }}" @endif 
    	@if (! empty($fieldInfo['maxlength'])) maxlength="{{ $fieldInfo['maxlength'] }}" @endif 
        date-format="{{ $dateFormat }}"
        @if (! empty($fieldInfo['startDate'])) start-date="{{ $fieldInfo['startDate'] }}" @endif 
        @if (! empty($fieldInfo['endDate'])) end-date="{{ $fieldInfo['endDate'] }}" @endif 
    	@if (! empty($fieldInfo['required'])) v-validate="'required'" @endif 
        data-vv-as="&quot;{{ $fieldInfo['label'] }}&quot;"
        data-vv-value-path="innerValue"
    ></date-picker>

    <span class="control-error" v-if="errors.has('{{ $fieldInfo['name'] }}')" v-text="errors.first('{{ $fieldInfo['name'] }}')"></span>

    @if (! empty($fieldInfo['description']))
    <small id="{{ $fieldInfo['name'] }}-help" class="form-text text-muted">{{ $fieldInfo['description'] }}</small>
    @endif
</div>