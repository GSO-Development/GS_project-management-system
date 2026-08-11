@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#c3122e] focus:ring-[#c3122e] rounded-md shadow-sm']) }}>
