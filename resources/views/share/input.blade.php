@php
    $type ??= 'text';
    $class ??=null;
    $name ??= '';
    $value ??= '';
    $label ??= ucfirst($name);
@endphp

<div class="form-group {{ $class }}">
    <label for="{{ $name }}" class="block font-medium text-sm text-gray-700">{{ $label }}</label>
    @if($type === 'textarea')
        <textarea class="form-textarea mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error($name) border-red-500 @enderror" id="{{ $name }}" name="{{ $name }}">{{ old($name, $value) }}</textarea>
    @else
        <input class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error($name) border-red-500 @enderror" type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}">
    @endif
    @error($name)
    <div class="text-red-500 mt-1 text-sm">
        {{ $message }}
    </div>
    @enderror
</div>
