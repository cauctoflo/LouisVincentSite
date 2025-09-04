@if(isset($isPlaceholder) && $isPlaceholder)
    <div class="text-center py-4 text-gray-500">
        <i class="{{ $icon }} text-3xl mb-2"></i>
        <p>{{ $name }}</p>
    </div>
@elseif(isset($action))
    @include('components.sidebar-action', ['action' => $action, 'name' => $name, 'icon' => $icon])
@else
    @if($route)
        @php
            $routeParams = $params ?? [];
            $isActive = request()->routeIs($route);
            
            // Check if this is the current route with params
            if (!empty($routeParams)) {
                $isActive = request()->routeIs($route) && request()->input('action') == ($routeParams['action'] ?? null);
            }
        @endphp
        
        <a href="{{ route($route, $routeParams) }}" 
           class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ $isActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="{{ $icon }} mr-3 text-{{ $isActive ? 'blue-500' : 'gray-400 group-hover:text-gray-500' }}"></i>
            {{ $name }}
        </a>
    @elseif(isset($url))
        <a href="{{ $url }}" 
           class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
            <i class="{{ $icon }} mr-3 text-gray-400 group-hover:text-gray-500"></i>
            {{ $name }}
        </a>
    @else
        <div class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-400 cursor-not-allowed">
            <i class="{{ $icon }} mr-3 text-gray-300"></i>
            {{ $name }}
        </div>
    @endif
@endif