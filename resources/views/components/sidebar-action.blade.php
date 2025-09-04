@if($action === 'clear-logs')
    <button type="button" 
            onclick="confirmClearLogs()" 
            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50">
        <i class="{{ $icon }} mr-3 text-red-500"></i>
        {{ $name }}
    </button>

    @push('scripts')
    <script>
        function confirmClearLogs() {
            if (confirm('Êtes-vous sûr de vouloir vider tous les logs ? Cette action est irréversible.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('personnels.Log.clear') }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    @endpush
@endif