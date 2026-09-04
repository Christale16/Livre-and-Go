@extends('layouts.app')

@section('title', 'Messagerie - Commande #' . $order->id)

@section('content')
<div class="d-flex justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h4 class="mb-1"><i class="bi bi-chat-dots text-primary"></i> Messagerie</h4>
        <p class="text-secondary mb-0">Commande #{{ $order->id }} · {{ $order->pickup_address }} → {{ $order->delivery_address }}</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-chat-left-text"></i> Discussion client / livreur
    </div>
    <div class="card-body">
        <div class="chat-box mb-3" id="chat-box" aria-live="polite">
            @foreach($messages as $m)
                <div class="{{ $m->sender_id === auth()->id() ? 'msg-me' : 'msg-other' }}">
                    <div class="msg-bubble">
                        <small class="d-block {{ $m->sender_id === auth()->id() ? 'text-white-50' : 'text-secondary' }}">
                            {{ $m->sender->name }} · {{ $m->created_at->format('d/m/Y H:i') }}
                        </small>
                        {{ $m->content }}
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('orders.messages.store', $order) }}" class="d-flex gap-2" id="chat-form">
            @csrf
            <input type="text" name="content" class="form-control" placeholder="Écrire un message..." required autocomplete="off">
            <button class="btn btn-primary flex-shrink-0" type="submit">
                <i class="bi bi-send-fill"></i> <span class="d-none d-sm-inline">Envoyer</span>
            </button>
        </form>

        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-3">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
    const currentUserId = {{ auth()->id() }};

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function rafraichirMessages() {
        fetch("{{ route('orders.messages.fetch', $order) }}", {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                return response.json();
            })
            .then(messages => {
                chatBox.innerHTML = '';
                messages.forEach(m => {
                    const mine = Number(m.sender_id) === Number(currentUserId);
                    const wrapper = document.createElement('div');
                    wrapper.className = mine ? 'msg-me' : 'msg-other';

                    const bubble = document.createElement('div');
                    bubble.className = 'msg-bubble';

                    const small = document.createElement('small');
                    small.className = `d-block ${mine ? 'text-white-50' : 'text-secondary'}`;
                    small.textContent = `${m.sender_name ?? 'Utilisateur'} · ${m.created_at ?? ''}`;

                    bubble.appendChild(small);
                    bubble.appendChild(document.createTextNode(m.content ?? ''));
                    wrapper.appendChild(bubble);
                    chatBox.appendChild(wrapper);
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(error => console.warn('Impossible de récupérer les messages :', error));
    }

    document.getElementById('chat-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const input = this.querySelector('input[name="content"]');
        const content = input.value.trim();
        if (!content) return;

        const button = this.querySelector('button');
        button.disabled = true;

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ content })
        })
        .then(async response => {
            if (!response.ok) {
                let message = `Erreur HTTP ${response.status}`;
                try {
                    const data = await response.json();
                    if (data.message) message = data.message;
                } catch (_) {}
                throw new Error(message);
            }
            input.value = '';
            rafraichirMessages();
        })
        .catch(error => {
            console.error('Envoi du message impossible :', error);
            alert('Le message n’a pas pu être envoyé. Vérifiez votre connexion puis réessayez.');
        })
        .finally(() => {
            button.disabled = false;
            input.focus();
        });
    });

    setInterval(rafraichirMessages, 4000);
</script>
@endpush
