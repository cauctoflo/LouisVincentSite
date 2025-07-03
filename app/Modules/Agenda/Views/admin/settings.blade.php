@extends('layouts.admin')

@section('title', 'Paramètres de l\'Agenda')

@section('content')
    <div class="py-12 px-12 bg-white min-h-screen">
        <div class="w-full max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <h1 class="text-4xl font-extrabold text-blue-900 flex items-center gap-4">
                    <i class="fas fa-calendar-alt text-blue-600 text-5xl"></i> Paramètres de l'Agenda
                </h1>
                <a href="{{ route('personnels.modules.index') }}" class="inline-flex items-center px-6 py-3 text-lg font-semibold text-blue-700 bg-white border-2 border-blue-200 rounded-2xl hover:bg-blue-50 hover:border-blue-400 transition-all gap-2 shadow">
                    <i class="fas fa-arrow-left"></i> Retour aux modules
                </a>
            </div>

            @if(session('success'))
                <div class="mb-8 rounded-2xl bg-blue-50 border-l-8 border-blue-400 p-6 shadow flex items-center gap-4">
                    <i class="fas fa-check-circle text-blue-500 text-3xl"></i>
                    <span class="text-lg font-semibold text-blue-900">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-8 rounded-2xl bg-blue-100 border-l-8 border-blue-800 p-6 shadow flex items-center gap-4">
                    <i class="fas fa-exclamation-circle text-blue-800 text-3xl"></i>
                    <span class="text-lg font-semibold text-blue-900">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Gestion des évènements -->
            <div class="bg-white rounded-3xl p-14 border-2 border-blue-100 mb-12">
                <h2 class="text-2xl font-bold text-blue-800 mb-10 flex items-center gap-3">
                    <i class="fas fa-calendar-day text-blue-500 text-3xl p-4"></i> Évènements de l'agenda
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10 w-full p-4">
                    @forelse($evenements as $event)
                        <div class="bg-white rounded-2xl p-8 flex flex-col justify-between border-2 border-blue-100 w-full min-h-[220px]">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="font-bold text-xl text-blue-900">{{ $event->titre }}</span>
                                    <span class="text-sm text-blue-400"><i class="far fa-clock"></i> {{ $event->date }} de {{ $event->heure_debut }} à {{ $event->heure_fin }}</span>
                                </div>
                                <p class="text-blue-700 text-base mb-8">{{ $event->description }}</p>
                            </div>
                            <div class="flex gap-4 mt-auto">
                                <form action="{{ route('personnels.Agenda.evenements.supprimer', $event->id) }}" method="POST" onsubmit="return confirm('Supprimer cet évènement ?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-5 py-2 bg-blue-50 text-blue-700 border-2 border-blue-200 rounded-xl hover:bg-blue-100 hover:border-blue-400 text-base font-semibold gap-2 transition-all">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                                <button type="button" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-800 text-base font-semibold gap-2 transition-all open-edit-modal"
                                    data-id="{{ $event->id }}"
                                    data-titre="{{ $event->titre }}"
                                    data-date="{{ $event->date }}"
                                    data-heure_debut="{{ $event->heure_debut }}"
                                    data-heure_fin="{{ $event->heure_fin }}"
                                    data-description="{{ $event->description }}"
                                    data-couleur="{{ $event->couleur }}"
                                    data-lieu="{{ $event->lieu }}">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center text-blue-300 italic">Aucun évènement pour le moment.</div>
                    @endforelse
                </div>
            </div>

            <!-- Formulaire d'ajout d'évènement -->
            <div class="bg-white shadow-2xl rounded-3xl p-16 border-2 border-blue-50 w-full mb-20 flex flex-col items-center">
                <h3 class="text-4xl font-bold mb-12 text-blue-800 flex items-center gap-4 mt-4">
                    <i class="fas fa-calendar-plus text-5xl text-blue-600"></i> Ajouter un évènement
                </h3>
                <form action="{{ route('personnels.Agenda.evenements.ajouter') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 w-full max-w-6xl">
                    @csrf
                    <!-- Champ Titre -->
                    <div class="col-span-1">
                        <label for="titre" class="block text-blue-700 font-semibold mb-3 text-lg">Titre</label>
                        <input type="text" name="titre" id="titre" placeholder="Ex: Réunion équipe"
                            class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg shadow-md transition-all duration-200 hover:shadow-lg bg-white"
                            required>
                    </div>
                    <!-- Champ Date avec mini-calendrier -->
                    <div class="col-span-1">
                        <label for="date" class="block text-blue-700 font-semibold mb-3 text-lg">Date</label>
                        <div id="mini-calendar-create" class="mb-4"></div>
                        <input type="date" name="date" id="date"
                            class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg shadow-md transition-all duration-200 hover:shadow-lg bg-white"
                            required style="background:#f9fafb; cursor:pointer">
                    </div>
                    <!-- Champ Heure de début -->
                    <div class="col-span-1">
                        <label for="heure_debut" class="block text-blue-700 font-semibold mb-3 text-lg">Heure de début</label>
                        <div class="flex gap-2 items-center">
                            <input type="number" name="heure_debut_h" id="heure_debut_h" min="0" max="23" step="1" class="border-2 border-blue-200 rounded-xl p-3 text-lg text-center no-spin" value="08" required style="width:5rem;" autocomplete="off">
                            <span class="text-xl font-bold">:</span>
                            <input type="number" name="heure_debut_m" id="heure_debut_m" min="0" max="59" step="5" class="border-2 border-blue-200 rounded-xl p-3 text-lg text-center no-spin" value="00" required style="width:5rem;" autocomplete="off">
                        </div>
                    </div>
                    <!-- Champ Heure de fin -->
                    <div class="col-span-1">
                        <label for="heure_fin" class="block text-blue-700 font-semibold mb-3 text-lg">Heure de fin</label>
                        <div class="flex gap-2 items-center">
                            <input type="number" name="heure_fin_h" id="heure_fin_h" min="0" max="23" step="1" class="border-2 border-blue-200 rounded-xl p-3 text-lg text-center no-spin" value="09" required style="width:5rem;" autocomplete="off">
                            <span class="text-xl font-bold">:</span>
                            <input type="number" name="heure_fin_m" id="heure_fin_m" min="0" max="59" step="5" class="border-2 border-blue-200 rounded-xl p-3 text-lg text-center no-spin" value="00" required style="width:5rem;" autocomplete="off">
                        </div>
                    </div>
                    <!-- Champ Couleur -->
                    <div class="col-span-1">
                        <label for="couleur" class="block text-blue-700 font-semibold mb-3 text-lg">Couleur</label>
                        <select name="couleur" id="couleur" class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg bg-white">
                            <option value="#2563eb" style="color:#2563eb">Bleu</option>
                            <option value="#16a34a" style="color:#16a34a">Vert</option>
                            <option value="#eab308" style="color:#eab308">Jaune</option>
                            <option value="#dc2626" style="color:#dc2626">Rouge</option>
                            <option value="#7c3aed" style="color:#7c3aed">Violet</option>
                            <option value="#0e7490" style="color:#0e7490">Cyan</option>
                            <option value="#f59e42" style="color:#f59e42">Orange</option>
                        </select>
                    </div>
                    <!-- Champ Lieu -->
                    <div class="col-span-1">
                        <label for="lieu" class="block text-blue-700 font-semibold mb-3 text-lg">Lieu</label>
                        <input type="text" name="lieu" id="lieu" placeholder="Ex: Salle 101" class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg bg-white">
                    </div>
                    <!-- Champ Description -->
                    <div class="col-span-1">
                        <label for="description" class="block text-blue-700 font-semibold mb-3 text-lg">Description</label>
                        <textarea name="description" id="description" placeholder="Ex: Point sur l'avancement du projet"
                            class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg min-h-[60px] shadow-md resize-none transition-all duration-200 hover:shadow-lg bg-white"></textarea>
                    </div>
                    <!-- Bouton Submit -->
                    <div class="col-span-1 md:col-span-2 xl:col-span-4 flex justify-center mt-4 mb-4">
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-800 text-base font-semibold gap-2 transition-all">
                            <i class="fas fa-plus text-2xl"></i> Ajouter l'évènement
                        </button>
                    </div>
                </form>
            </div>

            <!-- Modale de modification d'évènement -->
            <div id="editEventModal" class="fixed inset-0 bg-blue-900 bg-opacity-40 hidden z-50 flex items-center justify-center">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl mx-auto p-12 border-2 border-blue-200 relative">
                    <button type="button" class="absolute top-6 right-6 text-blue-400 hover:text-blue-700 text-3xl close-edit-modal"><i class="fas fa-times"></i></button>
                    <h3 class="text-2xl font-bold mb-4 flex items-center gap-3 text-blue-800 p-4 "><i class="fas fa-edit text-blue-600"></i> Modifier l'évènement</h3>
                    <form id="editEventForm" method="POST">
                        @csrf
                        <div class="mb-4 p-4">
                            <label class="block text-blue-700 font-semibold mb-2 text-lg">Titre</label>
                            <input type="text" name="titre" id="edit-titre" class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg bg-white" required>
                        </div>
                        <div class="mb-4 flex gap-6 p-4">
                            <div class="flex-1">
                                <label class="block text-blue-700 font-semibold mb-2 text-lg">Date</label>
                                <input type="date" name="date" id="edit-date" class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg bg-white" required>
                            </div>
                            <div class="flex-1 p-4">
                                <label class="block text-blue-700 font-semibold mb-2 text-lg">Heure de début</label>
                                <div class="flex gap-2 items-center">
                                    <input type="number" name="heure_debut_h" id="edit-heure-debut_h" min="0" max="23" step="1" class="border-2 border-blue-200 rounded-xl p-3 text-lg text-center no-spin" required style="width:5rem;" autocomplete="off">
                                    <span class="text-xl font-bold">:</span>
                                    <input type="number" name="heure_debut_m" id="edit-heure-debut_m" min="0" max="59" step="5" class="border-2 border-blue-200 rounded-xl p-3 text-lg text-center no-spin" required style="width:5rem;" autocomplete="off">
                                </div>
                            </div>
                            <div class="flex-1 p-4">
                                <label class="block text-blue-700 font-semibold mb-2 text-lg">Heure de fin</label>
                                <div class="flex gap-2 items-center">
                                    <input type="number" name="heure_fin_h" id="edit-heure-fin_h" min="0" max="23" step="1" class="border-2 border-blue-200 rounded-xl p-3 text-lg text-center no-spin" required style="width:5rem;" autocomplete="off">
                                    <span class="text-xl font-bold">:</span>
                                    <input type="number" name="heure_fin_m" id="edit-heure-fin_m" min="0" max="59" step="5" class="border-2 border-blue-200 rounded-xl p-3 text-lg text-center no-spin" required style="width:5rem;" autocomplete="off">
                                </div>
                            </div>
                            <div class="flex-1 p-4">
                                <label class="block text-blue-700 font-semibold mb-2 text-lg">Couleur</label>
                                <select name="couleur" id="edit-couleur" class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg bg-white">
                                    <option value="#2563eb" style="color:#2563eb">Bleu</option>
                                    <option value="#16a34a" style="color:#16a34a">Vert</option>
                                    <option value="#eab308" style="color:#eab308">Jaune</option>
                                    <option value="#dc2626" style="color:#dc2626">Rouge</option>
                                    <option value="#7c3aed" style="color:#7c3aed">Violet</option>
                                    <option value="#0e7490" style="color:#0e7490">Cyan</option>
                                    <option value="#f59e42" style="color:#f59e42">Orange</option>
                                </select>
                            </div>
                            <div class="flex-1 p-4">
                                <label class="block text-blue-700 font-semibold mb-2 text-lg">Lieu</label>
                                <input type="text" name="lieu" id="edit-lieu" class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg bg-white">
                            </div>
                        </div>
                        <div class="mb-4 p-4">
                            <label class="block text-blue-700 font-semibold mb-2 text-lg">Description</label>
                            <textarea name="description" id="edit-description" class="border-2 border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-2xl p-5 w-full text-lg min-h-[60px] bg-white"></textarea>
                        </div>
                        <div class="flex justify-end gap-4 p-4">
                            <button type="button" class="close-edit-modal inline-flex items-center p-4 border-2 border-blue-200 shadow-sm text-lg font-semibold rounded-2xl text-blue-700 bg-white hover:bg-blue-50 hover:border-blue-400 transition-all">
                                Annuler
                            </button>
                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-800 text-base font-semibold gap-2 transition-all">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mini calendrier pour choisir la date -->
            <div id="mini-calendar" class="mb-8"></div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('editEventModal');
                const form = document.getElementById('editEventForm');
                let currentId = null;

                document.querySelectorAll('.open-edit-modal').forEach(btn => {
                    btn.addEventListener('click', function() {
                        currentId = this.getAttribute('data-id');
                        document.getElementById('edit-titre').value = this.getAttribute('data-titre');
                        document.getElementById('edit-date').value = this.getAttribute('data-date');
                        document.getElementById('edit-heure-debut_h').value = this.getAttribute('data-heure_debut').split(':')[0];
                        document.getElementById('edit-heure-debut_m').value = this.getAttribute('data-heure_debut').split(':')[1];
                        document.getElementById('edit-heure-fin_h').value = this.getAttribute('data-heure_fin').split(':')[0];
                        document.getElementById('edit-heure-fin_m').value = this.getAttribute('data-heure_fin').split(':')[1];
                        document.getElementById('edit-description').value = this.getAttribute('data-description');
                        document.getElementById('edit-couleur').value = this.getAttribute('data-couleur') || '#2563eb';
                        document.getElementById('edit-lieu').value = this.getAttribute('data-lieu') || '';
                        form.action = "{{ route('personnels.Agenda.evenements.modifier', ['id' => 'ID']) }}".replace('ID', currentId);
                        modal.classList.remove('hidden');
                    });
                });
                document.querySelectorAll('.close-edit-modal').forEach(btn => {
                    btn.addEventListener('click', function() {
                        modal.classList.add('hidden');
                    });
                });
                // Fermer la modale en cliquant sur le fond
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
            </script>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Clocklet Timepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/clocklet@0.3.0/css/clocklet.min.css">
