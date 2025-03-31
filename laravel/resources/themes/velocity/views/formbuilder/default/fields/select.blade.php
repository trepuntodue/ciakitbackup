@php 
    $selValues = [];

    $fieldName = $fieldInfo['name'].'[]';

    if (! empty($fieldInfo['values'])) {
        $selValues = collect($fieldInfo['values'])->where('selected', true)->pluck('value')->all(); 
    }

    $curSelValues = old($fieldInfo['name'], $selValues);

    if (! is_array($curSelValues)) {
        $curSelValues = (array) $curSelValues;
    }

    $options = collect($fieldInfo['values'])->map(function ($item, $key) {
        return [
            'id' => $item['value'],
            'text' => $item['label']
        ];
    })->toJson();
@endphp

<div class="form-group">
    <label for="{{ $fieldInfo['name'] }}" class="@if (! empty($fieldInfo['required'])) mandatory @endif">{{ $fieldInfo['label'] }}</label>
    <select2 
        class="{{ $fieldInfo['className'] }} select2" :class="[errors.has('{{ $fieldName }}') ? 'has-error' : '']"
    	id="{{ $fieldInfo['name'] }}" 
        name="{{ $fieldName }}" 
    	@if (! empty($fieldInfo['required'])) v-validate="'required'" @endif 
    	@if ($fieldInfo['multiple'] == true) multiple @endif 
        data-vv-as="&quot;{{ $fieldInfo['label'] }}&quot;"
        :options="{{ $options }}"
        @if (count($curSelValues)) :value="{{ json_encode($curSelValues) }}" @endif
        data-vv-value-path="innerValue"
        @if (! empty($fieldInfo['placeholder'])) placeholder="{{ $fieldInfo['placeholder'] }}" @endif 
    >
        @if ($fieldInfo['multiple'] == false) <option></option> @endif
    </select2>

    <span class="control-error d-block" v-if="errors.has('{{ $fieldName }}')" v-text="errors.first('{{ $fieldName }}')"></span>

    @if (! empty($fieldInfo['description']))
    <small id="{{ $fieldInfo['name'] }}-help" class="form-text text-muted">{{ $fieldInfo['description'] }}</small>
    @endif
</div>