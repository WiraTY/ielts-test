<nav class="flex mb-4" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-3">
    @foreach($breadcrumbs as $breadcrumb)
      @if(!$loop->last)
        <li class="inline-flex items-center">
          <a href="{{ $breadcrumb['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
            @if(isset($breadcrumb['icon']))
              <svg class="{{ $breadcrumb['iconClasses'] ?? 'w-4 h-4 mr-2' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                {!! $breadcrumb['icon'] !!}
              </svg>
            @endif
            {{ $breadcrumb['label'] }}
          </a>
        </li>
        <li>
          <div class="flex items-center">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
          </div>
        </li>
      @else
        <li aria-current="page">
          <div class="flex items-center">
            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $breadcrumb['label'] }}</span>
          </div>
        </li>
      @endif
    @endforeach
  </ol>
</nav>