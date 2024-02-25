@php
    $class ??=null;
    $name ??= '';
    $value ??= '';
    $label ??= ucfirst($name);
@endphp

<div class="form-group {{ $class }}">
    <label for="{{ $name }}" class="block font-medium text-sm text-gray-700">{{ $label }}</label>
    <select name="{{$name}}[]" id="{{$name}}" multiple class="form-multiselect block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error($name) border-red-500 @enderror">
        @foreach($classes as $k => $v)
            <option @if($value->contains($k)) selected @endif value="{{$k}}">{{$v}}</option>
        @endforeach
    </select>
    @error($name)
    <div class="text-red-500 mt-1 text-sm">
        {{ $message }}
    </div>
    @enderror
</div>
