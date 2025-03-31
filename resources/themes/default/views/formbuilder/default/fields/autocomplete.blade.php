@php
    $fieldName = $fieldInfo['name'].'[]';
    
    $options = collect($fieldInfo['values'])->map(function ($item, $key) {
        return [
            'id' => $item['value'],
            'text' => $item['label']
        ];
    })->toJson();

    $curSelValues = old($fieldInfo['name'], []);

    if (! is_array($curSelValues)) {
        $curSelValues = (array) $curSelValues;
    }
@endphp

<div class="form-group col-lg-12">
    <label for="{{ $fieldInfo['name'] }}" class="@if (! empty($fieldInfo['required'])) mandatory @endif">{{ $fieldInfo['label'] }}</label>
    <select2 
    	class="{{ $fieldInfo['className'] }} select2" :class="[errors.has('{{ $fieldName }}') ? 'has-error' : '']"
    	id="{{ $fieldInfo['name'] }}" 
    	name="{{ $fieldName }}" 
    	@if (! empty($fieldInfo['required'])) v-validate="'required'" @endif 
        @if (! empty($fieldInfo['requireValidOption'])) data-tags=false @else data-tags=true @endif
        @if (! empty($fieldInfo['placeholder'])) placeholder="{{ $fieldInfo['placeholder'] }}" @endif 
        data-vv-as="&quot;{{ $fieldInfo['label'] }}&quot;"
        :options="{{ $options }}"
        @if (count($curSelValues)) :value="{{ json_encode($curSelValues) }}" @endif
        data-vv-value-path="innerValue"
    >
        @if (! empty($fieldInfo['placeholder'])) <option></option> @endif
    </select2>

    <span class="control-error" v-if="errors.has('{{ $fieldName }}')" v-text="errors.first('{{ $fieldName }}')"></span>

    @if (! empty($fieldInfo['description']))
    <small id="{{ $fieldInfo['name'] }}-help" class="form-text text-muted">{{ $fieldInfo['description'] }}</small>
    @endif
</div>