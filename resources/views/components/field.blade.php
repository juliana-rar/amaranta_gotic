@props(['name', 'label', 'value' => '', 'type' => 'text', 'rows' => 3, 'placeholder' => ''])
@php($id = str_replace('.', '_', $name))
<div data-field>
    <label for="{{ $id }}">{{ $label }}</label>
    @if($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @elseif($type === 'file')
        <input id="{{ $id }}" type="file" name="{{ $name }}" accept="image/png,image/jpeg,image/webp,image/gif" {{ $attributes }}>
    @elseif($type === 'checkbox')
        <input id="{{ $id }}" type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $value)) {{ $attributes }}>
    @else
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" {{ $attributes }}>
    @endif
    @error($name)<div class="error">{{ $message }}</div>@enderror
</div>
