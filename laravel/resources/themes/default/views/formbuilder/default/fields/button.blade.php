@if ($fieldInfo['subtype'] == 'button')

	<button type="submit" class="{{ $fieldInfo['className'] }}" name="{{ $fieldInfo['name'] }}">{{ $fieldInfo['label'] }}</button>

@elseif ($fieldInfo['subtype'] == 'submit')

	<input type="submit" class="{{ $fieldInfo['className'] }}" name="{{ $fieldInfo['name'] }}" value="{{ $fieldInfo['label'] }}">

@elseif ($fieldInfo['subtype'] == 'reset')

	<input type="reset" class="{{ $fieldInfo['className'] }}" name="{{ $fieldInfo['name'] }}" value="{{ $fieldInfo['label'] }}" onclick="return resetFBForm();">

@endif
