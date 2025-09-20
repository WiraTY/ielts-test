<div class="mt-4">
    @if($audioPath)
        <audio controls class="w-full">
            <source src="{{ asset('storage/' . $audioPath) }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @else
        <p class="text-gray-500">No audio available for this lesson.</p>
    @endif
</div>