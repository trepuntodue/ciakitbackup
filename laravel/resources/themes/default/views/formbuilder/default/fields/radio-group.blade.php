@php 
    $selValues = [];

    if (! empty($fieldInfo['values'])) {
        $selValues = collect($fieldInfo['values'])->where('selected', true)->pluck('value')->all(); 
    }

    $curSelValues = old($fieldInfo['name'], $selValues);
    if (! is_array($curSelValues)) {
        $curSelValues = (array) $curSelValues;
    }
@endphp

<div class="form-group col-lg-12">
    <label for="{{ $fieldInfo['name'] }}" class="@if (! empty($fieldInfo['required'])) mandatory @endif">{{ $fieldInfo['label'] }}</label>

    @foreach ($fieldInfo['values'] as $value)
    	<div class="custom-control custom-radio @if ($fieldInfo['inline']) custom-control-inline @endif">
	    	<input 
				type="radio" 
				class="custom-control-input" 
				id="{{ $fieldInfo['name'] . '-' . $value['value'] }}" 
				name="{{ $fieldInfo['name'] }}" 
				value="{{ $value['value'] }}"
				@if (in_array($value['value'], $curSelValues)) checked="checked" @endif 
				@if (! empty($fieldInfo['required'])) v-validate="'required'" @endif 
				data-vv-as="&quot;{{ $fieldInfo['label'] }}&quot;"
			>
			<label class="custom-control-label" for="{{ $fieldInfo['name'] . '-' . $value['value'] }}">{{ $value['label'] }}</label>
		</div>
	@endforeach

	<span class="control-error" v-if="errors.has('{{ $fieldInfo['name'] }}')" v-text="errors.first('{{ $fieldInfo['name'] }}')"></span>

	@if (! empty($fieldInfo['description']))
    <small id="{{ $fieldInfo['name'] }}-help" class="form-text text-muted">{{ $fieldInfo['description'] }}</small>
    @endif
</div>