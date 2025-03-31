@php 
    $fileRules = [];

    if (! empty($fieldInfo['required'])) {
        $fileRules[] = 'required';
    }
    
    if (! empty($fieldInfo['allowedFileExtension'])) {
        $fileRules[] = 'ext:'. implode(',', array_map(function ($ext) {
            return trim(str_replace('.', '', $ext));
        }, explode(',', $fieldInfo['allowedFileExtension'])));
    }

    if (! empty($fieldInfo['allowedFileSize'])) {
        $fileRules[] = 'size:'.$fieldInfo['allowedFileSize'];
    }
@endphp

<div class="form-group">
    <label for="{{ $fieldInfo['name'] }}" class="@if (! empty($fieldInfo['required'])) mandatory @endif">{{ $fieldInfo['label'] }}</label>
    <input 
        type="file" 
        class="{{ $fieldInfo['className'] }}" :class="[errors.has('{{ $fieldInfo['name'] }}') ? 'has-error' : '']"
        id="{{ $fieldInfo['name'] }}" 
        @if ($fieldInfo['multiple']) 
            name="{{ $fieldInfo['name'] }}[]"
        @else
            name="{{ $fieldInfo['name'] }}"
        @endif
        @if (! empty($fieldInfo['multiple'])) multiple @endif 
        @if (! empty($fieldInfo['allowedFileExtension'])) accept="{{ $fieldInfo['allowedFileExtension'] }}" @endif
        @if (count($fileRules)) v-validate="'{{ implode('|', $fileRules) }}'" @endif 
        data-vv-as="&quot;{{ $fieldInfo['label'] }}&quot;"
    >

    <span class="control-error" v-if="errors.has('{{ $fieldInfo['name'] }}')" v-text="errors.first('{{ $fieldInfo['name'] }}')"></span>

    @if (! empty($fieldInfo['description']))
    <small id="{{ $fieldInfo['name'] }}-help" class="form-text text-muted">{{ $fieldInfo['description'] }}</small>
    @endif
</div>