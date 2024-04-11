@php
    $value = '93da93d90c5ed8a8c963098ea0c8d177';
    //echo (!($value === 'null' || (Str::length($value) === 32 && \App\Models\Admin\Cart\Token::firstWhere('token', $value)))) ? 'not valid' : 'valid';

@endphp
<div>
    {{  (!($value === 'null' || (Str::length($value) === 32 && \App\Models\Admin\Cart\Token::firstWhere('token', $value)))) ? 'not valid' : 'valid' }}
</div>
