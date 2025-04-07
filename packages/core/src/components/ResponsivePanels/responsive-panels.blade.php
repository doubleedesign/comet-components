<div data-vue-component="responsive-panels" @if ($classes) @class($classes) @endif>
    <responsive-panels @attributes($attributes) breakpoint="{{ $breakpoint }}" :titles="{{ json_encode($titles) }}"
        :contents="{{ json_encode($panels) }}">
    </responsive-panels>
</div>