<script src="https://cdn.jsdelivr.net/npm/clocklet@0.3.0"></script>
<style>
    /* Supprime les flèches des input type number sur tous les navigateurs */
    input.no-spin::-webkit-outer-spin-button,
    input.no-spin::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input.no-spin[type=number] {
        -moz-appearance: textfield;
    }
</style>
<script>
// Gestion du scroll sur les inputs d'heure/minute
function clamp(val, min, max) {
    return Math.max(min, Math.min(max, val));
}
function pad2(n) {
    return n.toString().padStart(2, '0');
}
function setupScrollInput(id, min, max, step) {
    const input = document.getElementById(id);
    input.addEventListener('wheel', function(e) {
        e.preventDefault();
        let v = parseInt(input.value, 10);
        if (isNaN(v)) v = min;
        if (e.deltaY < 0) v += step;
        else v -= step;
        if (v > max) v = min;
        if (v < min) v = max;
        input.value = pad2(v);
    });
    input.addEventListener('blur', function() {
        let v = parseInt(input.value, 10);
        if (isNaN(v)) v = min;
        v = clamp(v, min, max);
        input.value = pad2(v);
    });
}
document.addEventListener('DOMContentLoaded', function() {
    setupScrollInput('heure_debut_h', 0, 23, 1);
    setupScrollInput('heure_debut_m', 0, 59, 5);
    setupScrollInput('heure_fin_h', 0, 23, 1);
    setupScrollInput('heure_fin_m', 0, 59, 5);
    if(document.getElementById('edit-heure-debut_h')) setupScrollInput('edit-heure-debut_h', 0, 23, 1);
    if(document.getElementById('edit-heure-debut_m')) setupScrollInput('edit-heure-debut_m', 0, 59, 5);
    if(document.getElementById('edit-heure-fin_h')) setupScrollInput('edit-heure-fin_h', 0, 23, 1);
    if(document.getElementById('edit-heure-fin_m')) setupScrollInput('edit-heure-fin_m', 0, 59, 5);
});
</script>
<script>
// Mini calendrier interactif pour la sélection de la date
function renderMiniCalendar(targetId, onSelect) {
    const today = new Date();
    let refDate = new Date(today.getFullYear(), today.getMonth(), 1);
    const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    function getMonday(d) {
        d = new Date(d);
        var day = d.getDay();
        var diff = d.getDate() - (day === 0 ? 6 : day - 1);
        return new Date(d.setDate(diff));
    }
    function formatDate(date) {
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    function draw() {
        const container = document.getElementById(targetId);
        container.innerHTML = '';
        let year = refDate.getFullYear();
        let month = refDate.getMonth();
        let firstDay = new Date(year, month, 1);
        let lastDay = new Date(year, month + 1, 0);
        let start = getMonday(firstDay);
        let end = new Date(lastDay);
        let endDay = end.getDay();
        if (endDay !== 0) {
            end.setDate(end.getDate() + (7 - endDay));
        }
        let days = [];
        let current = new Date(start);
        while (current <= end) {
            days.push(new Date(current));
            current.setDate(current.getDate() + 1);
        }
        // Header
        let html = `<div class='flex items-center justify-between mb-2'>
            <button type='button' class='prev-month px-2 py-1 rounded hover:bg-blue-100'>&lt;</button>
            <span class='font-semibold text-blue-700'>${monthNames[month]} ${year}</span>
            <button type='button' class='next-month px-2 py-1 rounded hover:bg-blue-100'>&gt;</button>
        </div>`;
        html += `<div class='grid grid-cols-7 gap-1 mb-1'>`;
        ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'].forEach(d => {
            html += `<div class='text-xs text-blue-600 text-center font-bold'>${d}</div>`;
        });
        html += `</div><div class='grid grid-cols-7 gap-1'>`;
        days.forEach(day => {
            let isCurrentMonth = day.getMonth() === month;
            let isToday = formatDate(day) === formatDate(today);
            html += `<button type='button' class='mini-cal-day p-2 rounded-lg border text-xs ${isCurrentMonth ? 'bg-white text-blue-900' : 'bg-gray-50 text-gray-400'} ${isToday ? 'border-blue-400 font-bold' : 'border-gray-100'} hover:bg-blue-100' data-date='${formatDate(day)}'>${day.getDate()}</button>`;
        });
        html += `</div>`;
        container.innerHTML = html;
        container.querySelector('.prev-month').onclick = () => { refDate.setMonth(refDate.getMonth() - 1); draw(); };
        container.querySelector('.next-month').onclick = () => { refDate.setMonth(refDate.getMonth() + 1); draw(); };
        container.querySelectorAll('.mini-cal-day').forEach(btn => {
            btn.onclick = () => onSelect(btn.getAttribute('data-date'));
        });
    }
    draw();
}
document.addEventListener('DOMContentLoaded', function() {
    renderMiniCalendar('mini-calendar-create', function(date) {
        document.getElementById('date').value = date;
    });
});
</script>
@endpush